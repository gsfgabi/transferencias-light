@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Administrativo -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-gray-900">Painel Administrativo</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Administrador</p>
                        <p class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Boas-vindas -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">Bem-vindo {{ Auth::user()->name }}</h2>
            <p class="text-gray-600">Gerencie usuários, transações e relatórios do sistema</p>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total de Usuários -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total de Usuários</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Transações Completadas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Transações Completadas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_transactions'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Volume Total -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Volume Total</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($stats['total_volume'], 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Transações Pendentes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">⏳</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pendentes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_transactions'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Ações Administrativas -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card de Usuários -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Gerenciar Usuários</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-blue-600">👥</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Listar Usuários</p>
                                    <p class="text-xs text-gray-500">{{ $stats['total_users'] }} usuários</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('admin.reports') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-green-600">📊</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Relatórios</p>
                                    <p class="text-xs text-gray-500">Análises e dados</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-purple-600">🔐</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Permissões</p>
                                    <p class="text-xs text-gray-500">Gerenciar roles e permissões</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Card de Operações Financeiras (Somente Visualização) -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Operações Financeiras</h3>
                        <span class="text-xs text-gray-500 bg-yellow-100 px-2 py-1 rounded-full">Somente Visualização</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('deposit.form') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-green-600">💰</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Depósito</p>
                                    <p class="text-xs text-gray-500">Visualizar formulário</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('transfer.form') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-blue-600">💸</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Transferência</p>
                                    <p class="text-xs text-gray-500">Visualizar formulário</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Transações Recentes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Transações Recentes</h3>
                    </div>
                    <div class="p-6">
                        @if($recentTransactions->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentTransactions as $transaction)
                                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                                <span class="text-sm">{{ $transaction->status === 'completed' ? '✅' : '⏳' }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $transaction->sender->name }} → {{ $transaction->payee->name }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-gray-900">
                                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                            </p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl">📭</span>
                                </div>
                                <p class="text-gray-500 text-sm">Nenhuma transação encontrada</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estatísticas por Tipo de Usuário -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Usuários por Tipo</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">Usuários Comuns</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $stats['common_users'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">Lojistas</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $stats['merchant_users'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">Administradores</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $stats['admin_users'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">Suporte</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $stats['support_users'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
