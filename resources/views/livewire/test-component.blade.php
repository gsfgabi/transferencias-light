<div>
    <h1>Teste Livewire</h1>
    <p>{{ $message }}</p>
    <button wire:click="testMethod" class="bg-blue-500 text-white px-4 py-2 rounded">
        Testar Método
    </button>
    
    <script>
    console.log('=== TESTE LIVEWIRE ===');
    console.log('$wire disponível:', typeof $wire !== 'undefined');
    
    document.addEventListener('livewire:init', () => {
        console.log('✅ Livewire inicializado no teste');
        console.log('$wire disponível após init:', typeof $wire !== 'undefined');
    });
    </script>
</div>
