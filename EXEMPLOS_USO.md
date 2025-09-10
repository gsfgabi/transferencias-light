# 📱 Exemplos de Uso - Transferências Light

## 🎯 Cenários de Teste

### Cenário 1: Usuário Comum Enviando Transferência

1. **Acesse o sistema**
   - URL: `http://localhost:8000`
   - Faça login com: `usuario@teste.com` / `password`

2. **Verifique o saldo**
   - Acesse "Minha Carteira"
   - Saldo inicial: R$ 100,00

3. **Envie uma transferência**
   - Acesse "Transferir"
   - CPF do destinatário: `123.456.789-00` (lojista)
   - Valor: R$ 50,00
   - Confirme a operação

4. **Verifique o resultado**
   - Saldo atualizado: R$ 50,00
   - Histórico mostra a transferência

### Cenário 2: Lojista Recebendo Transferência

1. **Acesse com outro usuário**
   - Faça login com: `lojista@teste.com` / `password`

2. **Verifique o saldo**
   - Saldo inicial: R$ 100,00
   - Após receber: R$ 150,00

3. **Consulte o histórico**
   - Veja a transferência recebida
   - Status: "Concluída"

### Cenário 3: Teste de Validações

1. **Tente enviar valor maior que o saldo**
   - Valor: R$ 200,00 (saldo: R$ 100,00)
   - Resultado: Erro de saldo insuficiente

2. **Tente enviar para CPF inexistente**
   - CPF: `999.999.999-99`
   - Resultado: Erro de usuário não encontrado

3. **Tente enviar valor zero**
   - Valor: R$ 0,00
   - Resultado: Erro de valor inválido

## 🔧 Comandos de Teste

### Resetar Dados
```bash
# Recriar banco com dados iniciais
php artisan migrate:fresh --seed

# Com Docker
docker-compose exec app php artisan migrate:fresh --seed
```

### Adicionar Saldo
```bash
# Via tinker
php artisan tinker

# No tinker
$user = App\Models\User::where('email', 'usuario@teste.com')->first();
$user->wallet->update(['balance' => 1000.00]);
```

### Verificar Transações
```bash
# Via tinker
php artisan tinker

# No tinker
$transactions = App\Models\Transaction::with('payer', 'payee')->get();
$transactions->each(function($t) {
    echo "{$t->payer->name} -> {$t->payee->name}: R$ {$t->amount}\n";
});
```

## 📊 Dados de Teste Disponíveis

### Usuários Pré-cadastrados

| Tipo | Email | Senha | CPF/CNPJ | Saldo |
|------|-------|-------|----------|-------|
| Comum | `joao@example.com` | `password` | `111.222.333-44` | R$ 100,00 |
| Lojista | `loja@example.com` | `password` | `123.456.78/0001-99` | R$ 100,00 |
| Admin | `admin@example.com` | `password` | `999.888.777-66` | R$ 100,00 |

### CPFs/CNPJs Válidos para Teste

**CPFs:**
- `123.456.789-00` (Usuário Comum)
- `999.888.777-66` (Admin)
- `111.222.333-44` (Válido para cadastro)

**CNPJs:**
- `123.456.78/0001-99` (Lojista)

## 🧪 Testes Automatizados

### Executar Todos os Testes
```bash
php artisan test
```

### Executar Testes Específicos
```bash
# Testes de transferência
php artisan test --filter=TransferServiceTest

# Testes de usuário
php artisan test --filter=UserTest

# Testes de integração
php artisan test --filter=TransferIntegrationTest
```

### Testes com Cobertura
```bash
php artisan test --coverage
```

## 🔍 Debugging

### Verificar Logs
```bash
# Logs da aplicação
tail -f storage/logs/laravel.log

# Logs específicos
grep "TransferService" storage/logs/laravel.log
```

### Verificar Banco de Dados
```bash
# SQLite
sqlite3 database/database.sqlite
.tables
SELECT * FROM users;
SELECT * FROM transactions;
.quit
```

### Verificar Cache
```bash
# Limpar cache
php artisan cache:clear

# Verificar cache
php artisan cache:table
```

## 📈 Monitoramento de Performance

### Verificar Queries
```bash
# Ativar log de queries
# Adicione no .env: DB_LOG_QUERIES=true

# Ver queries no log
grep "Query" storage/logs/laravel.log
```

### Verificar Memória
```bash
# Verificar uso de memória
php artisan tinker
memory_get_usage(true)
```

## 🚀 Cenários Avançados

### Cenário 4: Múltiplas Transferências

1. **Crie vários usuários**
   ```bash
   php artisan tinker

   # No tinker
   $users = [
       ['name' => 'João Silva', 'email' => 'joao@teste.com', 'document' => '111.111.111-11'],
       ['name' => 'Maria Santos', 'email' => 'maria@teste.com', 'document' => '222.222.222-22'],
       ['name' => 'Pedro Costa', 'email' => 'pedro@teste.com', 'document' => '333.333.333-33']
   ];

   foreach($users as $userData) {
       $user = App\Models\User::create([
           'name' => $userData['name'],
           'email' => $userData['email'],
           'password' => bcrypt('password'),
           'document' => $userData['document'],
           'type' => 'common'
       ]);

       $user->wallet()->create(['balance' => 500.00]);
   }
   ```

2. **Execute transferências em lote**
   ```bash
   # No tinker
   $joao = App\Models\User::where('email', 'joao@teste.com')->first();
   $maria = App\Models\User::where('email', 'maria@teste.com')->first();
   $pedro = App\Models\User::where('email', 'pedro@teste.com')->first();

   $transferService = app(App\Services\TransferService::class);

   $transferService->transfer($joao, $maria, 100.00);
   $transferService->transfer($maria, $pedro, 50.00);
   $transferService->transfer($pedro, $joao, 25.00);
   ```

### Cenário 5: Teste de Concorrência

1. **Simule transferências simultâneas**
   ```bash
   # Script para testar concorrência
   # Crie um arquivo test_concurrency.php
   ```

2. **Verifique integridade dos dados**
   ```bash
   # No tinker
   $totalBalance = App\Models\Wallet::sum('balance');
   echo "Saldo total: R$ {$totalBalance}\n";

   $totalTransactions = App\Models\Transaction::sum('amount');
   echo "Total transferido: R$ {$totalTransactions}\n";
   ```

## 📱 Testes de Interface

### Teste de Responsividade
1. Acesse em diferentes dispositivos
2. Teste em diferentes resoluções
3. Verifique se os formulários funcionam

### Teste de Usabilidade
1. Navegue pelo sistema sem documentação
2. Teste todos os botões e links
3. Verifique mensagens de erro e sucesso

---

**🎉 Agora você está pronto para testar todos os cenários do sistema!**
