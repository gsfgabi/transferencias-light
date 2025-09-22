<div class="min-h-screen flex flex-col sm:justify-center items-center pt-24 sm:pt-16">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">💸 {{ __('messages.navigation.transfer') }}</h1>
            <p class="text-gray-600">{{ __('messages.info.transfer_description') }}</p>
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
                    <p><strong>{{ __('messages.forms.user_type') }}:</strong> {{ __('messages.roles.' . ($user->getRoleNames()->first() ?? 'user')) }}</p>
                    @if(!$user->hasRole('admin'))
                        <p><strong>{{ __('messages.forms.current_balance') }}:</strong> R$ {{ number_format($user->balance, 2, ',', '.') }}</p>
                    @endif
                </div>
            </div>

            @if ($user->type === 'merchant')
                <!-- Lojista não pode transferir -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-yellow-800 mb-2">⚠️ {{ __('messages.alerts.restriction') }}</h3>
                    <p class="text-sm text-yellow-700">Lojistas não podem realizar transferências.</p>
                </div>
            @elseif ($user->hasRole('admin'))
                <!-- Administrador não pode transferir -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🚫</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ __('messages.alerts.restricted_access') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('messages.alerts.admin_cannot_transfer') }}</p>
                    </div>
                </div>
            @else
                <!-- Formulário de Transferência -->
                <form wire:submit="transfer">
                    <!-- Email do Recebedor -->
                    <div class="mb-4">
                        <label for="payee_email" class="block text-sm font-semibold text-gray-700 mb-2">
                            📧 {{ __('messages.forms.payee_email') }}
                        </label>
                        <input type="email"
                               id="payee_email"
                               wire:model.live.debounce.300ms="form.payee_email"
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('form.payee_email') border-red-500 @else border-gray-300 @enderror"
                               placeholder="recebedor@email.com"
                               required>
                        @error('form.payee_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        @if($payee && $form->payee_email)
                            <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                <p class="text-sm text-green-700">
                                    <strong>{{ $payee->name }}</strong> - {{ $payee->email }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Valor da Transferência -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                            💰 {{ __('messages.forms.transfer_amount') }}
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
                                   max="{{ $user->balance }}"
                                   class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('form.amount') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="0,00"
                                   required>
                        </div>
                        @error('form.amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        @if($form->amount && $canTransfer)
                            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded">
                                <p class="text-sm text-blue-700">
                                    {{ __('messages.info.transfer_summary', [
                                        'amount' => 'R$ ' . number_format($form->amount, 2, ',', '.'),
                                        'recipient' => $payee?->name ?? __('messages.info.unknown_recipient')
                                    ]) }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Botão de Transferência -->
                    <button type="submit"
                            @disabled(!$canTransfer)
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 disabled:transform-none disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="transfer">
                            💸 {{ __('messages.buttons.transfer') }}
                        </span>
                        <span wire:loading wire:target="transfer">
                            {{ __('messages.info.processing') }}
                        </span>
                    </button>
                </form>
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
            @can('deposit.create')
                <a href="{{ route('deposit.form') }}"
                   class="block text-sm text-gray-600 hover:text-gray-800 underline">
                    💳 {{ __('messages.navigation.make_deposit') }}
                </a>
            @endcan
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('confirm-transfer', (event) => {
        const data = event[0];
        if (data && typeof Swal !== 'undefined') {
            confirmTransfer(data.amount, data.recipient).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('transfer');
                }
            });
        }
    });

    Livewire.on('transfer-completed', () => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '{{ __('messages.success.transfer_completed') }}',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});
</script>
