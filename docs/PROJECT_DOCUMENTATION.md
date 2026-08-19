# FCPMS 2.0 — Project Documentation

## 1. Overview

**FCPMS 2.0** (the folder/repo name is `fcpms`) is a Laravel-based web application for managing **post-disaster reconstruction programs in Bangladesh**. It is used to manage *projects → packages → schemes (shelters) → BOQ (Bill of Quantities) → contractor bills* and to produce printed bill reports.

The system has two operational sides:

- **Admin panel** (`/admin`) — program administration: projects, geographic data, packages, schemes, BOQ structure, contractors, contractor users, and viewing bills/reports.
- **User panel** (`/`) — contractor logins. A contractor user selects a package, builds bills (schemes → BOQ parts → BOQ items → sub-items), records measurements, and generates printable bills.

The branding in the sidebar is "F C P M S 2.0" (database name in `.env` is `pbms`), indicating this is version 2 of the system.

---

## 2. Technology Stack

| Component          | Choice |
|--------------------|--------|
| Framework          | Laravel 12 (`laravel/framework: ^12.0`) |
| PHP                | `^8.2` |
| Database           | PostgreSQL (`.env`: `DB_CONNECTION=pgsql`, db `pbms`) — uses `gen_random_uuid()`, Postgres regex/natural-sort SQL |
| Frontend           | AdminLTE 3 (vendored in `public/adminlte` + `public/backend`), jQuery, Bootstrap 5, Select2, DataTables, SweetAlert2 |
| Assets/build       | Vite (`laravel-vite-plugin`, Tailwind CSS 4, Sass) |
| Auth               | Multi-guard session auth: `web` (contractor users) and `admin` (administrators) |
| Testing            | PHPUnit (Feature + Unit suites, SQLite `:memory:`) |
| Code style tool    | Laravel Pint |

**Key packages:** `laravel/ui`, `laravel/tinker`, `laravel/sail`, `fakerphp/faker`, `phpunit/phpunit`, `nunomaduro/collision`.

---

## 3. Directory Structure

```
app/
  Helper/                      # Shared logic
    BillGenerator.php          # Renders shelter-wise & package-summary bill reports
    Constants.php              # Pagination per page (15), blank image paths
    ExtendedModel.php          # Base model: UUID PK + created_by/updated_by (admin guard)
    ExtendedModelUser.php      # Base model: UUID PK + created_by/updated_by (web guard)
    InWordConvertion.php       # Converts taka amounts to English words
    PermittedPackage.php       # Package IDs permitted for the logged-in admin
    PermittedScheme.php        # Scheme IDs permitted for the logged-in admin
  Http/
    Controllers/
      Admin/                   # Admin-side controllers (projects, packages, schemes, BOQ, contractors, bills)
      Auth/LoginController.php # Dual login (admin + user), multi-project login for admins
      Common/                  # Geo AJAX endpoints (division→district→upazila→union) + region CRUD
      User/UserHomeController.php
      BillController.php       # Core user-side bill building + measurements
    Middleware/
      AdminMiddleware.php      # Redirects to admin.login when admin guard not authenticated
      UserMiddleware.php       # Redirects to user.login when web guard not authenticated
  Models/                      # 28 Eloquent models (all UUID-keyed)
  Providers/
database/
  migrations/                  # 37 migrations (schema below)
  seeders/                     # AdminSeeder, UserSeeder, ProjectSeeder, GeoInfoSeeder, DatabaseSeeder
resources/views/
  backend/admin/               # Admin panel views (layouts, auth, CRUD screens)
  backend/user/                # Contractor-user views (bill building, reports)
  backend/bill/                # Shared printable report layouts (shelter_bill, summary_bill)
routes/web.php                 # All routes
public/
  adminlte/                    # Vendored AdminLTE assets
  backend/                     # AdminLTE dist + app images
  uploads/                     # Uploaded media (e.g., scheme option images)
```

---

## 4. Domain Model

```
Project
  └─ Packages  (belong to project; admin_packages pivot grants admins access)
      └─ Schemes (shelters) — belong to a package & project
      └─ BOQ Versions (versioned BoQs per package/project)
          └─ BoqVersionDetails (part×item×[sub-item]×scheme-option with quantity/rate)
      └─ Contractors (contractor_packages pivot assigns contractor↔package per project)
          └─ Users (contractor login, tied to one project/package)

Bill (contractor's running bill: contractor+project+package+boq_version)
  ├─ BillSchemes  (many-to-many bill ↔ schemes)
  ├─ BillParts    (scheme × boq_part selected into the bill)
  ├─ BillItems    (scheme × boq_part × boq_item selected)
  ├─ BillSubItems (scheme × boq_part × boq_item × boq_subitem)
  └─ BillDetails  (computed line: quantity, previous, held-up, this-bill qty & amounts)
      └─ Measurements (physical measurement lines feeding BillDetails)
```

### Key concepts / terminology

- **BOQ** — Bill of Quantities; structured as **Parts → Items → Sub-items** (an item can have sub-items when `has_sub_items` = true).
- **Scheme** — an individual shelter/construction site; geographic location (division→district→upazila→union) plus optional **pile type** (`NA | PC | CIS`) and optional **scheme option**.
- **Scheme Option** — a design/type variant (e.g., shelter design A/B). BOQ lines can vary by option when a part has `has_option_variation`.
- **boq_type** — `EGP | NONEGP` tag applied at both **Package** and **BoQ Part** level (recently added).
- **Pile type** — a BoQ item may only apply to schemes with a matching pile type (`NA` = applies everywhere).
- **Held-up quantity** — measurement quantity exceeding the BOQ quantity; held amounts are excluded from payment unless the bill's `calculate_with_heldup` flag is set (then the held-up portion is paid too).

---

## 5. Database Schema (summary)

All primary keys are UUIDs. Geo tables (divisions, districts, upazilas, unions) mirror Bangladesh admin geography with `name`, `bn_name`, `url`.

| Table | Notable columns / FKs |
|---|---|
| `projects` | name, code, short_name, budget, funded_by, PD info, ministry, executing/consulting agency, dates |
| `admin_projects` | admin_id → admins, project_id → projects |
| `admin_packages` | admin_id → admins, package_id → packages |
| `packages` | project_id, name, code, `boq_type` (EGP/NONEGP), alias, division/region/district FKs, budgets, dates |
| `scheme_options` | name, project_id, description, image_url, is_active |
| `schemes` | package_id, project_id, `scheme_option_id` (no FK), geo FKs, village_name, external_code, `pile_type`, lat/lng, budgets, dates |
| `units` | name, code, `fields` (JSON — measurement form layout), is_active |
| `boq_parts` | project_id, name, code, `boq_type`, `scheme_option_id` (no FK), `has_option_variation`, description, is_active |
| `boq_items` | boq_part_id, project_id, unit_id, specification_no, name, code, `pile_type`, `has_sub_items`, is_active |
| `boq_sub_items` | boq_part_id, boq_item_id, project_id, unit_id, specification_no, name, code |
| `boq_versions` | project_id, package_id, name, version_date, description, is_active |
| `boq_version_details` | version/package/project FKs, boq_part/item/sub_item FKs, `scheme_option_id` FK, unit_id, `nos`, `total_quantity`, `quantity`, `rate` |
| `contractors` | company + contact-person fields, is_active |
| `contractor_packages` | contractor_id, package_id, project_id (per-project assignment) |
| `bills` | contractor_id, project_id, package_id, boq_version_id, bill_no, bill_date, reference_code, `measurement_from_date`, `measurement_to_date`, name, status (default `Draft`), `serial` (identity), `calculate_with_heldup`, remarks |
| `bill_schemes` | project_id, bill_id, scheme_id |
| `bill_parts` | project_id, bill_id, scheme_id, boq_part_id |
| `bill_items` | bill_id, scheme_id, boq_part_id, boq_item_id |
| `bill_sub_items` | bill_id, scheme_id, boq_part_id, boq_item_id, boq_subitem_id |
| `bill_details` | project/bill/scheme/`scheme_option`/boq_part/item/subitem FKs, quantity, previous_quantity, boq_quantity, held_up_quantity, this_bill_quantity, rate, amount, this_bill_amount |
| `measurements` | project/bill/bill_detail/scheme/boq_part/item/subitem FKs, unit_id, description, nos, length, width, height, weight, quantity |
| `regions` | name, bn_name, url |

FK delete behavior: `restrict` on core master tables; `cascade` on bill/measurement line tables.

---

## 6. Authentication & Authorization

Configured in `config/auth.php` with two guards and two providers:

| Guard | Provider | Model | Used by |
|---|---|---|---|
| `web` | `users` | `App\Models\User` | Contractor users |
| `admin` | `admins` | `App\Models\Admin` | Administrators |

- **User login** (`/login`): email + password via `Auth::guard('web')`.
- **Admin login** (`/admin/login`): email + password **+ project code**. On success the admin's `project_id`/`project_code` are written to the `admins` table (`LoginController::finalize_admin_login`). This scopes the entire admin session to one project.
- **Middleware**: `AdminMiddleware` (admin routes) and `UserMiddleware` (user routes) simply check the respective guard and redirect to the matching login page.
- **Data scoping**: throughout the admin controllers, queries filter by `Auth::guard('admin')->user()->project_id`. Package/scheme access is further restricted through the `PermittedPackage` / `PermittedScheme` helpers (via the `admin_packages` pivot) exposed as the `Package::permitted()` and `Scheme::permitted()` scopes.
- **Base models** (`ExtendedModel` for admin entities, `ExtendedModelUser` for user-created entities) auto-fill the UUID `id` and `created_by` / `updated_by` from the active guard.

---

## 7. Routing (`routes/web.php`)

### Admin group — prefix `/admin` (middleware `admin`)
- `GET|POST /admin/login`, `GET /admin/logout`
- `GET /` → admin dashboard
- Resource routes (`names` under `admin.*`): `regions`, `projects`, `packages`, `scheme-options`, `schemes`, `units`, `boq-parts`, `boq-items`, `boq-sub-items`, `boq-versions`, `boq-version-details`, `contractors`, `contractor-users`, `bills`
- Custom routes:
  - `boq-version-details/copy/{id}`, `check-existing-boq-version-details` (duplicate detection)
  - `boq-version-export-import` (+ `export/{version_id}`)
  - `contractors/add-package/{id}` + `store-package`, `contractors/add-user`
  - `boq-versions/export`, `boq-versions/export-data` (printable BOQ)
  - `bills-by-package/{package_id}`, `bill/details/shelter-wise-details`
  - `get-scheme-by-upazila/{upazila_id}`

### Common group — prefix `/common` (AJAX JSON, no auth)
- `get-districts-by-division/{division_id}`
- `get-upazilas-by-district/{district_id}`
- `get-unions-by-upazila/{upazila_id}`
- `get-boq-items-by-part/{boq_part_id}`
- `get-boq-sub-item-by-boq-items/{boq_item_id}`
- `get-boq-version-by-boq-package/{package_id}`
- `get-unit-by-boq-item/{boq_item_id}`, `get-unit-by-boq-sub-item/{boq_sub_item_id}`
- `get-bill-boq-part-by-bill-scheme/{bill_id}/{scheme_id}`
- `get-bill-boq-items-by-bill-part/{bill_id}/{scheme_id}/{boq_part_id}`

### User group — root prefix (middleware `auth`)
- `GET|POST /login`, `GET /logout`, `GET /` → user dashboard
- Package selection: `get-permitted-packages`, `select-package` (POST)
- Resource `bills` (`user.bills.*`) plus the bill-building workflow routes:
  - `bill/details/scheme/{id}` + `add-scheme` + `remove-scheme`
  - `bill/details/boq-part/{id}` + `add-boq_parts` + `remove-boq-part`
  - `bill/details/boq-item/{id}` + `add-boq_items` + `remove-boq-item`
  - `bill/details/boq_subitem/{id}` + `add-boq_subitems` + `remove-boq-subitem`
  - `bill/details/measurement/{id}` + `add-measurement` + `remove-measurement`
  - `bill/details/unit-wise-view/{id}` (route exists but **no controller method** — dead route)
  - `bill/details/shelter-wise/{id}`, `bill/details`, `bill/details/shelter-wise-details`
  - `bill/get-measurement-suggestions`
  - `bill/regenerate/{id}`
  - `get-scheme-by-upazila/{upazila_id}`, `get-permitted-packages`

---

## 8. Core Workflows

### 8.1 Admin setup flow
1. **Projects** — create the reconstruction program (ProjectController).
2. **Packages** — create packages per project; the creating admin is automatically attached to the package (`PackageController::store`).
3. **Schemes** — register individual shelters with geo location, pile type, scheme option.
4. **BOQ structure** — build Units (with JSON measurement `fields`), BoQ Parts, BoQ Items, BoQ Sub-items (per project).
5. **BOQ Versions** — bundle parts/items/sub-items with quantities & rates into a version (`BoqVersionDetails`). A new version can be created by **copying** an existing version's details. Duplicate detection is enforced via `existingBoqVersionDetails`. `BoqVersionController::export_data` prints the BOQ.
6. **Contractors** — create companies; assign packages (`add_package`/`store_package` syncs `contractor_packages` with the admin's project); create contractor users (`add_user`/`ContractorUserController`).

### 8.2 Contractor user bill workflow
1. User logs in, picks an active package (`UserHomeController`).
2. **Create bill** — name, BOQ version, schemes, bill/measurement dates.
3. **Add schemes** — select which shelters the bill covers.
4. **Add BOQ parts** — pick parts (per scheme or all) from the bill's BOQ version.
5. **Add BOQ items** — pick items under a part for a scheme.
6. **Add sub-items** — where the item `has_sub_items`.
7. **Measurements** — enter physical measurements (nos/length/width/height/weight → computed `quantity`). Stored on `Measurements` and aggregated into a `BillDetail`.
8. **Bill calculation** (in `BillController::storeMeasurement` / `removeMeasurement`):
   - `quantity` = Σ measurement quantities
   - `previous_quantity` = Σ `this_bill_quantity` from prior bills of same contractor/project/package
   - `held_up_quantity` = `max(0, quantity - boq_quantity)` unless `calculate_with_heldup` is set (then 0)
   - `this_bill_quantity` = quantity − previous − held-up
   - `amount` / `this_bill_amount` = (qty − held-up) × rate and this-bill-qty × rate
9. **Reports** — from the bill's *Bill Report* page (user) or the admin Bill page, choose report type and render a printable report (see §9).

---

## 9. Report Generation

Rendering is centralized in **`App\Helper\BillGenerator::shelterWiseView()`**, used by both the user report flow (`BillController::report_show`) and the admin flow (`AdminBillController::bill_show`).

Report types (query param `report_type`):

| Type | Meaning | View |
|---|---|---|
| `PKG_SUM` | Package summary | `backend/bill/summary_bill.blade.php` |
| `UPZ_DTL` | Upazila-wise details | `backend/bill/shelter_bill.blade.php` |
| `SCH_DTL` | Scheme-wise details | `backend/bill/shelter_bill.blade.php` |

The generator:
- Finds the current bill + all **previous** bills (by `serial`) and the **next** bill to determine what work has been billed so far.
- Groups BOQ **parts → items → sub-items** per scheme, pulling `BillDetail` (this bill or latest old bill) with its `Measurements`.
- Applies the `scheme_option_id` filter when the part `has_option_variation`, and the `pile_type` filter on items (`NA` matches all).
- Aggregates BOQ quantity, total/previous/this-bill quantities and this-bill amount into a `$summary_bill` for the package-summary layout.

`InWordConvertion::convert()` renders the total amount in words (Indian numbering: crore/lakh/thousand, "Taka … and … Poisha Only").

---

## 10. Controllers at a Glance

### `BillController` (user side — largest controller)
`index`, `create`, `store`, `edit`, `update`, `destroy`, `show_scheme`/`storeScheme`/`removeScheme`, `show_boq_part`/`storeBoqPart`/`removeBoqPart`, `show_boq_item`/`storeBoqItem`/`removeBoqItem`, `show_boq_subitem`/`storeBoqSubItem`/`removeBoqSubItem`, `show_measurement`/`storeMeasurement`/`removeMeasurement`, `getMeasurementSuggestions`, `shelterWiseView2` (legacy duplicate), `shelterWiseView`, `report`, `report_show`, `regenerate`.

> `regenerate($id)` recomputes bill details then ends with `dd(...)` — currently incomplete/debug-stubbed.

### Admin controllers
- **ProjectController** — full CRUD (all projects visible).
- **PackageController** — CRUD + auto-attach admin; `permitted()` scoped.
- **SchemeController** — CRUD + `getSchemebyUpazila`; `permitted()` scoped.
- **SchemeOptionController** — CRUD with image upload/delete (`uploads/scheme_options`).
- **BoqPartController / BoqItemController / BoqSubItemController** — CRUD + `getBoqItemsByPart` / `getBoqSubItemsByBoqItem` / `getBillBoqPartbyscheme` AJAX endpoints.
- **BoqVersionController** — CRUD with copy-on-create; `getBoqVersionsByPackage`; `export` / `export_data` (printable BOQ).
- **BoqVersionDetailsController** — CRUD with duplicate-check + copy; cascade filters package→version→part→item→sub-item.
- **ContractorController** — CRUD + `add_package`/`store_package`/`add_user`/`store_user`.
- **ContractorUserController** — user CRUD (derives project/package from contractor's first package).
- **AdminBillController** — bill list by package + report rendering (`bill_show`) + `getBillsByPackage` JSON.
- **AdminHomeController** — dashboard view.
- **BoqVersionExportImportController / BoqExportImoprtController** — non-functional stubs (empty index; export not implemented).
- **Admin/UserController** — empty stub.

### Common controllers
`DistrictController`, `UpazilaController`, `UnionController` (geo cascading JSON), `RegionController` (CRUD for `regions`).

### Auth / User
`LoginController` (dual login, admin project-code flow), `UserHomeController` (dashboard, scheme/package AJAX, package selection). `UnitController` (top-level) provides unit CRUD + unit AJAX lookups.

---

## 11. Helper Classes

| Class | Purpose |
|---|---|
| `ExtendedModel` | Base for admin-scoped models — UUID PK + auto `created_by`/`updated_by` (admin guard) |
| `ExtendedModelUser` | Base for user-scoped models — UUID PK + auto `created_by`/`updated_by` (web guard) |
| `Constants` | `PAGINATION_PER_PAGE = 15`, blank image paths |
| `PermittedPackage` | Package IDs from `admin_packages` for the current admin |
| `PermittedScheme` | Scheme IDs for the current admin's permitted packages |
| `BillGenerator` | Report assembly/rendering (shelter-wise + package summary) |
| `InWordConvertion` | Amount → English words in Bangladesh currency format |

---

## 12. Frontend

- **AdminLTE 3** layouts with sidebar/topbar/footer partials for both admin and user panels.
- Components used: Select2 (dropdowns), jQuery datepicker (dates), DataTables (tables), SweetAlert2 (delete confirmations), AJAX-dependent dropdowns (division→district→upazila→union, package→version, part→item→sub-item).
- Printable report layouts (`backend/bill/shelter_bill.blade.php`, `summary_bill.blade.php`) use custom print CSS with portrait/landscape rules.
- Login pages accept admin **project code** (admin) — a second project code field.

---

## 13. Seeders

| Seeder | Content |
|---|---|
| `AdminSeeder` | One admin: `admin@fcpms.com` / `12345678` (fixed UUID) |
| `UserSeeder` | One contractor user: `user@fcpms.com` / `12345678` (fixed UUID) |
| `ProjectSeeder` | One sample "RIVER Project" + admin-project link |
| `GeoInfoSeeder` | Full Bangladesh geography: 8 divisions, 64 districts, ~491 upazilas, ~4,540 unions |
| `DistrictSeeder` | Incomplete/legacy geo data — defines arrays but inserts nothing (dead code) |

Run: `php artisan db:seed` (DatabaseSeeder calls all above in order).

---

## 14. Testing

- `tests/Feature/ExampleTest.php` — `GET /` returns 200 (smoke test).
- `tests/Unit/ExampleTest.php` — placeholder assertion.
- `phpunit.xml` — Feature + Unit suites; `APP_ENV=testing`, SQLite `:memory:`, cache `array`, mail `array`, queue `sync`.
- Run with `composer test` (clears config then runs `php artisan test`).

---

## 15. Setup / Run

```bash
composer install
npm install && npm run build        # or npm run dev
cp .env.example .env                # set PostgreSQL credentials (DB_CONNECTION=pgsql, DB_DATABASE=pbms)
php artisan key:generate
php artisan migrate --seed
php artisan serve
# dev stack (concurrently): composer dev
```

---

## 16. Known Issues / Observations

- `BillController::regenerate` ends in `dd(...)` — incomplete (leftover debug).
- `unitWiseView` route (`user.bills.unit_wise_view`) has no controller method (dead route).
- `shelterWiseView2` in `BillController` is an older duplicate implementation superseded by the `BillGenerator` path.
- `BoqVersionExportImportController` and `BoqExportImoprtController` are stubs — import/export BOQ functionality is not implemented (the real printable export is `BoqVersionController::export_data`).
- `Admin/UserController`, `Admin/UnitController`, `Admin/RegionController` — empty/missing; active controllers are top-level `UnitController` and `Common/RegionController`.
- `DistrictSeeder` is dead code (arrays defined, nothing inserted).
- `schemes.scheme_option_id` and `boq_parts.scheme_option_id` have no FK constraints.
- `Bill` model `fillable` lacks `package_id` and `serial`, though both are written/used in code.
- `BillController::shelterWiseView` still carries commented-out legacy code; bill "previous bills" are identified via `serial` (new) in some flows and via `created_at` in others.
