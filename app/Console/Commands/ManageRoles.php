<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManageRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:manage {action} {--role=} {--user=} {--permission=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listRoles();
                break;
            case 'create':
                $this->createRole();
                break;
            case 'assign':
                $this->assignRole();
                break;
            case 'remove':
                $this->removeRole();
                break;
            case 'permissions':
                $this->showRolePermissions();
                break;
            case 'users':
                $this->showUsersByRole();
                break;
            default:
                $this->error('Ação inválida. Use: list, create, assign, remove, permissions, users');
        }
    }

    private function listRoles()
    {
        $this->info('📋 Roles disponíveis:');
        $this->newLine();

        $roles = Role::with('permissions')->get();
        
        foreach ($roles as $role) {
            $this->line("🔹 <fg=cyan>{$role->name}</>");
            $this->line("   Permissões: " . $role->permissions->pluck('name')->join(', '));
            $this->line("   Usuários: " . $role->users()->count());
            $this->newLine();
        }
    }

    private function createRole()
    {
        $roleName = $this->option('role') ?: $this->ask('Nome da role');
        
        if (Role::where('name', $roleName)->exists()) {
            $this->error("Role '{$roleName}' já existe!");
            return;
        }

        $role = Role::create(['name' => $roleName]);
        $this->info("✅ Role '{$roleName}' criada com sucesso!");
    }

    private function assignRole()
    {
        $roleName = $this->option('role') ?: $this->ask('Nome da role');
        $userEmail = $this->option('user') ?: $this->ask('Email do usuário');

        $role = Role::where('name', $roleName)->first();
        $user = User::where('email', $userEmail)->first();

        if (!$role) {
            $this->error("Role '{$roleName}' não encontrada!");
            return;
        }

        if (!$user) {
            $this->error("Usuário '{$userEmail}' não encontrado!");
            return;
        }

        $user->assignRole($role);
        $this->info("✅ Role '{$roleName}' atribuída ao usuário '{$userEmail}' com sucesso!");
    }

    private function removeRole()
    {
        $roleName = $this->option('role') ?: $this->ask('Nome da role');
        $userEmail = $this->option('user') ?: $this->ask('Email do usuário');

        $role = Role::where('name', $roleName)->first();
        $user = User::where('email', $userEmail)->first();

        if (!$role) {
            $this->error("Role '{$roleName}' não encontrada!");
            return;
        }

        if (!$user) {
            $this->error("Usuário '{$userEmail}' não encontrado!");
            return;
        }

        $user->removeRole($role);
        $this->info("✅ Role '{$roleName}' removida do usuário '{$userEmail}' com sucesso!");
    }

    private function showRolePermissions()
    {
        $roleName = $this->option('role') ?: $this->ask('Nome da role');

        $role = Role::with('permissions')->where('name', $roleName)->first();

        if (!$role) {
            $this->error("Role '{$roleName}' não encontrada!");
            return;
        }

        $this->info("🔑 Permissões da role '{$roleName}':");
        $this->newLine();

        foreach ($role->permissions as $permission) {
            $this->line("  ✅ {$permission->name}");
        }

        $this->newLine();
        $this->info("Total: " . $role->permissions->count() . " permissões");
    }

    private function showUsersByRole()
    {
        $roleName = $this->option('role') ?: $this->ask('Nome da role');

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("Role '{$roleName}' não encontrada!");
            return;
        }

        $users = $role->users;

        $this->info("👥 Usuários com a role '{$roleName}':");
        $this->newLine();

        foreach ($users as $user) {
            $this->line("  👤 {$user->name} ({$user->email})");
        }

        $this->newLine();
        $this->info("Total: " . $users->count() . " usuários");
    }
}