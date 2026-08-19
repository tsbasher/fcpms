# FCPMS 2.0 — User Manual

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Logging In](#2-logging-in)
3. [Admin Panel — Overview](#3-admin-panel--overview)
4. [Admin Panel — Managing Master Data](#4-admin-panel--managing-master-data)
5. [Admin Panel — Building the BOQ](#5-admin-panel--building-the-boq)
6. [Admin Panel — Contractors](#6-admin-panel--contractors)
7. [Admin Panel — Viewing Bills](#7-admin-panel--viewing-bills)
8. [Contractor User — Overview](#8-contractor-user--overview)
9. [Contractor User — Selecting a Package](#9-contractor-user--selecting-a-package)
10. [Contractor User — Building a Bill](#10-contractor-user--building-a-bill)
11. [Contractor User — Generating Reports](#11-contractor-user--generating-reports)
12. [Report Types Explained](#12-report-types-explained)
13. [Frequently Asked Questions](#13-frequently-asked-questions)
14. [Glossary](#14-glossary)

---

## 1. Introduction

### What is FCPMS?

FCPMS 2.0 (Finance & Contract Management System) is a web-based application used to manage post-disaster reconstruction programs in Bangladesh. It provides a structured way to:

- Register reconstruction **projects** and organize them into **packages**
- Define individual shelter sites as **schemes** with geographic and design details
- Build and version a **Bill of Quantities (BOQ)** — the priced schedule of work items
- Register **contractors** and create login accounts for their staff
- Let contractor staff **build bills** by recording physical measurements at each shelter site
- Generate **printable bill reports** for payment processing

### Who uses FCPMS?

There are two types of users:

| Role | Description |
|------|-------------|
| **Administrator** | Program staff who set up projects, packages, BOQ structures, schemes, and contractors. They also review and verify bills. |
| **Contractor User** | Staff employed by a contractor company. They log in, select their assigned package, build bills by entering measurements, and generate reports. |

### Key terminology at a glance

| Term | Meaning |
|------|---------|
| **Project** | A reconstruction program (e.g., "Flood Recovery 2025") |
| **Package** | A group of work within a project, tied to a geographic area and budget |
| **Scheme** | An individual shelter or construction site within a package |
| **BOQ** | Bill of Quantities — the master list of work items, quantities, and rates |
| **Part** | A major section of the BOQ (e.g., "Civil Works", "Electrical") |
| **Item** | A specific work item within a part (e.g., "Foundation excavation") |
| **Sub-Item** | A further breakdown of an item (e.g., specific foundation types) |
| **Bill** | A contractor's claim for payment, covering measurements at selected schemes |
| **Measurement** | A physical measurement record (e.g., "3 pits, each 2m × 1.5m × 1m") |

---

## 2. Logging In

### Admin Login

1. Open your web browser and navigate to the FCPMS login page.
2. Click **Admin Login** (or navigate to `/admin/login`).
3. You will see the admin login form with three fields:
   - **Email** — your registered email address
   - **Password** — your account password
   - **Project Code** — the short code of the project you want to work on (e.g., `RIVER`)
4. Enter all three fields and click **Login**.

> **Important:** You must enter a valid Project Code. You can only access projects that have been assigned to your admin account. If you enter a wrong project code, you will see an error message: *"You have no permission to this project."*

5. On successful login, you are taken to the **Admin Dashboard**. The top bar will display your project code as a green badge.

### Contractor User Login

1. Navigate to the FCPMS login page (the root URL `/`).
2. You will see the contractor user login form with two fields:
   - **Email** — your registered email address
   - **Password** — your account password
3. Enter your credentials and click **Login**.
4. On successful login, you are taken to the **Contractor Dashboard**. The top bar will display your assigned package name (clickable — see [Section 9](#9-contractor-user--selecting-a-package)).

### If Login Fails

- **"Invalid credentials"** — check your email and password. Contact your administrator if you have forgotten your password.
- **"You have no permission to this project"** (admin only) — the Project Code you entered is not assigned to your account. Check with your administrator for the correct code.

### Logging Out

Click **Logout** in the left sidebar (shown in red text). You will be returned to the login page.

---

## 3. Admin Panel — Overview

### Layout

The admin panel uses a standard layout with three areas:

- **Left Sidebar** — navigation menu with links to all admin sections
- **Top Bar** — shows the current project code (green badge), project name, and a user dropdown with your name and a logout option
- **Content Area** — the main content of the current page

### Sidebar Navigation

The sidebar contains the following menu items (each highlighted in blue when active):

| # | Menu Item | Purpose |
|---|-----------|---------|
| 1 | **Dashboard** | Admin home page |
| 2 | **Projects** | Manage reconstruction projects |
| 3 | **Regions** | Manage geographic regions |
| 4 | **Packages** | Manage work packages within your project |
| 5 | **Scheme Options** | Manage shelter design variants |
| 6 | **Schemes** | Manage individual shelter sites |
| 7 | **Units** | Manage measurement units |
| 8 | **BOQ Parts** | Manage BOQ sections |
| 9 | **BOQ Items** | Manage BOQ line items |
| 10 | **BOQ Sub Items** | Manage BOQ sub-items |
| 11 | **BOQ Versions** | Manage BOQ versions |
| 12 | **BOQ Versions Details** | Manage BOQ line-by-line rates and quantities |
| 13 | **Contractors** | Manage contractor companies |
| 14 | **Contractor Users** | Manage contractor user accounts |
| 15 | **Bill** | View and generate bill reports |
| 16 | **Logout** | Sign out (shown in red) |

### Common UI Patterns

Throughout the admin panel you will encounter these recurring patterns:

- **List pages** show a searchable table with an **Add** button at the top. Each row has **Edit** and **Delete** buttons. Deleting a record shows a confirmation dialog (SweetAlert popup — click **OK** to confirm or **Cancel** to abort).
- **Create/Edit pages** show a form with labeled fields. Fields marked with a red asterisk (`*`) are required. Click **Save** to submit or **Back** to return to the list.
- **Cascading dropdowns** — when you select a value in one dropdown (e.g., Division), the next dropdown (e.g., District) automatically loads and shows only relevant options. This cascading applies for: Division → District → Upazila → Union, Package → BOQ Version, BOQ Part → BOQ Item, BOQ Item → BOQ Sub Item.
- All dropdowns use **Select2** — you can type to search within the dropdown options.

---

## 4. Admin Panel — Managing Master Data

### 4.1 Projects

A **Project** represents a reconstruction program (e.g., "Flood Recovery 2025"). You must create a project before creating packages, schemes, or BOQ data.

**Creating a Project:**

1. Click **Projects** in the sidebar.
2. Click the **Add** button.
3. Fill in the form fields:

| Field | Required | Description |
|-------|----------|-------------|
| Name | Yes | Full project name |
| Code | Yes | Short unique identifier (used at admin login) |
| Short Name | No | Abbreviated name |
| Description | No | Brief description of the project |
| Approval Date | No | When the project was approved |
| Planned Start Date | No | Intended start date |
| Planned End Date | No | Intended end date |
| Actual Start Date | No | Real start date |
| Actual End Date | No | Real end date |
| Budget | No | Total project budget |
| Funded By | No | Funding organization |
| PD Name | No | Project Director's name |
| PD Contact No | No | PD's phone number |
| PD Email | No | PD's email address |
| Ministry | No | Government ministry |
| Executing Agency | No | Agency carrying out the work |
| Consulting Agency | No | Consulting firm |
| Is Active | Yes | Toggle to activate/deactivate (checked = active) |

4. Click **Save** to create the project.

**Editing a Project:** Click **Edit** on any row in the project list. Make changes and click **Save**.

> **Note:** You are automatically assigned to any project you create (via the admin-projects link). Your login is scoped to one project at a time.

---

### 4.2 Regions

Regions are geographic administrative divisions (e.g., "Dhaka Division", "Chattogram Division").

**Managing Regions:**

1. Click **Regions** in the sidebar.
2. Click **Add** to create a new region, providing a **Name** and optionally a Bangla name and URL.
3. Click **Save**.

Regions are used when creating packages to indicate the geographic scope.

---

### 4.3 Packages

A **Package** groups work within a project, tied to a geographic area (division/region/district) and budget. Each package has its own BOQ and contractors.

**Creating a Package:**

1. Click **Packages** in the sidebar.
2. Click **Add**.
3. Fill in the form:

| Field | Required | Description |
|-------|----------|-------------|
| Project | Yes | Auto-selected (your current project) |
| Name | Yes | Package name |
| Code | Yes | Short unique code |
| BOQ Type | Yes | `EGP` (Electronic Government Procurement) or `NONEGP` (Non-EGP) |
| Alias | No | Alternative name |
| Division | No | Geographic division (cascades to District) |
| Region | No | Geographic region |
| District | No | Geographic district (auto-loads upazilas when viewing schemes) |
| Description | No | Brief description |
| Bid Invitation Date | No | When bids were invited |
| Bid Submission Date | No | Submission deadline |
| Planned Start Date | No | Intended start |
| Planned End Date | No | Intended end |
| Actual Start Date | No | Real start |
| Actual End Date | No | Real end |
| Planned Budget | No | Estimated budget |
| Actual Budget | No | Confirmed budget |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

> **Important:** When you create a package, you are automatically linked to it. This determines which packages you can manage. The package also determines which BOQ and contractors are available to contractor users assigned to it.

**Editing a Package:** Click **Edit** on the row, modify fields, and click **Save**.

**Deleting a Package:** Click **Delete** on the row and confirm the popup.

---

### 4.4 Scheme Options

**Scheme Options** represent design variants for shelters (e.g., "Design A — Single Storey", "Design B — Double Storey"). BOQ quantities and rates can vary by scheme option.

**Creating a Scheme Option:**

1. Click **Scheme Options** in the sidebar.
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| Name | Yes | Option name (e.g., "Option A") |
| Description | No | Details about this design variant |
| Image | No | Upload an image file (e.g., a design diagram) |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

**Editing a Scheme Option:** Click **Edit**. You can replace the uploaded image or remove it. Click **Save** to apply changes.

> **Tip:** Scheme options are used when BOQ Parts have the "Has Option Variation" flag enabled. This allows different quantities/rates for different shelter designs.

---

### 4.5 Schemes (Shelters)

A **Scheme** represents an individual shelter or construction site. Each scheme belongs to a package and has a geographic location, design type, and optional pile type.

**Creating a Scheme:**

1. Click **Schemes** in the sidebar.
2. Click **Add**.
3. Fill in the form:

| Field | Required | Description |
|-------|----------|-------------|
| Package | Yes | Select the package (from your permitted packages) |
| Name | Yes | Scheme name (e.g., "Shelter S-001") |
| Code | Yes | Unique code (sorted naturally: S1, S2, S10 after S2) |
| Alias | No | Alternative identifier |
| Description | No | Details |
| Division | Yes | Geographic division (cascades to District) |
| District | Yes | Geographic district (cascades to Upazila) |
| Upazila | Yes | Geographic upazila (cascades to Union) |
| Union | Yes | Geographic union |
| Village Name | No | Specific village |
| External Code | No | Code from an external system |
| Pile Type | Yes | Foundation type: `NA` (not applicable / standard), `PC` (precast), or `CIS` |
| Latitude | No | GPS latitude |
| Longitude | No | GPS longitude |
| Scheme Option | No | Design variant (from Scheme Options list) |
| Signing Date | No | Contract signing date |
| Planned Start Date | No | Intended start |
| Planned End Date | No | Intended end |
| Actual Start Date | No | Real start |
| Actual End Date | No | Real end |
| Planned Budget | No | Estimated cost |
| Actual Budget | No | Confirmed cost |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

**Filtering Schemes:** On the scheme list page you can filter by:
- Division → District → Upazila → Union (cascading dropdowns)
- Package
- Search text (matches on name or code)

> **About Pile Type:** BOQ Items can be tagged with a pile type (`NA`, `PC`, or `CIS`). When a scheme has a pile type, only matching BOQ items (or items tagged `NA` which match all) will appear in measurements and reports for that scheme.

---

### 4.6 Units

**Units** define how measurements are expressed (e.g., "Sq. Meter", "Cubic Meter", "Pcs"). Each unit can have a custom set of measurement fields.

**Creating a Unit:**

1. Click **Units** in the sidebar.
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| Name | Yes | Unit name (e.g., "Cubic Meter") |
| Code | Yes | Short code (e.g., "CUM") |
| Description | No | Additional info |
| Measurement Fields | No | JSON array defining the measurement form fields. Example: `[{"label":"Length","field":"length"},{"label":"Width","field":"width"},{"label":"Height","field":"height"}]` |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

> **About Measurement Fields:** The JSON `fields` array controls which dimension fields appear in the measurement form for this unit. Common combinations:
> - `nos` only — for counting items (pieces, numbers)
> - `length × width × height` — for volume measurements
> - `length × width` — for area measurements
> - `length × width × height × weight` — for all dimensions

---

## 5. Admin Panel — Building the BOQ

The **Bill of Quantities (BOQ)** is the priced schedule that defines what work will be done, in what quantities, and at what rate. It is structured in a three-level hierarchy:

```
Part (e.g., "Civil Works")
  └── Item (e.g., "Foundation Excavation")
        └── Sub-Item (e.g., "Type A Foundation", "Type B Foundation")
```

An item only has sub-items if the **Has Sub Items** flag is enabled on the item.

### 5.1 BOQ Parts

A **Part** is a major section of the BOQ (e.g., "Civil Works", "Electrical Works", "Sanitary Works").

**Creating a BOQ Part:**

1. Click **BOQ Parts** in the sidebar.
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| Name | Yes | Part name |
| Code | Yes | Section code (e.g., "A", "B", "E") — used for sorting |
| BOQ Type | Yes | `EGP` or `NONEGP` |
| Scheme Option | No | If set, this part's quantities/rates vary by scheme option |
| Description | No | Details |
| Has Option Variation | Yes | When checked, BOQ details for this part will be filtered by the scheme's design option. Leave unchecked if all schemes use the same rates for this part. |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

> **Has Option Variation explained:** If you check this flag, then when building the BOQ Version Details for this part, you must specify a Scheme Option for each line. During bill reporting, the system will match the scheme's option to find the correct rate and quantity. Use this when different shelter designs have different construction costs for the same part.

---

### 5.2 BOQ Items

An **Item** is a specific work task within a Part (e.g., "Foundation excavation", "Column casting").

**Creating a BOQ Item:**

1. Click **BOQ Items** in the sidebar.
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| BOQ Part | Yes | Parent part (cascading: selecting a part shows its items elsewhere) |
| Project | Yes | Your current project (auto-filled) |
| Unit | No | Measurement unit (e.g., "Cubic Meter") — auto-filled when selected via BOQ Sub Item |
| Specification No | No | Reference to a specification document |
| Name | Yes | Item name |
| Code | Yes | Item code (sorted alphanumerically: A1, A2, A10 after A2) |
| Pile Type | Yes | `NA` (applies to all schemes), `PC` (precast pile schemes only), or `CIS` (CIS pile schemes only) |
| Description | No | Detailed description |
| Has Sub Items | Yes | When checked, this item requires sub-items before measurements can be entered. When unchecked, measurements are entered directly against this item. |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

**Filtering Items:** On the item list you can filter by **BOQ Part** and search text. Items are sorted naturally by code (e.g., A1, A2, A10 appears after A2).

> **About Pile Type on Items:** This filters which items appear for a given scheme. If an item is tagged `PC`, it will only appear in measurements for schemes with `Pile Type = PC`. Items tagged `NA` appear for all schemes regardless of pile type.

---

### 5.3 BOQ Sub Items

A **Sub-Item** is a further breakdown of an item that has **Has Sub Items** enabled.

**Creating a BOQ Sub Item:**

1. Click **BOQ Sub Items** in the sidebar.
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| BOQ Part | Yes | Parent part (used for cascading) |
| BOQ Item | Yes | Parent item (auto-loaded based on selected part) |
| Project | Yes | Your current project |
| Unit | Yes | Measurement unit |
| Specification No | No | Reference number |
| Name | Yes | Sub-item name |
| Code | Yes | Sub-item code |
| Description | No | Details |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

> **Note:** You should create sub-items for an item **before** setting the item's "Has Sub Items" flag to true. The sub-items will then be available when building BOQ Versions and when contractor users enter measurements.

---

### 5.4 BOQ Versions

A **BOQ Version** is a snapshot of the BOQ with specific quantities and rates for a package. You can create multiple versions over time (e.g., "Version 1 - Original", "Version 2 - Revised").

**Creating a BOQ Version:**

1. Click **BOQ Versions** in the sidebar.
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| Package | Yes | The package this BOQ applies to |
| Name | Yes | Version name (e.g., "Version 1") |
| Version Date | No | Date of this version |
| Description | No | Notes about this version |
| Is Active | Yes | Toggle active status |
| Copy From Existing Version | No | Select an existing version to clone all its BOQ Version Details into this new version |

4. Click **Save**.

> **Tip — Copying an existing version:** If you need to create a revised BOQ that is very similar to an existing one, use the "Copy From Existing Version" dropdown. This will copy all line items (details) from the source version into the new version. You can then edit individual lines as needed.

---

### 5.5 BOQ Version Details (Rates & Quantities)

**BOQ Version Details** define the actual quantities and unit rates for each line in a BOQ version. This is where you set how much of each item exists and what it costs.

**Adding Details:**

1. Click **BOQ Versions Details** in the sidebar.
2. Click **Add**.
3. Use the cascading dropdowns to select:
   - **Package** → **BOQ Version** → **BOQ Part** → **BOQ Item** → (if applicable) **BOQ Sub Item**
   - **Scheme Option** — required if the part has "Has Option Variation" enabled
4. Fill in the remaining fields:

| Field | Required | Description |
|-------|----------|-------------|
| Unit | Yes | Measurement unit (auto-filled from the item) |
| Nos | Yes | Number of units |
| Total Quantity | Yes | Total quantity (calculated automatically on save: nos × quantity, rounded to 3 decimals) |
| Quantity | Yes | Quantity per unit |
| Rate | Yes | Unit rate (price per quantity) |

5. Click **Save**.

> **Duplicate detection:** If you try to save a detail that already exists (same version + package + part + item + sub-item + scheme option), the system will detect it and either update the existing record or prompt you. The `Check Existing` feature on the index page lets you search for duplicates before saving.

**Copying a Detail:** On the BOQ Version Details list, click the **Copy** button on any row. This opens the Create form pre-filled with that row's values. Adjust the fields you want to change and click **Save**.

**Editing Details:** Click **Edit** on any row. The system recomputes `Total Quantity` automatically when you save.

**Filtering:** The detail list supports cascading filters: Package → Version → Part → Item → Sub Item. Use these to narrow down the list.

---

### 5.6 Exporting / Printing the BOQ

To view and print a formatted BOQ:

1. Click **BOQ Versions** in the sidebar.
2. Click the **Export** button on the version you want to print (or use the Export route from the menu).
3. Select the Package and BOQ Version.
4. The system displays a printable BOQ view grouped by Part → Item → Sub-Item, showing quantities, rates, and totals.
5. Use your browser's **Print** function (Ctrl+P / Cmd+P) to print or save as PDF.

> **Note:** The BOQ Export/Import feature for file-based import/export is under development and not yet available.

---

## 6. Admin Panel — Contractors

### 6.1 Creating a Contractor

A **Contractor** represents a construction company.

1. Click **Contractors** in the sidebar.
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| Company Name | Yes | Full company name |
| Company Email | No | Company email |
| Company Phone | No | Company phone number |
| Company Address | No | Full address |
| Company Website | No | Website URL |
| Company Registration Code | No | Official registration number |
| Contact Person Name | Yes | Primary contact's name |
| Contact Person Email | No | Contact's email |
| Contact Person Phone | No | Contact's phone |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

---

### 6.2 Assigning Packages to a Contractor

Before contractor users can build bills, the contractor must be assigned to packages.

1. On the Contractors list, click the **Add Package** button on the contractor's row.
2. You will see a list of packages from your permitted packages.
3. Check the packages you want to assign to this contractor.
4. Click **Save** to assign the packages.

> **Note:** Each package assignment is linked to your current project. The contractor will only see schemes and BOQs for the assigned packages.

---

### 6.3 Creating Contractor Users

A **Contractor User** is a person who logs in as the contractor to build bills.

1. On the Contractors list, click the **Users** button on the contractor's row (or use **Contractor Users** in the sidebar).
2. Click **Add**.
3. Fill in:

| Field | Required | Description |
|-------|----------|-------------|
| Name | Yes | User's full name |
| Email | Yes | Login email |
| Password | Yes | Login password (will be stored encrypted) |
| Phone | No | Phone number |
| Contractor | Yes | Parent contractor (auto-filled if adding from contractor page) |
| Package | Yes | Package this user works on (auto-filled from contractor's first package) |
| Is Active | Yes | Toggle active status |

4. Click **Save**.

> **Important:** Each contractor user is assigned to a specific **package**. When they log in, they will only see schemes, BOQ data, and bills for that package. If you need the user to work on multiple packages, they will need to select the package after login (see Section 9).

---

## 7. Admin Panel — Viewing Bills

Administrators can view contractor bills and generate printable reports.

### Accessing Bills

1. Click **Bill** in the sidebar.
2. You will see a filter form with the following fields:

| Field | Description |
|-------|-------------|
| Package | Select a package (from your permitted packages) |
| Upazila | (Loaded automatically based on the package's district) |
| Bill | (Loaded automatically when a package is selected) |
| Report Type | Choose the report format (see below) |

### Generating a Report

1. Select a **Package**. The upazila dropdown will populate based on the package's district.
2. Optionally select an **Upazila** to narrow down the report.
3. Select a **Bill** from the dropdown (loaded via AJAX based on the package).
4. Choose a **Report Type**:
   - **Package Summary** (`PKG_SUM`) — aggregated summary across all shelters
   - **Upazila Details** (`UPZ_DTL`) — detailed breakdown by upazila
   - **Scheme Details** (`SCH_DTL`) — detailed breakdown by individual scheme
5. Click **View Report** (or **Submit**).

The report opens in a new page formatted for printing (A4 landscape layout). Use your browser's **Print** function to print or save as PDF.

---

## 8. Contractor User — Overview

### Layout

The contractor panel has a simpler layout than the admin panel:

- **Left Sidebar** — three items: Dashboard, Bills, Bill Report, Logout
- **Top Bar** — shows your project code (indigo badge), project name, and a **clickable package name** (see Section 9). On the right: user dropdown with your name and logout.
- **Content Area** — main page content

### Sidebar Navigation

| Menu Item | Purpose |
|-----------|---------|
| **Dashboard** | Home page |
| **Bills** | Create and manage your bills |
| **Bill Report** | Generate printable bill reports |
| **Logout** | Sign out |

---

## 9. Contractor User — Selecting a Package

When you log in, you are assigned to a default package. If you need to work on a different package:

1. Look at the **top bar** — you will see your currently selected package name displayed as a clickable link.
2. **Click the package name**. A popup modal will appear showing all packages your contractor account is permitted to work on.
3. Select the desired package from the list.
4. The page will reload with the new package active.

> **Note:** All bill operations (creating, measuring, reporting) are scoped to your currently selected package. Make sure you have selected the correct package before starting work.

---

## 10. Contractor User — Building a Bill

Building a bill is a multi-step process. You progressively add more detail to the bill — from the high-level scheme selection down to individual measurements.

### 10.1 Creating a Bill

1. Click **Bills** in the sidebar.
2. Click the **Add** button.
3. Fill in the form:

| Field | Required | Description |
|-------|----------|-------------|
| Bill Name | Yes | Descriptive name (e.g., "Monthly Bill - July 2025") |
| BOQ Version | Yes | Select the BOQ version this bill uses |
| Bill No | No | Reference bill number |
| Reference Code | No | External reference code |
| Bill Date | Yes | Date of the bill (date picker) |
| Measurement From Date | No | Start date of the measurement period |
| Measurement To Date | No | End date of the measurement period |
| Remarks | No | Additional notes |
| Schemes | Yes | Select one or more schemes this bill covers (multi-select) |

4. Click **Save**. You are redirected to the bills list with a success message.

> **Tip:** You can select multiple schemes at once using the Schemes multi-select dropdown (hold Ctrl/Cmd and click to select multiple).

---

### 10.2 Adding Schemes to the Bill

After creating a bill, you can modify which schemes are included.

1. From the Bills list, click the **Edit** button (or click on the bill to open it).
2. The **Scheme** tab shows all schemes currently in the bill.
3. To add more schemes:
   - Select schemes from the dropdown
   - Click **Add Scheme**
4. To remove a scheme:
   - Click the **Remove** button next to the scheme name
   - Confirm the popup

> **Important:** Schemes are the foundation of a bill. You cannot add BOQ parts or items until you have at least one scheme in the bill.

---

### 10.3 Adding BOQ Parts

1. Navigate to the **BOQ Parts** tab of your bill.
2. You will see a scheme selector at the top. Choose which scheme you want to add parts for, or select "All" to add parts to all schemes.
3. Check the BOQ parts you want to include (these come from your bill's BOQ version).
4. Click **Add Parts** (or **Save**).
5. The selected parts will appear in the bill's parts list.

**Removing a Part:** Click the **Remove** button next to the part and confirm.

---

### 10.4 Adding BOQ Items

1. Navigate to the **BOQ Items** tab.
2. Select a **Scheme** and a **BOQ Part** from the dropdowns.
3. Check the items you want to include under that part.
4. Click **Add Items** (or **Save**).

**Removing an Item:** Click **Remove** and confirm.

---

### 10.5 Adding BOQ Sub Items (when applicable)

If an item has sub-items (the "Has Sub Items" flag is enabled), you must select which sub-items to include.

1. Navigate to the **BOQ Sub Items** tab.
2. Select the **Scheme**, **BOQ Part**, and **BOQ Item**.
3. Check the sub-items you want to include.
4. Click **Add Sub Items** (or **Save**).

**Removing a Sub-Item:** Click **Remove** and confirm.

> **Note:** For items without sub-items, you can skip this step and go directly to measurements.

---

### 10.6 Entering Measurements

Measurements are the core of bill building. Each measurement records the physical dimensions of work done.

1. Navigate to the **Measurements** tab.
2. Select the **Scheme**, **BOQ Part**, and **BOQ Item** (and **Sub-Item** if applicable).
3. The measurement form appears below, showing dimension fields based on the unit's configuration.
4. Fill in the measurement:

| Field | Description |
|-------|-------------|
| Description | Text description of the measurement (e.g., "Foundation excavation at shelter base"). An autocomplete suggests previous descriptions for the same part/item. |
| Nos | Number of units (e.g., number of pits, slabs, etc.) |
| Length | Length dimension (if applicable) |
| Width | Width dimension (if applicable) |
| Height | Height dimension (if applicable) |
| Weight | Weight dimension (if applicable) |

5. Click **Add Measurement** (or **Save**).

> **How quantity is calculated:** The system multiplies all dimensions together:
> `quantity = Nos × Length × Width × Height × Weight`
> Any missing dimension defaults to 1.

**Existing measurements** are listed below the form. Each shows the description, dimensions, and computed quantity. You can remove a measurement by clicking its **Remove** button.

**The previous bill's measurements** are also displayed above the form for reference, so you can see what was billed before.

---

### 10.7 How the Bill Calculates Quantities

After you add or remove a measurement, the system automatically recalculates the **Bill Detail** line for that item/scheme combination:

| Field | Formula |
|-------|---------|
| **Quantity** | Sum of all measurement quantities for this bill detail |
| **Previous Quantity** | Sum of `this_bill_quantity` from all previous bills of the same contractor, project, and package |
| **Held-Up Quantity** | If `calculate_with_heldup` is OFF: `max(0, Quantity − BOQ Quantity)`. If ON: `0` |
| **This Bill Quantity** | `Quantity − Previous Quantity − Held-Up Quantity` |
| **Amount** | `(Quantity − Held-Up Quantity) × Rate` |
| **This Bill Amount** | `This Bill Quantity × Rate` |

> **What is Held-Up Quantity?** When the measured quantity exceeds the BOQ quantity, the excess is "held up" — it is not paid in the current bill. This prevents over-payment beyond the contracted amount. If the `calculate_with_heldup` flag is enabled on the bill, held-up amounts are not deducted (i.e., the contractor gets paid for everything).

> **Previous Quantity** prevents double-counting. If you measured 100 cubic meters in a previous bill, and the cumulative total is now 150, only 50 is billed in this bill.

---

## 11. Contractor User — Generating Reports

### Accessing the Report Page

1. Click **Bill Report** in the sidebar.
2. You will see a filter form:

| Field | Description |
|-------|-------------|
| Upazila | Select an upazila to filter schemes (optional) |
| Scheme | (Loaded via AJAX when an upazila is selected) |
| Bill | Select the bill to generate a report for |
| Report Type | Choose the report format |

3. Select your options and click **Submit** (or **View Report**).
4. The report opens in a new browser tab, formatted for printing.

### Printing the Report

The report is rendered as a printable HTML page with:

- **Header:** Project name, package name, bill details, contractor info, upazila
- **Body:** Tables organized by scheme → BOQ Part → BOQ Item → Sub-Item, showing:
  - BOQ quantity, rate, total amount
  - Previous bill quantity
  - Current bill quantity and amount
  - Measurement details (dimensions, quantities)
- **Footer:** Amount in words (English), bill totals, signature lines

To print:
1. Press **Ctrl+P** (Windows/Linux) or **Cmd+P** (Mac) in your browser.
2. The page is pre-styled for A4 landscape printing.
3. Click **Print** or **Save as PDF**.

---

## 12. Report Types Explained

FCPMS generates three types of reports:

### Package Summary (`PKG_SUM`)

- Shows an **aggregated summary** across all shelters/schemes in the package
- Groups data by BOQ Part → Item → Sub-Item
- Totals quantities, rates, and amounts across all schemes
- Best for: high-level overview of total work completed and payment due

### Upazila Details (`UPZ_DTL`)

- Shows a **detailed breakdown by upazila** (sub-district)
- Within each upazila, lists schemes and their individual measurements
- Best for: reviewing work progress organized by administrative area

### Scheme Details (`SCH_DTL`)

- Shows the **most detailed view** — every scheme listed individually
- For each scheme: all BOQ parts, items, sub-items, and individual measurements with dimensions
- Best for: verifying specific measurements at each shelter site

### Report Column Definitions

| Column | Meaning |
|--------|---------|
| BOQ Qty | Total quantity in the BOQ for this line |
| Rate | Unit price |
| Total Amount | BOQ Qty × Rate |
| Previous Qty | Quantity already billed in prior bills |
| This Bill Qty | Quantity being billed in the current bill |
| This Bill Amount | This Bill Qty × Rate |
| Nos | Number of units in a measurement |
| L | Length |
| W | Width |
| H | Height |
| Qty | Computed measurement quantity (Nos × L × W × H × Weight) |
| Amount in Words | Total bill amount in English words (Taka format) |

---

## 13. Frequently Asked Questions

### General

**Q: I forgot my password. What should I do?**
A: Contact your system administrator. Passwords are encrypted and cannot be retrieved — they will need to reset it for you.

**Q: Why can't I see certain packages or schemes?**
A: Your access is limited to packages that your administrator has assigned to your account. Contact your administrator if you need access to additional packages.

**Q: I selected the wrong package. How do I switch?**
A: Click the package name in the top bar to open the package selector modal, then choose the correct package (see [Section 9](#9-contractor-user--selecting-a-package)).

### Bill Building

**Q: Why don't I see any BOQ items when adding items to my bill?**
A: Make sure you have:
1. Added BOQ Parts to the bill first
2. Selected the correct Scheme and BOQ Part in the dropdown
3. The BOQ Version assigned to your bill has BOQ Version Details for those parts/items

**Q: Why don't I see certain items for a specific scheme?**
A: This is likely due to the **Pile Type** filter. Items tagged `PC` only appear for schemes with pile type `PC`, and items tagged `CIS` only appear for `CIS` schemes. Items tagged `NA` appear for all schemes.

**Q: Can I edit a measurement after saving it?**
A: Currently you can remove a measurement and add a new one. Direct editing of saved measurements is not available — remove and re-enter.

**Q: What does "Held-Up Quantity" mean?**
A: When your measured quantity exceeds the BOQ quantity, the excess is held back (not paid). See [Section 10.7](#107-how-the-bill-calculates-quantities) for details.

**Q: What is the `calculate_with_heldup` flag?**
A: This flag is set by the administrator on each bill. When enabled, held-up quantities are not deducted — you are paid for all measured work regardless of BOQ limits.

**Q: Why does the "Previous Quantity" show a non-zero value?**
A: This means the same item at the same scheme was billed in a previous bill. The system subtracts previously billed quantities to prevent double-counting.

### Reports

**Q: The report shows no data. What happened?**
A: Check that:
1. You have selected the correct bill
2. The bill has measurements entered
3. The report filters (upazila/scheme) match the data in the bill

**Q: How do I print the report?**
A: Use your browser's print function (Ctrl+P / Cmd+P). The report is pre-formatted for A4 landscape printing.

**Q: What is the difference between "Package Summary" and "Scheme Details"?**
A: Package Summary aggregates all data across all schemes into one summary table. Scheme Details shows individual data for every scheme separately. See [Section 12](#12-report-types-explained) for full details.

### Technical

**Q: The page is loading slowly or not responding.**
A: Try refreshing the page. If the problem persists, check your internet connection and contact your system administrator.

**Q: I see an error page. What should I do?**
A: Note the error message, take a screenshot if possible, and contact your system administrator with the details.

---

## 14. Glossary

| Term | Definition |
|------|------------|
| **Bill** | A contractor's claim for payment for work completed during a specific period |
| **Bill Detail** | A computed summary line in a bill for a specific scheme + BOQ item combination |
| **Bill of Quantities (BOQ)** | A priced schedule defining all work items, quantities, and unit rates |
| **BOQ Part** | A major section of the BOQ (e.g., "Civil Works") |
| **BOQ Item** | A specific work task within a part (e.g., "Foundation Excavation") |
| **BOQ Sub Item** | A further breakdown of an item (e.g., "Type A Foundation") |
| **BOQ Version** | A snapshot of the BOQ with specific quantities and rates for a package |
| **BOQ Version Detail** | An individual line in a BOQ version, defining quantity and rate for a specific part/item/sub-item |
| **Contractor** | A construction company hired to perform work |
| **Contractor User** | A person who logs into FCPMS on behalf of a contractor to build bills |
| **EGP** | Electronic Government Procurement — a procurement method |
| **Held-Up Quantity** | Measured quantity that exceeds the BOQ amount and is withheld from payment |
| **Measurement** | A physical measurement record (dimensions and quantities) for work at a shelter site |
| **NONEGP** | Non-Electronic Government Procurement — an alternative procurement method |
| **Package** | A group of work within a project, tied to a geographic area and budget |
| **Pile Type** | Foundation construction type: `NA` (standard), `PC` (precast), `CIS` |
| **Previous Quantity** | The cumulative quantity of an item that was billed in all prior bills |
| **Project** | A reconstruction program (e.g., "Flood Recovery 2025") |
| **Rate** | The unit price for a BOQ item (cost per unit of measurement) |
| **Region** | A geographic administrative division |
| **Scheme** | An individual shelter or construction site |
| **Scheme Option** | A design variant for shelters (e.g., "Design A", "Design B") |
| **Serial** | A sequential number assigned to each bill for ordering |
| **This Bill Amount** | The amount being claimed in the current bill for a specific item |
| **This Bill Quantity** | The quantity being billed in the current bill (after deducting previous and held-up) |
| **Unit** | A measurement unit (e.g., "Cubic Meter", "Square Meter", "Pieces") |
| **Upazila** | A sub-district administrative unit in Bangladesh |
| **Union** | A local administrative unit below upazila in Bangladesh |

---

*FCPMS 2.0 — User Manual — Version 2.0*
*Copyright 2025 FCPMS. All rights reserved.*
