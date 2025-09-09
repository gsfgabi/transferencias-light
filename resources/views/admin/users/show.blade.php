@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                        ← Voltar
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('admin.login-as', $user) }}">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium" onclick="return confirm('Deseja fazer login como este usuário?')">
                            Login como Usuário
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informações do Usuário -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informações do Usuário</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nome Completo</p>
                            <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="text-base font-semibold text-gray-900 break-all">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Documento</p>
                            <p class="text-base font-semibold text-gray-900">{{ $user->formatted_document }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Tipo de Conta</p>
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium
                                    {{ $role->name === 'admin' ? 'bg-red-100 text-red-800 border border-red-200' : 
                                       ($role->name === 'common-user' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                                       ($role->name === 'merchant' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 
                                       ($role->name === 'support' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200'))) }}">
                                    <span class="w-2 h-2 rounded-full mr-2
                                        {{ $role->name === 'admin' ? 'bg-red-500' : 
                                           ($role->name === 'common-user' ? 'bg-blue-500' : 
                                           ($role->name === 'merchant' ? 'bg-purple-500' : 
                                           ($role->name === 'support' ? 'bg-green-500' : 'bg-gray-500'))) }}"></span>
                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                </span>
                            @endforeach
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Membro desde</p>
                            <p class="text-base font-semibold text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Saldo -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Saldo</h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Saldo Disponível</p>
                                    <p class="text-3xl font-bold text-green-600">R$ {{ number_format($user->wallet->balance ?? 0, 2, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-600 text-xl">💰</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transações -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Transações</h3>
                    </div>
                    <div class="p-6">
                        @if($transactions->count() > 0)
                            <div class="space-y-4">
                                @foreach($transactions as $transaction)
                                    <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 {{ $transaction->sender_id === $user->id ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                                <span class="text-lg">{{ $transaction->sender_id === $user->id ? '📤' : '📥' }}</span>
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
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : ($transaction->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Paginação -->
                            @if($transactions->hasPages())
                                <div class="mt-6">
                                    {{ $transactions->links() }}
                                </div>
                            @endif
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
        </div>
    </div>
</div>
@endsection
