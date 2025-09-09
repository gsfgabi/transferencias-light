<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Lista de permissões por role
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        // Agrupar permissões por categoria
        $permissionsByCategory = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('admin.permissions.index', compact('roles', 'permissions', 'permissionsByCategory'));
    }

    /**
     * Atualizar permissões de uma role
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id'
        ]);

        // Buscar as permissões pelos IDs
        $permissions = [];
        if ($request->permissions) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
        }

        // Sincronizar permissões da role
        $role->syncPermissions($permissions);

        return redirect()->route('admin.permissions.index')
            ->with('success', __('messages.success.permission_updated'));
    }

    /**
     * Criar nova permissão
     */
    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'description' => 'nullable|string|max:255'
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissão criada com sucesso!');
    }

    /**
     * Deletar permissão
     */
    public function deletePermission(Permission $permission)
    {
        // Verificar se a permissão está sendo usada por alguma role
        $rolesUsingPermission = $permission->roles()->count();

        if ($rolesUsingPermission > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', "Não é possível deletar a permissão '{$permission->name}' pois está sendo usada por {$rolesUsingPermission} role(s).");
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissão deletada com sucesso!');
    }

    public function updatePermission (Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:255'
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', __('messages.success.permission_updated'));
    }

    /**
     * Criar permissões padrão
     */
    private function createDefaultPermissions()
    {
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Transferências
            'transfer.create',
            'transfer.view',
            'transfer.history',

            // Depósitos
            'deposit.create',
            'deposit.view',

            // Administração
            'admin.users.view',
            'admin.users.create',
            'admin.users.edit',
            'admin.users.delete',
            'admin.transactions.view',
            'admin.transactions.manage',
            'admin.permissions.manage',
            'admin.reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }

    /**
     * Criar roles padrão com permissões
     */
    private function createDefaultRoles()
    {
        $rolesData = [
            'admin' => [
                'name' => 'Administrador',
                'permissions' => [
                    'dashboard.view', 'transfer.create', 'transfer.view', 'transfer.history',
                    'deposit.create', 'deposit.view', 'admin.users.view', 'admin.users.create',
                    'admin.users.edit', 'admin.users.delete', 'admin.transactions.view',
                    'admin.transactions.manage', 'admin.permissions.manage', 'admin.reports.view',
                ]
            ],
            'support' => [
                'name' => 'Suporte',
                'permissions' => [
                    'dashboard.view', 'admin.users.view', 'admin.transactions.view', 'admin.reports.view',
                ]
            ],
            'common-user' => [
                'name' => 'Usuário Comum',
                'permissions' => [
                    'dashboard.view', 'transfer.create', 'transfer.view', 'transfer.history',
                    'deposit.create', 'deposit.view',
                ]
            ],
            'merchant' => [
                'name' => 'Lojista',
                'permissions' => [
                    'dashboard.view', 'deposit.create', 'deposit.view', 'transfer.history',
                ]
            ],
            'user' => [
                'name' => 'Usuário Básico',
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
    }
}
