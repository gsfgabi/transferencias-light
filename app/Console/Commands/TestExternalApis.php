<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestExternalApis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:external-apis {--detailed : Show detailed output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test external APIs connectivity and responses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testando APIs Externas...');
        $this->newLine();

        $authorizationUrl = config('services.authorization.url');
        $notificationUrl = config('services.notification.url');
        $authorizationTimeout = config('services.authorization.timeout');
        $notificationTimeout = config('services.notification.timeout');

        $this->info("📋 Configurações:");
        $this->line("   • Authorization API: {$authorizationUrl}");
        $this->line("   • Notification API: {$notificationUrl}");
        $this->line("   • Authorization Timeout: {$authorizationTimeout}s");
        $this->line("   • Notification Timeout: {$notificationTimeout}s");
        $this->newLine();

        // Test Authorization API
        $this->testAuthorizationApi($authorizationUrl, $authorizationTimeout);

        $this->newLine();

        // Test Notification API
        $this->testNotificationApi($notificationUrl, $notificationTimeout);

        $this->newLine();
        $this->info('✅ Teste de APIs concluído!');
    }

    private function testAuthorizationApi(string $url, int $timeout): void
    {
        $this->info('🔐 Testando API de Autorização...');

        try {
            $startTime = microtime(true);
            $response = Http::timeout($timeout)->get($url);
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);

            $this->line("   Status: {$response->status()}");
            $this->line("   Tempo de resposta: {$responseTime}ms");

            if ($response->successful()) {
                $this->line("   ✅ API de autorização está funcionando");
                
                if ($this->option('detailed')) {
                    $data = $response->json();
                    $this->line("   Resposta: " . json_encode($data, JSON_PRETTY_PRINT));
                }
            } else {
                $this->line("   ⚠️ API retornou status de erro");
                if ($this->option('detailed')) {
                    $this->line("   Resposta: " . $response->body());
                }
            }

        } catch (\Exception $e) {
            $this->line("   ❌ Erro ao conectar com a API de autorização");
            $this->line("   Erro: " . $e->getMessage());
            
            Log::error('Erro ao testar API de autorização', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function testNotificationApi(string $url, int $timeout): void
    {
        $this->info('📧 Testando API de Notificação...');

        try {
            $startTime = microtime(true);
            $response = Http::timeout($timeout)->post($url, [
                'test' => true,
                'message' => 'Teste de conectividade'
            ]);
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);

            $this->line("   Status: {$response->status()}");
            $this->line("   Tempo de resposta: {$responseTime}ms");

            if ($response->successful()) {
                $this->line("   ✅ API de notificação está funcionando");
                
                if ($this->option('detailed')) {
                    $data = $response->json();
                    $this->line("   Resposta: " . json_encode($data, JSON_PRETTY_PRINT));
                }
            } else {
                $this->line("   ⚠️ API retornou status de erro");
                if ($this->option('detailed')) {
                    $this->line("   Resposta: " . $response->body());
                }
            }

        } catch (\Exception $e) {
            $this->line("   ❌ Erro ao conectar com a API de notificação");
            $this->line("   Erro: " . $e->getMessage());
            
            Log::error('Erro ao testar API de notificação', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}