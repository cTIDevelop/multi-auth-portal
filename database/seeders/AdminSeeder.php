<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = Admin::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name'           => 'Super Admin',
                'password'       => Hash::make('password'),
                'is_super_admin' => true,
            ]
        );

        // Catalog Manager Admin
        $catalogAdmin = Admin::firstOrCreate(
            ['email' => 'catalog@example.com'],
            [
                'name'           => 'Catalog Manager',
                'password'       => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );
        $catalogAdmin->assignRole('catalog-manager');

        // Provider Manager Admin
        $providerAdmin = Admin::firstOrCreate(
            ['email' => 'provideradmin@example.com'],
            [
                'name'           => 'Provider Manager',
                'password'       => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );
        $providerAdmin->assignRole('provider-manager');

        $this->command->info('✅ Admins seeded:');
        $this->command->line('   superadmin@example.com  / password  (Super Admin)');
        $this->command->line('   catalog@example.com     / password  (Catalog Manager)');
        $this->command->line('   provideradmin@example.com / password (Provider Manager)');
    }
}
