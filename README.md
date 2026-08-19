<p align="center">
  <img src="public/backend/dist/img/short_logo.png" alt="FCPMS Logo" width="80">
</p>

<h1 align="center">FCPMS 2.0</h1>

<p align="center">
  <strong>Finance & Contract Management System</strong><br>
  A web application for managing post-disaster reconstruction programs in Bangladesh — projects, packages, BOQ, contractor bills, and printable reports.
</p>

---

## Overview

FCPMS 2.0 streamlines the management of reconstruction programs by providing:

- **Project & Package Management** — organize work into projects, packages, and geographic areas
- **Scheme (Shelter) Registration** — register individual shelter sites with location, design type, and pile foundation details
- **Bill of Quantities (BOQ)** — build versioned BOQs with parts, items, sub-items, rates, and quantities
- **Contractor Management** — register contractors, assign packages, and create user accounts
- **Bill Building** — contractor users record physical measurements at each shelter and the system computes bill quantities (including previous deductions and held-up amounts)
- **Printable Reports** — generate shelter-wise detail reports or package-level summaries for payment processing

## Tech Stack

| Component | Version |
|-----------|---------|
| PHP | >= 8.2 |
| Laravel | 12.x |
| Database | PostgreSQL |
| Frontend | AdminLTE 3, Bootstrap 5, jQuery, Select2, DataTables |
| Build Tool | Vite (with Tailwind CSS 4, Sass) |
| Auth | Multi-guard session auth (Admin + Contractor User) |

## Prerequisites

- PHP 8.2 or higher with extensions: `pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- PostgreSQL 12 or higher
- Composer 2.x
- Node.js 18+ and npm
- Git

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd fcpms

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

## Configuration

Edit `.env` and set the following:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pbms
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

## Database Setup

```bash
# Run all migrations
php artisan migrate

# Seed the database (admin user, contractor user, project, Bangladesh geo data)
php artisan db:seed
```

### Default Credentials

| Role | Email | Password | Notes |
|------|-------|----------|-------|
| Admin | `admin@fcpms.com` | `12345678` | Also requires project code at login (see seeded project) |
| Contractor User | `user@fcpms.com` | `12345678` | Assigned to the seeded project |

> **Important:** Change these passwords in production.

## Running the Application

```bash
# Start all services (server, queue, Vite) concurrently
composer dev

# Or start individually:
php artisan serve          # Web server (http://localhost:8000)
php artisan queue:listen   # Queue worker
npm run dev                # Vite dev server
```

Build for production:

```bash
npm run build
```

## Testing

```bash
# Run all tests
composer test

# Or directly:
php artisan test
```

Tests use an in-memory SQLite database configured in `phpunit.xml`.

## Project Structure

```
fcpms/
├── app/
│   ├── Helper/
│   │   ├── BillGenerator.php         # Shelter-wise & summary bill report rendering
│   │   ├── Constants.php             # App-wide constants (pagination, images)
│   │   ├── ExtendedModel.php         # Base model with UUID PK + admin audit fields
│   │   ├── ExtendedModelUser.php     # Base model with UUID PK + user audit fields
│   │   ├── InWordConvertion.php      # Amount-to-words converter (Taka format)
│   │   ├── PermittedPackage.php      # Admin package access control
│   │   └── PermittedScheme.php       # Admin scheme access control
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                # 16 admin controllers (projects, BOQ, contractors, bills...)
│   │   │   ├── Auth/                 # Login controller (dual admin/user login)
│   │   │   ├── Common/               # AJAX geo endpoints + region CRUD
│   │   │   ├── User/                 # Contractor home controller
│   │   │   └── BillController.php    # Core bill building + measurements (user side)
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php    # Guards admin routes
│   │       └── UserMiddleware.php     # Guards contractor routes
│   └── Models/                       # 28 Eloquent models (all UUID-keyed)
├── database/
│   ├── migrations/                   # 37 migrations
│   └── seeders/                      # Admin, User, Project, Geo seeders
├── docs/
│   ├── PROJECT_DOCUMENTATION.md      # Technical documentation
│   └── USER_MANUAL.md                # End-user manual
├── resources/views/
│   └── backend/
│       ├── admin/                    # Admin panel views (40+ blade files)
│       ├── user/                     # Contractor panel views (20+ blade files)
│       └── bill/                     # Shared printable report layouts
├── routes/web.php                    # All route definitions
└── public/
    ├── adminlte/                     # Vendored AdminLTE assets
    └── backend/                      # Static assets (CSS, JS, images)
```

## Architecture

### Authentication

Two separate login systems share the same codebase:

- **Admin** (`/admin/login`) — email + password + **project code**. The project code scopes the entire session to one project.
- **Contractor User** (`/login`) — email + password. Scoped to an assigned package.

### Domain Model

```
Project
  ├── Package
  │     ├── Scheme (shelter site)
  │     ├── BOQ Version
  │     │     └── BOQ Version Details (part × item × sub-item × option → qty, rate)
  │     └── Contractor
  │           └── User (contractor login)
  └── Bill
        ├── Bill Schemes
        ├── Bill Parts / Items / Sub-Items
        └── Bill Details → Measurements
```

### Key Concepts

- **BOQ (Bill of Quantities)** — structured as Parts → Items → Sub-Items with quantities and rates
- **Scheme Option** — design variants; BOQ lines can vary by option when a part has `has_option_variation`
- **Pile Type** — items can be filtered by foundation type (`NA` / `PC` / `CIS`)
- **Held-Up Quantity** — measurement exceeding BOQ quantity is withheld unless the bill's `calculate_with_heldup` flag is set
- **Bill Serial** — sequential ordering of bills for calculating previous quantities

### Admin Access Control

Admins are linked to projects (`admin_projects`) and packages (`admin_packages`). Queries are automatically scoped to the logged-in admin's project and permitted packages via `PermittedPackage` / `PermittedScheme` helpers.

## AJAX Endpoints

Cascading dropdowns are powered by JSON endpoints under `/common/`:

| Endpoint | Triggers | Returns |
|----------|----------|---------|
| `get-districts-by-division/{id}` | Division change | Districts |
| `get-upazilas-by-district/{id}` | District change | Upazilas |
| `get-unions-by-upazila/{id}` | Upazila change | Unions |
| `get-boq-items-by-part/{id}` | BOQ Part change | BOQ Items |
| `get-boq-sub-item-by-boq-items/{id}` | BOQ Item change | BOQ Sub Items |
| `get-boq-version-by-boq-package/{id}` | Package change | BOQ Versions |
| `get-unit-by-boq-item/{id}` | BOQ Item change | Unit (auto-fill) |
| `get-unit-by-boq-sub-item/{id}` | BOQ Sub Item change | Unit (auto-fill) |

## Documentation

- [Technical Documentation](docs/PROJECT_DOCUMENTATION.md) — architecture, schema, controllers, helpers
- [User Manual](docs/USER_MANUAL.md) — end-user guide for administrators and contractor staff

## License

This project is proprietary software. All rights reserved.
