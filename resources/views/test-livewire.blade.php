<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teste Livewire</title>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
    @livewireStyles
    @livewireScripts
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Teste do Livewire</h1>
        
        <livewire:test-component />
        
        <div class="mt-4">
            <a href="/transfer" class="text-blue-500 underline">Voltar para Transfer</a>
        </div>
    </div>
    
    <script>
    console.log('=== DEBUG LIVEWIRE ===');
    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
    console.log('Livewire disponível:', typeof Livewire !== 'undefined');
    console.log('$wire disponível:', typeof $wire !== 'undefined');
    
    // Verificar se há erros no console
    window.addEventListener('error', function(e) {
        console.error('Erro JavaScript:', e.error);
    });
    
    // Aguardar o Livewire carregar
    setTimeout(() => {
        console.log('Após timeout - Livewire:', typeof Livewire !== 'undefined');
        console.log('Após timeout - $wire:', typeof $wire !== 'undefined');
        
        // Verificar se há componentes Livewire na página
        const livewireComponents = document.querySelectorAll('[wire\\:id]');
        console.log('Componentes Livewire encontrados:', livewireComponents.length);
        livewireComponents.forEach((comp, index) => {
            console.log(`Componente ${index}:`, comp.getAttribute('wire:id'));
        });
    }, 2000);
    </script>
</body>
</html>
