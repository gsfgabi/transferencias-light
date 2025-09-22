<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Jobs\SendTransferNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TransferService
{
    /**
     * Realiza uma transferência entre dois usuários.
     *
     * @param User $sender O usuário remetente.
     * @param User $payee O usuário recebedor.
     * @param float $amount O valor da transferência.
     * @return array Retorna um array com 'success' (bool) e 'message' (string).
     */
    public function processTransfer(User $sender, User $payee, float $amount): array
    {
        // 1. Validações de Regra de Negócio
        $validationResult = $this->validateTransfer($sender, $payee, $amount);
        if (!$validationResult['valid']) {
            return ['success' => false, 'message' => $validationResult['message']];
        }

        // 2. Consultar serviço autorizador externo
        $authorizationResult = $this->checkAuthorization();
        if (!$authorizationResult['authorized']) {
            return ['success' => false, 'message' => $authorizationResult['message']];
        }

        // 3. Operação de transferência como transação
        return DB::transaction(function () use ($sender, $payee, $amount) {
            try {
                // Criar a transação primeiro
                $transaction = $this->createTransaction($sender, $payee, $amount, 'processing');

                // Debitar do remetente
                $sender->wallet()->lockForUpdate()->decrement('balance', $amount);

                // Creditar no recebedor
                $payee->wallet()->lockForUpdate()->increment('balance', $amount);

                // Atualizar status da transação
                $transaction->update(['status' => 'completed']);

                // Enviar notificação assíncrona
                SendTransferNotification::dispatch($payee, $amount, $sender->name);

                Log::info("Transferência realizada com sucesso", [
                    'sender_id' => $sender->id,
                    'payee_id' => $payee->id,
                    'amount' => $amount,
                    'transaction_id' => $transaction->id
                ]);

                return ['success' => true, 'message' => 'Transferência realizada com sucesso!'];

            } catch (Exception $e) {
                // Atualizar transação como falha
                if (isset($transaction)) {
                    $transaction->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage()
                    ]);
                }

                Log::error('Erro na transferência', [
                    'sender_id' => $sender->id,
                    'payee_id' => $payee->id,
                    'amount' => $amount,
                    'error' => $e->getMessage()
                ]);

                throw $e; // Re-throw para que a transação seja revertida
            }
        });
    }

    /**
     * Valida se a transferência pode ser realizada.
     */
    protected function validateTransfer(User $sender, User $payee, float $amount): array
    {
        // Verificar se o remetente pode enviar dinheiro
        if (!$sender->canSendMoney()) {
            return ['valid' => false, 'message' => 'Lojistas não podem realizar transferências.'];
        }

        // Verificar se o recebedor pode receber dinheiro
        if (!$payee->canReceiveMoney()) {
            return ['valid' => false, 'message' => 'Não é possível transferir para si mesmo.'];
        }

        // Verificar se não está transferindo para si mesmo
        if ($sender->id === $payee->id) {
            return ['valid' => false, 'message' => 'Não é possível transferir para si mesmo.'];
        }

        // Verificar saldo suficiente
        if ($sender->balance < $amount) {
            return ['valid' => false, 'message' => 'Saldo insuficiente para realizar a transferência.'];
        }

        // Verificar valor mínimo
        if ($amount <= 0) {
            return ['valid' => false, 'message' => 'O valor mínimo para depósito é R$ 0,01.'];
        }

        return ['valid' => true];
    }

    /**
     * Verifica autorização externa.
     * Em ambiente de desenvolvimento, simula autorização.
     */
    protected function checkAuthorization(): array
    {
        // Em ambiente de desenvolvimento ou teste, simular autorização
        if (app()->environment('local', 'testing')) {
            Log::info('Simulando autorização em ambiente de desenvolvimento/teste');
            return ['authorized' => true];
        }

        $authorizationServiceUrl = config('services.authorization.url');
        $timeout = config('services.authorization.timeout');
        
        try {
            $response = Http::timeout($timeout)->get($authorizationServiceUrl);
            
            if (!$response->successful()) {
                Log::warning('Serviço de autorização retornou erro HTTP', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return ['authorized' => false, 'message' => 'Serviço de autorização indisponível.'];
            }

            $data = $response->json();
            if (isset($data['message']) && $data['message'] !== 'Autorizado') {
                return ['authorized' => false, 'message' => 'Transferência não autorizada pelo serviço externo.'];
            }

            return ['authorized' => true];

        } catch (Exception $e) {
            Log::error('Erro ao consultar serviço de autorização', [
                'error' => $e->getMessage(),
                'url' => $authorizationServiceUrl
            ]);
            
            // Em caso de erro, permitir transferência em ambiente local
            if (app()->environment('local', 'testing')) {
                Log::info('Permitindo transferência em ambiente local devido a erro de autorização');
                return ['authorized' => true];
            }
            
            return ['authorized' => false, 'message' => 'Serviço de autorização indisponível.'];
        }
    }

    /**
     * Cria uma nova transação.
     */
    protected function createTransaction(User $sender, User $payee, float $amount, string $status): Transaction
    {
        return Transaction::create([
            'sender_id' => $sender->id,
            'payee_id' => $payee->id,
            'amount' => $amount,
            'status' => $status,
        ]);
    }


    /**
     * Obtém o histórico de transações de um usuário.
     */
    public function getUserTransactionHistory(User $user, int $limit = 50): array
    {
        $sentTransactions = $user->sentTransactions()
            ->with('payee')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $receivedTransactions = $user->receivedTransactions()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return [
            'sent' => $sentTransactions,
            'received' => $receivedTransactions,
            'total_sent' => $user->sentTransactions()->sum('amount'),
            'total_received' => $user->receivedTransactions()->sum('amount'),
        ];
    }
}