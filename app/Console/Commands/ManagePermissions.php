<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class ManagePermissions extends Command
{
    protected $signature = 'permissions:manage 
                            {action : Ação a ser executada (list-roles|list-permissions|assign-role|remove-role|create-permission|delete-permission|reset)}
                            {--role= : Nome da role}
                            {--permission= : Nome da permissão}
                            {--user= : Email do usuário}
                            {--name= : Nome da permissão/role}';

    protected $description = 'Gerenciar roles e permissões do sistema';

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list-roles':
                $this->listRoles();
                break;
            case 'list-permissions':
                $this->listPermissions();
                break;
            case 'assign-role':
                $this->assignRole();
                break;
            case 'remove-role':
                $this->removeRole();
                break;
            case 'create-permission':
                $this->createPermission();
                break;
            case 'delete-permission':
                $this->deletePermission();
                break;
            case 'reset':
                $this->resetPermissions();
                break;
            default:
                $this->error('Ação inválida. Use: list-roles, list-permissions, assign-role, remove-role, create-permission, delete-permission, reset');
                return 1;
        }

        return 0;
    }

    private function listRoles()
    {
        $this->info('📋 Roles Disponíveis:');
        $this->line('');

        $roles = Role::with('permissions')->get();
        
        foreach ($roles as $role) {
            $this->line("🔹 <fg=cyan>{$role->name}</> - {$role->permissions->count()} permissões");
            
            if ($role->permissions->count() > 0) {
                foreach ($role->permissions as $permission) {
                    $this->line("   • {$permission->name}");
                }
            }
            $this->line('');
        }

        $this->info("Total: {$roles->count()} roles");
    }

    private function listPermissions()
    {
        $this->info('🔑 Permissões Disponíveis:');
        $this->line('');

        $permissions = Permission::with('roles')->get();
        
        foreach ($permissions as $permission) {
            $rolesCount = $permission->roles->count();
            $this->line("🔹 <fg=green>{$permission->name}</> - {$rolesCount} role(s)");
        }

        $this->line('');
        $this->info("Total: {$permissions->count()} permissões");
    }

    private function assignRole()
    {
        $roleName = $this->option('role');
        $userEmail = $this->option('user');

        if (!$roleName || !$userEmail) {
            $this->error('Use --role=NOME_DA_ROLE --user=EMAIL_DO_USUARIO');
            return;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' não encontrada!");
            return;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("Usuário '{$userEmail}' não encontrado!");
            return;
        }

        if ($user->hasRole($roleName)) {
            $this->warn("Usuário '{$userEmail}' já possui a role '{$roleName}'");
            return;
        }

        $user->assignRole($role);
        $this->info("✅ Role '{$roleName}' atribuída ao usuário '{$userEmail}' com sucesso!");
    }

    private function removeRole()
    {
        $roleName = $this->option('role');
        $userEmail = $this->option('user');

        if (!$roleName || !$userEmail) {
            $this->error('Use --role=NOME_DA_ROLE --user=EMAIL_DO_USUARIO');
            return;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("Usuário '{$userEmail}' não encontrado!");
            return;
        }

        if (!$user->hasRole($roleName)) {
            $this->warn("Usuário '{$userEmail}' não possui a role '{$roleName}'");
            return;
        }

        $user->removeRole($roleName);
        $this->info("✅ Role '{$roleName}' removida do usuário '{$userEmail}' com sucesso!");
    }

    private function createPermission()
    {
        $permissionName = $this->option('permission') ?: $this->option('name');

        if (!$permissionName) {
            $this->error('Use --permission=NOME_DA_PERMISSAO ou --name=NOME_DA_PERMISSAO');
            return;
        }

        if (Permission::where('name', $permissionName)->exists()) {
            $this->error("Permissão '{$permissionName}' já existe!");
            return;
        }

        Permission::create([
            'name' => $permissionName,
            'guard_name' => 'web'
        ]);

        $this->info("✅ Permissão '{$permissionName}' criada com sucesso!");
    }

    private function deletePermission()
    {
        $permissionName = $this->option('permission') ?: $this->option('name');

        if (!$permissionName) {
            $this->error('Use --permission=NOME_DA_PERMISSAO ou --name=NOME_DA_PERMISSAO');
            return;
        }

        $permission = Permission::where('name', $permissionName)->first();
        if (!$permission) {
            $this->error("Permissão '{$permissionName}' não encontrada!");
            return;
        }

        $rolesCount = $permission->roles->count();
        if ($rolesCount > 0) {
            $this->error("Não é possível deletar a permissão '{$permissionName}' pois está sendo usada por {$rolesCount} role(s)!");
            return;
        }

        $permission->delete();
        $this->info("✅ Permissão '{$permissionName}' deletada com sucesso!");
    }

    private function resetPermissions()
    {
        if (!$this->confirm('Tem certeza que deseja resetar todas as permissões? Esta ação não pode ser desfeita.')) {
            $this->info('Operação cancelada.');
            return;
        }

        $this->info('🔄 Resetando permissões...');

        // Limpar todas as permissões existentes
        \DB::table('role_has_permissions')->delete();
        \DB::table('model_has_roles')->delete();
        \DB::table('permissions')->delete();
        \DB::table('roles')->delete();

        // Recriar permissões padrão
        $this->createDefaultPermissions();
        $this->createDefaultRoles();

        $this->info('✅ Permissões resetadas com sucesso!');
    }

    private function createDefaultPermissions()
    {
        $permissions = [
            'dashboard.view',
            'transfer.create', 'transfer.view', 'transfer.history',
            'deposit.create', 'deposit.view',
            'admin.users.view', 'admin.users.create', 'admin.users.edit', 'admin.users.delete',
            'admin.transactions.view', 'admin.transactions.manage',
            'admin.permissions.manage', 'admin.reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->line("   • {$permissions->count()} permissões criadas");
    }

    private function createDefaultRoles()
    {
        $rolesData = [
            'admin' => [
                'permissions' => [
                    'dashboard.view', 'transfer.create', 'transfer.view', 'transfer.history',
                    'deposit.create', 'deposit.view', 'admin.users.view', 'admin.users.create',
                    'admin.users.edit', 'admin.users.delete', 'admin.transactions.view', 
                    'admin.transactions.manage', 'admin.permissions.manage', 'admin.reports.view',
                ]
            ],
            'support' => [
                'permissions' => [
                    'dashboard.view', 'admin.users.view', 'admin.transactions.view', 'admin.reports.view',
                ]
            ],
            'common-user' => [
                'permissions' => [
                    'dashboard.view', 'transfer.create', 'transfer.view', 'transfer.history',
                    'deposit.create', 'deposit.view',
                ]
            ],
            'merchant' => [
                'permissions' => [
                    'dashboard.view', 'deposit.create', 'deposit.view', 'transfer.history',
                ]
            ],
            'user' => [
                'permissions' => [
                    'dashboard.view',
                ]
            ]
        ];

        foreach ($rolesData as $roleKey => $roleData) {
            $role = Role::create([
                'name' => $roleKey,
                'guard_name' => 'web'
            ]);

            $permissions = Permission::whereIn('name', $roleData['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        $this->line("   • " . count($rolesData) . " roles criadas");
    }
}