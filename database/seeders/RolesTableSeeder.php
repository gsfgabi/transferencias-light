<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define roles with their descriptions
        $roles = [
            'admin' => [
                'name' => 'Administrador',
                'description' => 'Administrador do sistema com acesso a todas as funcionalidades',
                'permissions' => [
                    'dashboard.view',
                    'transfer.create',
                    'transfer.view',
                    'transfer.history',
                    'deposit.create',
                    'deposit.view',
                    'admin.users.view',
                    'admin.users.create',
                    'admin.users.edit',
                    'admin.users.delete',
                    'admin.transactions.view',
                    'admin.transactions.manage',
                    'admin.permissions.manage',
                    'admin.reports.view',
                    'admin.permissions.view',
                ]
            ],
            'support' => [
                'name' => 'Suporte',
                'description' => 'Equipe de suporte com acesso a visualização de dados',
                'permissions' => [
                    'dashboard.view',
                    'transfer.view',
                    'transfer.history',
                    'deposit.view',
                    'admin.users.view',
                    'admin.transactions.view',
                ]
            ],
            'common-user' => [
                'name' => 'Usuário Comum',
                'description' => 'Usuário comum que pode enviar e receber transferências',
                'permissions' => [
                    'dashboard.view',
                    'transfer.create',
                    'transfer.view',
                    'transfer.history',
                    'deposit.create',
                    'deposit.view',
                ]
            ],
            'merchant' => [
                'name' => 'Lojista',
                'description' => 'Lojista que pode apenas receber transferências',
                'permissions' => [
                    'dashboard.view',
                    'transfer.view',
                    'transfer.history',
                    'deposit.create',
                    'deposit.view',
                ]
            ],
            'user' => [
                'name' => 'Usuário Básico',
                'description' => 'Usuário com acesso básico ao sistema',
                'permissions' => [
                    'dashboard.view',
                    'transfer.view',
                    'deposit.view',
                ]
            ]
        ];

        // Create roles
        foreach ($roles as $roleKey => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleKey],
                [
                    'name' => $roleKey,
                    'guard_name' => 'web'
                ]
            );

            // Add custom attributes if needed (you can extend the roles table)
            // $role->update([
            //     'display_name' => $roleData['name'],
            //     'description' => $roleData['description']
            // ]);

            // Assign permissions
            if ($roleData['permissions'] === 'all') {
                // Super admin gets all permissions
                $role->givePermissionTo(\Spatie\Permission\Models\Permission::all());
            } else {
                // Assign specific permissions
                foreach ($roleData['permissions'] as $permission) {
                    $permissionModel = Permission::firstOrCreate(['name' => $permission]);
                    $role->givePermissionTo($permissionModel);
                }
            }

            $this->command->info("✅ Role '{$roleData['name']}' criada/atualizada com sucesso");
        }

        $this->command->info("\n🎉 Todas as roles foram criadas/atualizadas com sucesso!");
        $this->command->info("📊 Total de roles: " . Role::count());
        $this->command->info("🔑 Total de permissões: " . Permission::count());
    }
}