<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Transaction;
use App\Services\TransferService;
use Exception;

class TransferController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function show()
    {
        return view('transfer-form');
    }

    public function process(Request $request)
    {
        // Se não estiver autenticado, redirecionar para login
        if (!Auth::check()) {
            return redirect()->route('transfer.form')->with('error', 
                'Você precisa fazer login para realizar uma transferência.'
            );
        }

        $request->validate([
            'payee_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:0.01',
        ], [
            'payee_email.required' => 'O email do recebedor é obrigatório.',
            'payee_email.email' => 'Digite um email válido.',
            'payee_email.exists' => 'Usuário não encontrado com este email.',
            'amount.required' => 'O valor da transferência é obrigatório.',
            'amount.numeric' => 'O valor deve ser um número válido.',
            'amount.min' => 'O valor mínimo para transferência é R$ 0,01.',
        ]);

        try {
            $sender = Auth::user();
            $payee = User::where('email', $request->payee_email)->first();
            $amount = $request->amount;

            // Verificar se não está transferindo para si mesmo
            if ($sender->id === $payee->id) {
                return redirect()->route('transfer.form')->with('error', 
                    'Você não pode transferir para si mesmo.'
                );
            }

            // Verificar se é lojista tentando transferir
            if ($sender->type === 'merchant') {
                return redirect()->route('transfer.form')->with('error', 
                    'Lojistas não podem realizar transferências.'
                );
            }

            // Verificar saldo suficiente
            if ($sender->balance < $amount) {
                return redirect()->route('transfer.form')->with('error', 
                    'Saldo insuficiente para realizar a transferência.'
                );
            }

            // Realizar transferência usando o serviço
            $result = $this->transferService->processTransfer($sender, $payee, $amount);

            if ($result['success']) {
                return redirect()->route('transfer.form')->with('success', 
                    'Transferência realizada com sucesso! Valor: R$ ' . number_format($amount, 2, ',', '.')
                );
            } else {
                return redirect()->route('transfer.form')->with('error', $result['message']);
            }

        } catch (Exception $e) {
            Log::error('Erro ao processar transferência', [
                'sender_id' => Auth::id(),
                'payee_email' => $request->payee_email,
                'amount' => $request->amount,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('transfer.form')->with('error', 
                'Erro ao processar a transferência: ' . $e->getMessage()
            );
        }
    }
}
