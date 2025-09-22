<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            // Transfer permissions
            'transfer.create',
            'transfer.view',
            'transfer.history',

            // Deposit permissions
            'deposit.create',
            'deposit.view',

            // Dashboard permissions
            'dashboard.view',

            // Admin permissions
            'admin.users.view',
            'admin.users.create',
            'admin.users.edit',
            'admin.users.delete',
            'admin.transactions.view',
            'admin.transactions.manage',
            'admin.permissions.view',
            'admin.permissions.manage',
            'admin.reports.view',
        ];

        $createdCount = 0;
        $existingCount = 0;

        foreach ($permissions as $permission) {
            $permissionModel = Permission::firstOrCreate(
                ['name' => $permission],
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ]
            );

        }
    }
}
