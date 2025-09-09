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
                    <h1 class="text-2xl font-bold text-gray-900">Relatórios</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
            <form method="GET" action="{{ route('admin.reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ $filters['start_date'] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ $filters['end_date'] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="transaction_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Transação</label>
                    <select id="transaction_type" 
                            name="transaction_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        <option value="completed" {{ ($filters['transaction_type'] ?? '') === 'completed' ? 'selected' : '' }}>Completadas</option>
                        <option value="failed" {{ ($filters['transaction_type'] ?? '') === 'failed' ? 'selected' : '' }}>Falhadas</option>
                        <option value="pending" {{ ($filters['transaction_type'] ?? '') === 'pending' ? 'selected' : '' }}>Pendentes</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        Gerar Relatório
                    </button>
                    <a href="{{ route('admin.reports') }}" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors text-sm font-medium text-center">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Informações dos Filtros Aplicados -->
        @if($filters['start_date'] || $filters['end_date'] || $filters['transaction_type'])
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-semibold text-blue-800 mb-2">Filtros Aplicados:</h4>
            <div class="flex flex-wrap gap-2">
                @if($filters['start_date'])
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        📅 De: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }}
                    </span>
                @endif
                @if($filters['end_date'])
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        📅 Até: {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
                    </span>
                @endif
                @if($filters['transaction_type'])
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        🔄 Status: {{ ucfirst($filters['transaction_type']) }}
                    </span>
                @endif
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Transações por Mês -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Transações por Mês</h3>
                </div>
                <div class="p-6">
                    @if($transactionsByMonth->count() > 0)
                        <div class="space-y-4">
                            @foreach($transactionsByMonth as $month)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('M/Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $month->count }} transações</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">R$ {{ number_format($month->total, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl">📊</span>
                            </div>
                            <p class="text-gray-500 text-sm">Nenhum dado disponível</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usuários por Tipo -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Usuários por Tipo</h3>
                </div>
                <div class="p-6">
                    @if($usersByRole->count() > 0)
                        <div class="space-y-4">
                            @foreach($usersByRole as $role)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3
                                            {{ $role->name === 'admin' ? 'bg-red-500' :
                                               ($role->name === 'common-user' ? 'bg-blue-500' :
                                               ($role->name === 'merchant' ? 'bg-purple-500' :
                                               ($role->name === 'support' ? 'bg-green-500' : 'bg-gray-500'))) }}"></div>
                                        <span class="text-sm font-medium text-gray-900">{{ __('messages.roles.' . str_replace('-', '_', $role->name)) }}</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $role->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl">👥</span>
                            </div>
                            <p class="text-gray-500 text-sm">Nenhum dado disponível</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Usuários -->
        <div class="mt-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Top Usuários por Volume</h3>
                </div>
                <div class="p-6">
                    @if($topUsers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enviado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recebido</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($topUsers as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                        <span class="text-sm font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                R$ {{ number_format($user->sent_amount ?? 0, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                R$ {{ number_format($user->received_amount ?? 0, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                R$ {{ number_format(($user->sent_amount ?? 0) + ($user->received_amount ?? 0), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl">🏆</span>
                            </div>
                            <p class="text-gray-500 text-sm">Nenhum dado disponível</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const form = document.querySelector('form');

    // Validação de datas
    function validateDates() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate && startDate > endDate) {
            alert('A data inicial não pode ser maior que a data final.');
            return false;
        }
        return true;
    }

    // Adicionar validação ao submit do formulário
    form.addEventListener('submit', function(e) {
        if (!validateDates()) {
            e.preventDefault();
        }
    });

    // Validação em tempo real
    startDateInput.addEventListener('change', function() {
        if (endDateInput.value && this.value > endDateInput.value) {
            endDateInput.value = this.value;
        }
    });

    endDateInput.addEventListener('change', function() {
        if (startDateInput.value && this.value < startDateInput.value) {
            startDateInput.value = this.value;
        }
    });
});
</script>
@endsection
