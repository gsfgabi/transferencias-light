<div class="min-h-screen flex flex-col sm:justify-center items-center pt-24 sm:pt-16">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">💰 {{ __('messages.navigation.deposit') }}</h1>
            <p class="text-gray-600">{{ __('messages.info.deposit_description') }}</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                <div class="text-green-700">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($user)
            <!-- Informações do Usuário -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">👤 {{ __('messages.alerts.user_info') }}</h3>
                <div class="text-sm text-blue-700">
                    <p><strong>{{ __('messages.forms.name') }}:</strong> {{ $user->name }}</p>
                    <p><strong>{{ __('messages.forms.email') }}:</strong> {{ $user->email }}</p>
                    @if($canDeposit)
                        <p><strong>{{ __('messages.forms.current_balance') }}:</strong> R$ {{ number_format($user->balance, 2, ',', '.') }}</p>
                    @else
                        <p><strong>{{ __('messages.forms.function') }}:</strong> {{ __('messages.roles.admin') }}</p>
                    @endif
                </div>
            </div>

            @if ($canDeposit)
                <!-- Formulário de Depósito -->
                <form wire:submit="deposit">
                    <!-- Valor do Depósito -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                            💰 {{ __('messages.forms.deposit_amount') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number"
                                   id="amount"
                                   wire:model.live.debounce.300ms="form.amount"
                                   step="0.01"
                                   min="0.01"
                                   max="10000.00"
                                   class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('form.amount') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="0,00"
                                   required>
                        </div>
                        @error('form.amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        @if($form->amount)
                            <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                <p class="text-sm text-green-700">
                                    {{ __('messages.info.deposit_summary', [
                                        'amount' => 'R$ ' . number_format($form->amount, 2, ',', '.'),
                                        'new_balance' => 'R$ ' . number_format($user->balance + (float)$form->amount, 2, ',', '.')
                                    ]) }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Botão de Depósito -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="deposit">
                            💰 {{ __('messages.buttons.deposit') }}
                        </span>
                        <span wire:loading wire:target="deposit">
                            {{ __('messages.info.processing') }}
                        </span>
                    </button>
                </form>
            @else
                <!-- Administrador não pode depositar -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🚫</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Acesso Restrito</h3>
                        <p class="text-gray-600 mb-4">Administradores não podem realizar depósitos.</p>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Apenas visualização</p>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-red-800 mb-2">⚠️ {{ __('messages.alerts.error') }}</h3>
                <p class="text-sm text-red-700">{{ __('messages.error.user_not_authenticated') }}</p>
            </div>
        @endif

        <!-- Links -->
        <div class="mt-6 text-center space-y-2">
            <a href="{{ route('dashboard') }}"
               class="block text-sm text-blue-600 hover:text-blue-800 underline">
                ← {{ __('messages.navigation.back_to_dashboard') }}
            </a>
            @can('transfer.create')
                <a href="{{ route('transfer.form') }}"
                   class="block text-sm text-gray-600 hover:text-gray-800 underline">
                    💸 {{ __('messages.navigation.transfer') }}
                </a>
            @endcan
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('confirm-deposit', (event) => {
        const data = event[0];
        if (data && typeof Swal !== 'undefined') {
            confirmDeposit(data.amount).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deposit');
                }
            });
        }
    });

    Livewire.on('deposit-completed', () => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '{{ __('messages.success.deposit_completed') }}',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});
</script>
