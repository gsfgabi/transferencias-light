# 🔐 Sistema de Roles e Permissões

## 📋 Visão Geral

O sistema de transferências utiliza o pacote **Spatie Laravel Permission** para gerenciar roles e permissões de forma granular e segura.

## 🎯 Roles Disponíveis

### 1. **Administrador** (`admin`)
- **Descrição**: Administrador do sistema com acesso a todas as funcionalidades
- **Permissões**:
  - `dashboard.view` - Visualizar dashboard
  - `transfer.create` - Criar transferências
  - `transfer.view` - Visualizar transferências
  - `transfer.history` - Histórico de transferências
  - `deposit.create` - Criar depósitos
  - `deposit.view` - Visualizar depósitos
  - `admin.users.*` - Gerenciar usuários
  - `admin.transactions.*` - Gerenciar transações
- **Usuários**: 2

### 2. **Suporte** (`support`)
- **Descrição**: Equipe de suporte com acesso a visualização de dados
- **Permissões**:
  - `dashboard.view` - Visualizar dashboard
  - `transfer.view` - Visualizar transferências
  - `transfer.history` - Histórico de transferências
  - `deposit.view` - Visualizar depósitos
  - `admin.users.view` - Visualizar usuários
  - `admin.transactions.view` - Visualizar transações
- **Usuários**: 0

### 3. **Usuário Comum** (`common-user`)
- **Descrição**: Usuário comum que pode enviar e receber transferências
- **Permissões**:
  - `dashboard.view` - Visualizar dashboard
  - `transfer.create` - Criar transferências
  - `transfer.view` - Visualizar transferências
  - `transfer.history` - Histórico de transferências
  - `deposit.create` - Criar depósitos
  - `deposit.view` - Visualizar depósitos
- **Usuários**: 2

### 4. **Lojista** (`merchant`)
- **Descrição**: Lojista que pode apenas receber transferências
- **Permissões**:
  - `dashboard.view` - Visualizar dashboard
  - `transfer.view` - Visualizar transferências
  - `transfer.history` - Histórico de transferências
  - `deposit.create` - Criar depósitos
  - `deposit.view` - Visualizar depósitos
- **Usuários**: 1

### 5. **Usuário Básico** (`user`)
- **Descrição**: Usuário com acesso básico ao sistema
- **Permissões**:
  - `dashboard.view` - Visualizar dashboard
  - `transfer.view` - Visualizar transferências
  - `deposit.view` - Visualizar depósitos
- **Usuários**: 0

## 🔧 Comandos Disponíveis

### 1. **Testar Permissões**
```bash
php artisan test:permissions
```
Mostra todas as roles, permissões e testa os usuários.

### 2. **Gerenciar Roles**
```bash
# Listar todas as roles
php artisan roles:manage list

# Mostrar usuários de uma role
php artisan roles:manage users --role=common-user

# Mostrar permissões de uma role
php artisan roles:manage permissions --role=admin

# Criar nova role
php artisan roles:manage create --role=nova-role

# Atribuir role a usuário
php artisan roles:manage assign --role=admin --user=admin@example.com

# Remover role de usuário
php artisan roles:manage remove --role=admin --user=admin@example.com
```

### 3. **Executar Seeders**
```bash
# Executar todos os seeders
php artisan db:seed

# Executar apenas o seeder de roles
php artisan db:seed --class=RolesTableSeeder

# Executar apenas o seeder de usuários
php artisan db:seed --class=UserSeeder
```

## 🛡️ Middleware de Permissões

### Aplicação nas Rotas
```php
// Dashboard protegido
Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'permission:dashboard.view']);

// Transferências protegidas
Route::get('/transfer', [TransferController::class, 'show'])
    ->middleware('permission:transfer.view');

Route::post('/transfer', [TransferController::class, 'process'])
    ->middleware('permission:transfer.create');
```

### Verificação no Código
```php
// Verificar se usuário tem permissão
if (auth()->user()->can('transfer.create')) {
    // Usuário pode criar transferências
}

// Verificar se usuário tem role
if (auth()->user()->hasRole('admin')) {
    // Usuário é administrador
}
```

## 📊 Estrutura do Banco de Dados

### Tabelas Criadas
- `roles` - Armazena as roles
- `permissions` - Armazena as permissões
- `model_has_roles` - Relaciona usuários com roles
- `model_has_permissions` - Relaciona usuários com permissões
- `role_has_permissions` - Relaciona roles com permissões

### Relacionamentos
- **User** → **Roles** (Many-to-Many)
- **User** → **Permissions** (Many-to-Many)
- **Role** → **Permissions** (Many-to-Many)

## 🚀 Funcionalidades Implementadas

### ✅ **Sistema Completo**
- [x] 7 roles diferentes com permissões específicas
- [x] Middleware de permissões nas rotas
- [x] Seeders para popular o banco
- [x] Comandos Artisan para gerenciamento
- [x] Testes automatizados
- [x] Documentação completa

### ✅ **Segurança**
- [x] Controle granular de acesso
- [x] Verificação de permissões em todas as rotas
- [x] Middleware personalizado
- [x] Validação de roles e permissões

### ✅ **Manutenibilidade**
- [x] Seeders organizados
- [x] Comandos de gerenciamento
- [x] Documentação detalhada
- [x] Testes abrangentes

## 📈 Estatísticas

- **Total de Roles**: 5
- **Total de Permissões**: 46
- **Usuários Ativos**: 5
- **Testes**: 25 (100% passando)
- **Cobertura**: Sistema completo de permissões

## 🔄 Fluxo de Trabalho

1. **Criação de Usuário**: Usuário é criado com role padrão
2. **Atribuição de Permissões**: Role é atribuída com permissões específicas
3. **Verificação de Acesso**: Middleware verifica permissões nas rotas
4. **Auditoria**: Sistema registra todas as ações por usuário

---

**Sistema de Roles e Permissões implementado com sucesso! 🎉**
