<?php

namespace App\Livewire;

use Livewire\Component;

class TestComponent extends Component
{
    public $message = 'Teste inicial';
    
    public function testMethod()
    {
        $this->message = 'Método executado com sucesso!';
    }
    
    public function render()
    {
        return view('livewire.test-component');
    }
}
