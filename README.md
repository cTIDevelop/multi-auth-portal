# Multi-Auth Portal

A Laravel 11 starter project featuring **dual Filament panels** (Admin + Provider), **multiple authentication guards**, and **Spatie Laravel Permission** for role-based access control, backed by **PostgreSQL**.

---

## 🏗️ Architecture Overview

```
/admin     → Admin Filament Panel  (guard: admin,    model: Admin)
/provider  → Provider Filament Panel (guard: provider, model: Provider)
```

### Guards & Models

| Guard      | Model      | Panel Path  | Description                        |
|------------|------------|-------------|-------------------------------------|
| `admin`    | `Admin`    | `/admin`    | Staff with roles & permissions      |
| `provider` | `Provider` | `/provider` | Business partners with own data     |

### Admin Roles (guard: admin)
| Role               | Permissions                              |
|--------------------|------------------------------------------|
| `super-admin`      | All permissions (unrestricted)           |
| `catalog-manager`  | manage catalog, view reports             |
| `provider-manager` | manage providers, view reports           |

### Provider Roles (guard: provider)
| Role                | Permissions                                         |
|---------------------|-----------------------------------------------------|
| `provider`          | manage own services/products, view reports, edit profile |
| `provider-readonly` | view own reports only                               |

---

## 📋 Requirements

- PHP 8.2+
- Composer
- PostgreSQL 14+
- Node.js 18+ & npm

---

## 🚀 Installation

### 1. Clone & install dependencies

```bash
git clone <repository-url> multi-auth-portal
cd multi-auth-portal
composer install
npm install && npm run build
```

### 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your PostgreSQL credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=multi_auth_portal
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 3. Create the PostgreSQL database

```bash
psql -U postgres -c "CREATE DATABASE multi_auth_portal;"
```

### 4. Run migrations & seed

```bash
php artisan migrate
php artisan db:seed
```

### 5. Install Filament assets

```bash
php artisan filament:install --panels
php artisan storage:link
```

### 6. Serve

```bash
php artisan serve
```

Visit `http://localhost:8000` for the welcome page with panel links.

---

## 🔑 Demo Credentials

All passwords: **`password`**

### Admin Panel (`/admin`)
| Email                        | Role             |
|------------------------------|------------------|
| `superadmin@example.com`     | Super Admin      |
| `catalog@example.com`        | Catalog Manager  |
| `provideradmin@example.com`  | Provider Manager |

### Provider Panel (`/provider`)
| Email                  | Company           | Status           |
|------------------------|-------------------|------------------|
| `provider1@example.com`| TechSolutions MX  | Active, Verified |
| `provider2@example.com`| Creativa Digital  | Active, Verified |
| `provider3@example.com`| Capacita Pro      | Active, Unverified|
| `provider4@example.com`| SecureNet Consulting | **Inactive** (cannot log in) |

---

## 📁 Project Structure

```
app/
├── Filament/
│   ├── Admin/
│   │   ├── Pages/
│   │   │   └── Dashboard.php
│   │   ├── Resources/
│   │   │   ├── AdminResource/       # Manage admin users
│   │   │   ├── RoleResource/        # Manage roles & permissions
│   │   │   ├── ProviderResource/    # Manage providers (verify, activate)
│   │   │   ├── CategoryResource/    # Catalog categories (reorderable)
│   │   │   ├── ServiceResource/     # All providers' services
│   │   │   └── ProductResource/     # All providers' products
│   │   └── Widgets/
│   │       └── StatsOverview.php    # Dashboard KPI cards
│   └── Provider/
│       ├── Pages/
│       │   └── EditProfile.php      # Provider edits own company profile
│       ├── Resources/
│       │   ├── ServiceResource/     # Own services only (scoped query)
│       │   └── ProductResource/     # Own products only (scoped query)
│       └── Widgets/
│           └── ProviderStatsOverview.php
├── Models/
│   ├── Admin.php       # Implements FilamentUser, HasRoles (guard: admin)
│   ├── Provider.php    # Implements FilamentUser, HasRoles (guard: provider)
│   ├── Category.php    # Hierarchical categories (parent/children)
│   ├── Service.php     # Services offered by providers
│   └── Product.php     # Products sold by providers
└── Providers/
    └── Filament/
        ├── AdminPanelProvider.php    # /admin panel config
        └── ProviderPanelProvider.php # /provider panel config

config/
├── auth.php       # 3 guards: web, admin, provider
└── permission.php # Spatie config

database/
├── migrations/
│   ├── ..._create_admins_table.php
│   ├── ..._create_providers_table.php
│   ├── ..._create_categories_table.php
│   ├── ..._create_services_table.php
│   └── ..._create_products_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── PermissionSeeder.php  # All roles & permissions for both guards
    ├── AdminSeeder.php       # 3 admin users with different roles
    ├── CategorySeeder.php    # 6 parent categories + 24 subcategories
    └── ProviderSeeder.php    # 4 providers with services & products
```

---

## 🛡️ Authorization Strategy

### Admin Panel
- **Super Admins** (`is_super_admin = true`): bypass all permission checks.
- **Other admins**: access controlled via `canAccess()` on each Resource, checking `hasPermissionTo('manage X')`.

### Provider Panel
- `canAccessPanel()` on the `Provider` model checks `is_active = true`.
- Resources scope queries with `getEloquentQuery()` to `WHERE provider_id = auth('provider')->id()`, preventing cross-provider data access.
- Edit pages call `authorizeAccess()` to double-check ownership (defense in depth).

### Key Code Patterns

```php
// Admin Resource — restrict by permission
public static function canAccess(): bool
{
    $admin = auth('admin')->user();
    return $admin && ($admin->isSuperAdmin() || $admin->hasPermissionTo('manage catalog'));
}

// Provider Resource — scope to own data
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('provider_id', auth('provider')->id());
}

// Provider — only active providers can log in
public function canAccessPanel(Panel $panel): bool
{
    return $panel->getId() === 'provider' && $this->is_active;
}
```

---

## ➕ Adding New Resources

### Admin Resource
```bash
php artisan make:filament-resource MyModel --panel=admin
```

### Provider Resource (scoped)
```bash
php artisan make:filament-resource MyModel --panel=provider
```
Then add `getEloquentQuery()` to scope by `provider_id`.

---

## 🔧 Adding Permissions

In `PermissionSeeder` or a new seeder:

```php
// Create permission for a guard
Permission::create(['name' => 'export reports', 'guard_name' => 'admin']);

// Assign to role
Role::findByName('catalog-manager', 'admin')->givePermissionTo('export reports');
```

Check in a Resource:
```php
$admin->hasPermissionTo('export reports') // uses admin guard automatically
```

---

## 🗂️ Sample Data

After seeding you'll have:
- **6 parent categories** (Technology, Marketing, Design, Consulting, Education, Products)
- **24 subcategories** across those parents
- **4 providers** with varied statuses (active, verified, inactive)
- **~13 services** across multiple categories
- **~8 products** with stock levels (including out-of-stock examples)

---

## 📦 Key Packages

| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | ^11.0 | Core framework |
| `filament/filament` | ^3.2 | Admin & Provider panels |
| `spatie/laravel-permission` | ^6.9 | Roles & permissions (multi-guard) |

---

## 🐘 PostgreSQL Notes

Spatie permissions use a polymorphic `model_id` column. For PostgreSQL with multiple model types, ensure your `model_has_roles` and `model_has_permissions` tables use `bigint` for `model_id` (default). No extra config needed.

If you hit a `column "model_id" ... integer` type mismatch, publish and run Spatie's migrations fresh:
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate:fresh --seed
```
