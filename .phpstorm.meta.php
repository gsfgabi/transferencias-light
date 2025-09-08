<?php

namespace PHPSTORM_META {
    // Mapear métodos do Livewire Volt
    override(\Livewire\Volt\Component::class, map([
        'logout' => 'void',
    ]));
}
