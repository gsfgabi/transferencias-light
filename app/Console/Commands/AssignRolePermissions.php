<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignRolePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:assign-permissions {--role= : Specific role to assign permissions} {--reset : Reset all role permissions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign permissions to roles using the proper database relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Atribuindo permissões às roles...');
        $this->newLine();

        if ($this->option('reset')) {
            $this->warn('⚠️ Resetando todas as permissões das roles...');
            
            if (!$this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('❌ Operação cancelada.');
                return;
            }

            // Remove all permissions from all roles
            Role::all()->each(function ($role) {
                $role->syncPermissions([]);
            });
            
            $this->info('✅ Permissões das roles resetadas.');
            $this->newLine();
        }

        // Define role permissions mapping
        $rolePermissions = [
            'admin' => 'all', // Gets all permissions
            'support' => [
                'dashboard.view',
                'transfer.view',
                'transfer.history',
                'deposit.view',
                'admin.users.view',
                'admin.transactions.view',
                'admin.reports.view',
            ],
            'common-user' => [
                'dashboard.view',
                'transfer.create',
                'transfer.view',
                'transfer.history',
                'deposit.create',
                'deposit.view',
            ],
            'merchant' => [
                'dashboard.view',
                'transfer.view',
                'transfer.history',
                'deposit.create',
                'deposit.view',
            ],
            'user' => [
                'dashboard.view',
                'transfer.view',
                'deposit.view',
            ]
        ];

        $targetRole = $this->option('role');
        
        if ($targetRole) {
            // Assign permissions to specific role
            $this->assignPermissionsToRole($targetRole, $rolePermissions[$targetRole] ?? []);
        } else {
            // Assign permissions to all roles
            foreach ($rolePermissions as $roleName => $permissions) {
                $this->assignPermissionsToRole($roleName, $permissions);
            }
        }

        $this->newLine();
        $this->info('🎉 Atribuição de permissões concluída!');
        
        // Show summary
        $this->showSummary();
    }

    private function assignPermissionsToRole(string $roleName, $permissions): void
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            $this->error("❌ Role '{$roleName}' não encontrada!");
            return;
        }

        if ($permissions === 'all') {
            // Admin gets all permissions
            $allPermissions = Permission::all();
            $role->syncPermissions($allPermissions);
            $this->line("   ✅ {$roleName}: Todas as permissões (" . $allPermissions->count() . ")");
        } else {
            // Assign specific permissions
            $permissionsToAssign = Permission::whereIn('name', $permissions)->get();
            $role->syncPermissions($permissionsToAssign);
            $this->line("   ✅ {$roleName}: " . $permissionsToAssign->count() . " permissões");
        }
    }

    private function showSummary(): void
    {
        $this->info('📊 Resumo das roles e permissões:');
        
        Role::with('permissions')->get()->each(function ($role) {
            $permissionCount = $role->permissions->count();
            $this->line("   • {$role->name}: {$permissionCount} permissões");
        });
    }
}