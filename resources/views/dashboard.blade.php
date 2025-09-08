@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Minimalista -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-gray-900">Transferências Light</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Saldo</p>
                        <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($user->balance, 2, ',', '.') }}</p>
                    </div>
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Boas-vindas -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">Olá, {{ $user->name }}!</h2>
            <p class="text-gray-600">Gerencie suas transferências de forma simples e rápida</p>
        </div>

        <!-- Grid Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Ações Principais -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card de Depósito -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <span class="text-2xl">💰</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Depósito</h3>
                                <p class="text-sm text-gray-500">Adicione dinheiro à sua conta</p>
                            </div>
                        </div>
                        <a href="{{ route('deposit.form') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            Depositar
                        </a>
                    </div>
                </div>

                <!-- Card de Transferência -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <span class="text-2xl">💸</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Transferência</h3>
                                <p class="text-sm text-gray-500">Envie dinheiro para outros usuários</p>
                            </div>
                        </div>
                        @if($user->type === 'common')
                            <a href="{{ route('transfer.form') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                Transferir
                            </a>
                        @else
                            <span class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                                Apenas Recebe
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Transações Recentes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Transações Recentes</h3>
                    </div>
                    <div class="p-6">
                        @if($allTransactions->count() > 0)
                            <div class="space-y-4">
                                @foreach($allTransactions as $transaction)
                                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 {{ $transaction->sender_id === $user->id ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                                <span class="text-sm">{{ $transaction->sender_id === $user->id ? '📤' : '📥' }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $transaction->sender_id === $user->id ? 'Enviado para' : 'Recebido de' }}
                                                    {{ $transaction->sender_id === $user->id ? $transaction->payee->name : $transaction->sender->name }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold {{ $transaction->sender_id === $user->id ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $transaction->sender_id === $user->id ? '-' : '+' }}R$ {{ number_format($transaction->amount, 2, ',', '.') }}
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
            <div class="lg:col-span-2 space-y-6">
                <!-- Informações do Usuário -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ open: false }">
                    <!-- Header clicável -->
                    <button @click="open = !open" class="w-full p-6 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-lg">👤</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Informações da Conta</h3>
                                <p class="text-sm text-gray-500">Clique para expandir</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Conteúdo expansível -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="border-t border-gray-200">
                        <div class="p-6">
                            <!-- Informações Básicas -->
                            <div class="space-y-4 mb-6">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Nome Completo</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Email</p>
                                    <p class="text-base font-semibold text-gray-900 break-all">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-2">Tipo de Conta</p>
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ $user->type === 'common' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-purple-100 text-purple-800 border border-purple-200' }}">
                                        <span class="w-2 h-2 rounded-full mr-2 {{ $user->type === 'common' ? 'bg-blue-500' : 'bg-purple-500' }}"></span>
                                        {{ $user->type === 'common' ? 'Usuário Comum' : 'Lojista' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Saldo -->
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Saldo Disponível</p>
                                        <p class="text-3xl font-bold text-green-600">R$ {{ number_format($user->balance, 2, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-600 text-xl">💰</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-green-200">
                                    <p class="text-xs text-gray-500">Última atualização: {{ now()->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ open: false }">
                    <!-- Header clicável -->
                    <button @click="open = !open" class="w-full p-6 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-lg">📊</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Estatísticas</h3>
                                <p class="text-sm text-gray-500">Clique para expandir</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Conteúdo expansível -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="border-t border-gray-200">
                        <div class="p-6 space-y-4">
                            <!-- Total -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-blue-600 text-sm">📊</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Total de Transações</p>
                                        <p class="text-xs text-gray-500">Todas as movimentações</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_transactions'] }}</p>
                                </div>
                            </div>

                            <!-- Enviadas -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-green-600 text-sm">📤</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Transações Enviadas</p>
                                        <p class="text-xs text-gray-500">Dinheiro que você enviou</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600">{{ $stats['sent_transactions'] }}</p>
                                </div>
                            </div>

                            <!-- Recebidas -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-purple-600 text-sm">📥</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Transações Recebidas</p>
                                        <p class="text-xs text-gray-500">Dinheiro que você recebeu</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-purple-600">{{ $stats['received_transactions'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Ajuda -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-blue-600">💡</span>
                        </div>
    <div>
                            <h3 class="text-sm font-semibold text-blue-900 mb-1">Precisa de Ajuda?</h3>
                            <p class="text-xs text-blue-700 mb-3">Consulte nossa documentação para tirar suas dúvidas.</p>
                            <button class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Ver Documentação →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection