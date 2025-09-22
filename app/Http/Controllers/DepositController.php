<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Exception;

class DepositController extends Controller
{
    public function show()
    {
        return view('deposit', [
            'component' => \App\Livewire\Deposit\DepositFormComponent::class
        ]);
    }

}
