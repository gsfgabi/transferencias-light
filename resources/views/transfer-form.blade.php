@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">💸 Transferência</h1>
                <p class="text-gray-600">Envie dinheiro para outros usuários</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                    <div class="text-green-700">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="text-red-700">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Informações do Usuário -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">👤 Suas Informações</h3>
                <div class="text-sm text-blue-700">
                    <p><strong>Nome:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Tipo:</strong> {{ auth()->user()->type === 'common' ? 'Usuário Comum' : 'Lojista' }}</p>
                    <p><strong>Saldo Atual:</strong> R$ {{ number_format(auth()->user()->balance, 2, ',', '.') }}</p>
                </div>
            </div>

            @if (auth()->user()->type === 'merchant')
                <!-- Lojista não pode transferir -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-yellow-800 mb-2">⚠️ Restrição</h3>
                    <p class="text-sm text-yellow-700">Lojistas só podem receber transferências, não podem enviar dinheiro.</p>
                </div>
            @else
                <!-- Formulário de Transferência -->
                <form method="POST" action="{{ route('transfer.process') }}">
                    @csrf
                    
                    <!-- Email do Recebedor -->
                    <div class="mb-4">
                        <label for="payee_email" class="block text-sm font-semibold text-gray-700 mb-2">
                            📧 Email do Recebedor
                        </label>
                        <input type="email" 
                               id="payee_email" 
                               name="payee_email" 
                               value="{{ old('payee_email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="recebedor@email.com"
                               required>
                    </div>

                    <!-- Valor da Transferência -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                            💰 Valor da Transferência
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" 
                                   id="amount" 
                                   name="amount" 
                                   step="0.01" 
                                   min="0.01"
                                   value="{{ old('amount') }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="0,00"
                                   required>
                        </div>
                    </div>

                    <!-- Botão de Transferência -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                        💸 Transferir Agora
                    </button>
                </form>
            @endif

            <!-- Links -->
            <div class="mt-6 text-center space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="block text-sm text-blue-600 hover:text-blue-800 underline">
                    ← Voltar ao Dashboard
                </a>
                <a href="{{ route('deposit.form') }}" 
                   class="block text-sm text-gray-600 hover:text-gray-800 underline">
                    💳 Fazer Depósito
                </a>
                <a href="{{ route('logout.get') }}" 
                   class="text-sm text-red-600 hover:text-red-800 underline"
                   onclick="return confirm('Tem certeza que deseja sair?')">
                    🚪 Sair
                </a>
            </div>
        </div>
    </div>
@endsection
