<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTransferNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $payee,
        public float $amount,
        public string $senderName
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Em ambiente local, simular envio de notificação
        if (app()->environment('local', 'testing')) {
            Log::info('Simulando envio de notificação em ambiente de desenvolvimento', [
                'payee_email' => $this->payee->email,
                'amount' => $this->amount,
                'sender_name' => $this->senderName
            ]);
            return;
        }

        $notificationServiceUrl = 'https://util.devi.tools/api/v1/notify';
        
        try {
            $response = Http::timeout(10)->post($notificationServiceUrl, [
                'payee_email' => $this->payee->email,
                'amount' => $this->amount,
                'sender_name' => $this->senderName,
            ]);

            if (!$response->successful()) {
                Log::warning('Falha ao enviar notificação', [
                    'payee_email' => $this->payee->email,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                $this->fail('Falha ao enviar notificação: ' . $response->status());
            }

            Log::info('Notificação enviada com sucesso', [
                'payee_email' => $this->payee->email,
                'amount' => $this->amount,
                'sender_name' => $this->senderName
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação', [
                'payee_email' => $this->payee->email,
                'error' => $e->getMessage()
            ]);
            
            // Em ambiente local, não falhar o job
            if (app()->environment('local', 'testing')) {
                Log::info('Ignorando falha de notificação em ambiente local');
                return;
            }
            
            $this->fail($e);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job de notificação falhou definitivamente', [
            'payee_email' => $this->payee->email,
            'amount' => $this->amount,
            'sender_name' => $this->senderName,
            'error' => $exception->getMessage()
        ]);
    }
}

