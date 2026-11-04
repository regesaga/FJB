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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Daftar Permission
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // Dashboard
            'dashboard.view',

            // Kategori
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // Subkategori
            'sub_categories.view',
            'sub_categories.create',
            'sub_categories.update',
            'sub_categories.delete',

            // Atribut dinamis
            'attributes.view',
            'attributes.create',
            'attributes.update',
            'attributes.delete',

            // Pilihan atribut
            'attribute_options.view',
            'attribute_options.create',
            'attribute_options.update',
            'attribute_options.delete',

            // Listing / iklan
            'listings.view',
            'listings.update',
            'listings.approve',
            'listings.reject',
            'listings.delete',

            // Pengguna
            'users.view',
            'users.update',
            'users.suspend',

            // Verifikasi identitas
            'identity_verifications.view',
            'identity_verifications.review',

            // Laporan
            'reports.view',
            'reports.update',
            'reports.resolve',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Permission Admin
        |--------------------------------------------------------------------------
        */

        $adminPermissions = [
            'dashboard.view',

            // Kategori
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // Subkategori
            'sub_categories.view',
            'sub_categories.create',
            'sub_categories.update',
            'sub_categories.delete',

            // Atribut dinamis
            'attributes.view',
            'attributes.create',
            'attributes.update',
            'attributes.delete',

            // Pilihan atribut
            'attribute_options.view',
            'attribute_options.create',
            'attribute_options.update',
            'attribute_options.delete',

            // Listing
            'listings.view',
            'listings.update',
            'listings.approve',
            'listings.reject',
            'listings.delete',

            // Pengguna
            'users.view',
            'users.update',
            'users.suspend',

            // Verifikasi identitas
            'identity_verifications.view',
            'identity_verifications.review',

            // Laporan
            'reports.view',
            'reports.update',
            'reports.resolve',
        ];

        /*
        |--------------------------------------------------------------------------
        | Permission Moderator
        |--------------------------------------------------------------------------
        */

        $moderatorPermissions = [
            'dashboard.view',

            // Listing
            'listings.view',
            'listings.update',
            'listings.approve',
            'listings.reject',

            // Laporan
            'reports.view',
            'reports.update',
            'reports.resolve',
        ];

        /*
        |--------------------------------------------------------------------------
        | Pembuatan Role
        |--------------------------------------------------------------------------
        */

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $moderator = Role::firstOrCreate([
            'name' => 'Moderator',
            'guard_name' => 'web',
        ]);

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Sinkronisasi Permission
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions($adminPermissions);

        $moderator->syncPermissions($moderatorPermissions);

        // Super Admin mendapatkan akses penuh melalui Gate::before.
        $superAdmin->syncPermissions([]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}