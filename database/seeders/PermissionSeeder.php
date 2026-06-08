<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── ADMIN GUARD PERMISSIONS ───────────────────────────────────────
        $adminPermissions = [
            'manage admins',
            'manage roles',
            'manage providers',
            'manage catalog',
            'view reports',
            'manage settings',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Super Admin role (all permissions)
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);
        $superAdminRole->syncPermissions(
            Permission::where('guard_name', 'admin')->get()
        );

        // Catalog Manager role
        $catalogManagerRole = Role::firstOrCreate(['name' => 'catalog-manager', 'guard_name' => 'admin']);
        $catalogManagerRole->syncPermissions([
            Permission::where('name', 'manage catalog')->where('guard_name', 'admin')->first(),
            Permission::where('name', 'view reports')->where('guard_name', 'admin')->first(),
        ]);

        // Provider Manager role
        $providerManagerRole = Role::firstOrCreate(['name' => 'provider-manager', 'guard_name' => 'admin']);
        $providerManagerRole->syncPermissions([
            Permission::where('name', 'manage providers')->where('guard_name', 'admin')->first(),
            Permission::where('name', 'view reports')->where('guard_name', 'admin')->first(),
        ]);

        // ─── PROVIDER GUARD PERMISSIONS ───────────────────────────────────
        $providerPermissions = [
            'manage own services',
            'manage own products',
            'view own reports',
            'edit own profile',
        ];

        foreach ($providerPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'provider']);
        }

        // Standard provider role
        $providerRole = Role::firstOrCreate(['name' => 'provider', 'guard_name' => 'provider']);
        $providerRole->syncPermissions(
            Permission::where('guard_name', 'provider')->get()
        );

        // Read-only provider role (e.g., for suspended providers)
        $readOnlyRole = Role::firstOrCreate(['name' => 'provider-readonly', 'guard_name' => 'provider']);
        $readOnlyRole->syncPermissions([
            Permission::where('name', 'view own reports')->where('guard_name', 'provider')->first(),
        ]);

        $this->command->info('✅ Permissions and roles seeded successfully.');
    }
}
