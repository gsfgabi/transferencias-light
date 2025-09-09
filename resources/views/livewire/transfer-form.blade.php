<div class="min-h-screen flex flex-col sm:justify-center items-center pt-24 sm:pt-16">
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

            @if($user)
                <!-- Aviso para Administradores -->
                @if($user->hasRole('admin'))
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Acesso Administrativo
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Como administrador, você pode visualizar este formulário, mas não pode realizar transferências.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Informações do Usuário -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-blue-800 mb-2">👤 Suas Informações</h3>
                    <div class="text-sm text-blue-700">
                        <p><strong>Nome:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Tipo:</strong> {{ $user->type === 'common' ? 'Usuário Comum' : ($user->type === 'merchant' ? 'Lojista' : 'Administrador') }}</p>
                        @if(!$user->hasRole('admin'))
                            <p><strong>Saldo Atual:</strong> R$ {{ number_format($user->balance, 2, ',', '.') }}</p>
                        @else
                            <p><strong>Função:</strong> Administrador</p>
                        @endif
                    </div>
                </div>

                @if ($user->type === 'merchant')
                <!-- Lojista não pode transferir -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-yellow-800 mb-2">⚠️ Restrição</h3>
                    <p class="text-sm text-yellow-700">Lojistas só podem receber transferências, não podem enviar dinheiro.</p>
                </div>
            @elseif ($user->hasRole('admin'))
                <!-- Administrador não pode transferir -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🚫</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Acesso Restrito</h3>
                        <p class="text-gray-600 mb-4">Administradores não podem realizar transferências.</p>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Este formulário é apenas para visualização.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Formulário de Transferência -->
                <form wire:submit="transfer">
                    <!-- Email do Recebedor -->
                    <div class="mb-4">
                        <label for="payee_email" class="block text-sm font-semibold text-gray-700 mb-2">
                            📧 Email do Recebedor
                        </label>
                        <input type="email"
                               id="payee_email"
                               wire:model="payee_email"
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('payee_email') border-red-500 @else border-gray-300 @enderror"
                               placeholder="recebedor@email.com"
                               required>
                        @error('payee_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                                   wire:model="amount"
                                   step="0.01"
                                   min="0.01"
                                   class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('amount') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="0,00"
                                   required>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botão de Teste -->
                    <button type="button"
                            wire:click="testMethod"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-2 px-4 rounded-lg mb-2"
                            onclick="console.log('🧪 BOTÃO TESTE CLICADO!');">
                        🧪 Teste Simples
                    </button>

                    <!-- Botão de Transferência -->
                    <button type="button"
                            wire:click="confirmTransfer"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                            wire:loading.attr="disabled"
                            onclick="console.log('🖱️ BOTÃO CLICADO!'); console.log('Dados do formulário:', {payee_email: document.getElementById('payee_email').value, amount: document.getElementById('amount').value});">
                        <span wire:loading.remove>💸 Transferir Agora</span>
                        <span wire:loading>Processando...</span>
                    </button>
                </form>
                @endif
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-red-800 mb-2">⚠️ Erro</h3>
                    <p class="text-sm text-red-700">Usuário não autenticado. Faça login para continuar.</p>
                </div>
            @endif

            <!-- Links -->
            <div class="mt-6 text-center space-y-2">
                <a href="{{ route('dashboard') }}"
                   class="block text-sm text-blue-600 hover:text-blue-800 underline">
                    ← Voltar ao Dashboard
                </a>
                @can('deposit.create')
                    <a href="{{ route('deposit.form') }}"
                       class="block text-sm text-gray-600 hover:text-gray-800 underline">
                        💳 Fazer Depósito
                    </a>
                @endcan
            </div>
    </div>
</div>

<script>
console.log('=== INICIANDO SCRIPT JAVASCRIPT ===');

// Aguardar o Livewire carregar completamente
document.addEventListener('livewire:init', () => {
    console.log('✅ Livewire inicializado com sucesso');
    
    // Aguardar o componente ser inicializado
    setTimeout(() => {
        console.log('Verificando $wire após inicialização...');
        console.log('$wire disponível:', typeof $wire !== 'undefined');
        
        if (typeof $wire !== 'undefined') {
            console.log('✅ $wire disponível!');
        } else {
            console.log('❌ $wire ainda não disponível - aguardando mais...');
            // Tentar novamente após mais tempo
            setTimeout(() => {
                console.log('Segunda verificação - $wire disponível:', typeof $wire !== 'undefined');
                
                // Verificar se há componentes Livewire na página
                const livewireComponents = document.querySelectorAll('[wire\\:id]');
                console.log('Componentes Livewire encontrados:', livewireComponents.length);
                livewireComponents.forEach((comp, index) => {
                    console.log(`Componente ${index}:`, comp.getAttribute('wire:id'));
                });
                
                // Tentar acessar o $wire de forma diferente
                if (window.Livewire && window.Livewire.all) {
                    console.log('Livewire.all disponível:', window.Livewire.all);
                    const components = window.Livewire.all();
                    console.log('Componentes Livewire ativos:', components.length);
                    if (components.length > 0) {
                        console.log('Primeiro componente:', components[0]);
                        window.$wire = components[0];
                        console.log('$wire definido manualmente:', typeof window.$wire !== 'undefined');
                    }
                }
            }, 1000);
        }
    }, 1000);
    
    Livewire.on('confirm-transfer', (event) => {
        console.log('🎯 Evento confirm-transfer recebido!');
        console.log('Evento completo:', event);
        
        const data = event[0];
        if (data) {
            console.log('Dados do evento:', data);
            
            // Verificar se SweetAlert2 está disponível
            if (typeof Swal !== 'undefined' && typeof confirmTransfer === 'function') {
                console.log('Chamando SweetAlert...');
                confirmTransfer(data.amount, data.recipient).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Usuário confirmou, chamando transfer...');
                        // Verificar se $wire está disponível antes de usar
                        if (typeof $wire !== 'undefined') {
                            $wire.call('transfer');
                        } else {
                            console.error('❌ $wire não está disponível!');
                            // Tentar usar Livewire diretamente
                            if (typeof Livewire !== 'undefined') {
                                console.log('Tentando usar Livewire diretamente...');
                                Livewire.dispatch('transfer');
                            } else {
                                console.error('❌ Livewire também não está disponível!');
                            }
                        }
                    } else {
                        console.log('Usuário cancelou');
                    }
                });
            } else {
                console.log('SweetAlert não disponível, chamando transfer diretamente');
                if (typeof $wire !== 'undefined') {
                    $wire.call('transfer');
                } else {
                    console.error('❌ $wire não está disponível!');
                }
            }
        }
    });
    
    // Adicionar listener para erros do Livewire
    Livewire.on('exception', (event) => {
        console.error('❌ ERRO NO LIVEWIRE:', event);
    });
    
    console.log('✅ Todos os listeners configurados');
});

// Verificar se o DOM está carregado
if (document.readyState === 'loading') {
    console.log('⏳ DOM ainda carregando...');
    document.addEventListener('DOMContentLoaded', () => {
        console.log('✅ DOM carregado');
    });
} else {
    console.log('✅ DOM já carregado');
}

console.log('=== FIM SCRIPT JAVASCRIPT ===');
</script>
