# Transferências Light

Uma plataforma de transferências simplificada desenvolvida com Laravel, Livewire e AlpineJS.

## 📋 Sobre o Projeto

O Transferências Light é uma aplicação que permite:
- Cadastro de usuários comuns e lojistas
- Carteiras digitais com saldo
- Transferências entre usuários
- Validação de autorização externa
- Sistema de notificações
- Histórico de transações

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Livewire + AlpineJS + TailwindCSS
- **Banco de Dados**: SQLite
- **Testes**: Pest PHP

## 📦 Instalação

### Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM

### Passos para Instalação

1. **Clone o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd transferencias-light
   ```

2. **Instale as dependências do PHP**
   ```bash
   composer install
   ```

3. **Instale as dependências do Node.js**
   ```bash
   npm install
   ```

4. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Execute as migrações e seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compile os assets**
   ```bash
   npm run build
   ```

7. **Inicie o servidor de desenvolvimento**
   ```bash
   php artisan serve
   ```

A aplicação estará disponível em `http://localhost:8000`

## 🎯 Funcionalidades

### Usuários
- **Usuários Comuns**: Podem enviar e receber transferências
- **Lojistas**: Apenas recebem transferências

### Transferências
- Validação de saldo antes da transferência
- Autorização externa via API mock
- Transações atômicas com rollback automático
- Notificações assíncronas para recebedores
- Histórico completo de transações

### Validações
- CPF/CNPJ único no sistema
- E-mail único no sistema
- Validação de saldo suficiente
- Verificação de autorização externa

## 🧪 Testes

Execute os testes com:
```bash
php artisan test
```

## 📁 Estrutura do Projeto

```
app/
├── Http/Controllers/     # Controllers da aplicação
├── Livewire/            # Componentes Livewire
├── Models/              # Modelos Eloquent
├── Services/            # Serviços de negócio
└── Providers/           # Service Providers

database/
├── migrations/          # Migrações do banco
└── seeders/            # Seeders para dados iniciais

resources/
├── views/              # Views Blade
├── css/                # Estilos CSS
└── js/                 # JavaScript

tests/                  # Testes automatizados
```

## 🔧 Configuração de Desenvolvimento

### Banco de Dados
O projeto usa SQLite por padrão. Para usar outro banco:

1. Configure o arquivo `.env`
2. Execute as migrações: `php artisan migrate`

### Serviços Externos
A aplicação usa APIs mock para:
- **Autorização**: `https://util.devi.tools/api/v2/authorize`
- **Notificações**: `https://util.devi.tools/api/v1/notify`

## 📝 Regras de Negócio

1. **Usuários**: Nome completo, CPF/CNPJ, e-mail e senha obrigatórios
2. **Transferências**: Apenas usuários comuns podem enviar dinheiro
3. **Validações**: Saldo suficiente e autorização externa obrigatórias
4. **Transações**: Operações atômicas com rollback em caso de erro
5. **Notificações**: Envio assíncrono para recebedores

## 🚀 Deploy

Para produção:

1. Configure o ambiente de produção no `.env`
2. Execute `composer install --optimize-autoloader --no-dev`
3. Execute `php artisan config:cache`
4. Execute `php artisan route:cache`
5. Execute `php artisan view:cache`
6. Configure o servidor web (Apache/Nginx)

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
