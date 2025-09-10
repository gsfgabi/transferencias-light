<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
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

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $commonUserRole = Role::firstOrCreate(['name' => 'common-user']);
        $merchantRole = Role::firstOrCreate(['name' => 'merchant']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $supportRole = Role::firstOrCreate(['name' => 'support']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign permissions to common users
        $commonUserRole->givePermissionTo([
            'transfer.create',
            'transfer.view',
            'transfer.history',
            'deposit.create',
            'deposit.view',
            'dashboard.view',
        ]);

        // Assign permissions to merchants
        $merchantRole->givePermissionTo([
            'transfer.history',
            'deposit.create',
            'deposit.view',
            'dashboard.view',
        ]);

        // Assign permissions to support
        $supportRole->givePermissionTo([
            'dashboard.view',
            'admin.users.view',
            'admin.transactions.view',
            'admin.reports.view',
        ]);

        // Assign basic permissions to user
        $userRole->givePermissionTo([
            'dashboard.view',
            'transfer.view',
            'deposit.view',
        ]);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());
    }
}