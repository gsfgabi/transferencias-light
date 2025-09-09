@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                        ← Voltar
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.titles.permissions') }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="openCreatePermissionModal()" class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nova Permissão
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alertas -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Cards de Permissões por Role -->
        <div class="mb-8">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Permissões por Role</h3>
                <p class="text-sm text-gray-500">Gerencie as permissões de cada role do sistema</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($roles as $role)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <!-- Header do Card -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3
                                    {{ $role->name === 'admin' ? 'bg-red-500' :
                                       ($role->name === 'common-user' ? 'bg-blue-500' :
                                       ($role->name === 'merchant' ? 'bg-purple-500' :
                                       ($role->name === 'support' ? 'bg-green-500' : 'bg-gray-500'))) }}"></div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ __('messages.roles.' . str_replace('-', '_', $role->name)) }}</h4>
                            </div>
                        </div>

                        <!-- Lista de Permissões -->
                        <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
                            @foreach($permissions as $permission)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center flex-1">
                                        <label class="flex items-center cursor-pointer flex-1">
                                            <input type="checkbox"
                                                   class="permission-checkbox-{{ $role->id }} hidden"
                                                   value="{{ $permission->id }}"
                                                   {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                                                   disabled>
                                            <div class="w-5 h-5 border-2 rounded mr-3 flex items-center justify-center
                                                {{ $role->hasPermissionTo($permission) ? 'bg-green-500 border-green-500' : 'border-gray-300' }}">
                                                @if($role->hasPermissionTo($permission))
                                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-700 flex-1">{{ __('messages.permissions.' . str_replace('.', '_', $permission->name)) }}</span>
                                        </label>
                                    </div>
                                    <button onclick="removePermissionFromRole('{{ $role->id }}', '{{ $permission->id }}', '{{ $permission->name }}')"
                                            class="text-red-400 hover:text-red-600 ml-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer do Card -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $role->permissions->count() }} permissões ativas</span>
                                <button onclick="openEditRoleModal('{{ $role->id }}', '{{ $role->name }}', {{ $role->permissions->pluck('id')->toJson() }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Editar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<!-- Modal para Editar Role -->
<div id="editRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="editRoleTitle">Editar Permissões</h3>
                <button onclick="closeEditRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    @foreach($permissionsByCategory as $category => $permissions)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ ucfirst($category) }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               class="permission-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ __('messages.permissions.' . str_replace('.', '_', $permission->name)) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditRoleModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Criar Permissão -->
<div id="createPermissionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Criar Nova Permissão</h3>
                <button onclick="closeCreatePermissionModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.permissions.create') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="permission_name" class="block text-sm font-medium text-gray-700 mb-2">Nome da Permissão</label>
                        <input type="text"
                               id="permission_name"
                               name="name"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="ex: admin.users.create">
                    </div>

                    <div>
                        <label for="permission_description" class="block text-sm font-medium text-gray-700 mb-2">Descrição (opcional)</label>
                        <textarea id="permission_description"
                                  name="description"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Descrição da permissão..."></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeCreatePermissionModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Criar Permissão
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditRoleModal(roleId, roleName, currentPermissions) {
    console.log('Opening modal for role:', roleId, roleName);
    console.log('Current permissions:', currentPermissions);
    
    document.getElementById('editRoleTitle').textContent = `Editar Permissões - ${roleName}`;
    document.getElementById('editRoleForm').action = `/admin/permissions/role/${roleId}`;

    // Limpar checkboxes
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });

    // Marcar permissões atuais
    currentPermissions.forEach(permissionId => {
        const checkbox = document.querySelector(`input[name="permissions[]"][value="${permissionId}"]`);
        if (checkbox) {
            checkbox.checked = true;
            console.log('Checked permission:', permissionId);
        } else {
            console.log('Checkbox not found for permission:', permissionId);
        }
    });

    document.getElementById('editRoleModal').classList.remove('hidden');
}

function closeEditRoleModal() {
    document.getElementById('editRoleModal').classList.add('hidden');
}

function openCreatePermissionModal() {
    document.getElementById('createPermissionModal').classList.remove('hidden');
}

function closeCreatePermissionModal() {
    document.getElementById('createPermissionModal').classList.add('hidden');
}

function deletePermission(permissionId, permissionName) {
    if (confirm(`Tem certeza que deseja deletar a permissão "${permissionName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/permissions/${permissionId}`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
