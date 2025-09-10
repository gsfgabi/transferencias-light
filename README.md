# 💰 Transferências Light

Uma plataforma de transferências simplificada desenvolvida com Laravel, Livewire e AlpineJS.

## 📋 Sobre o Projeto

O **Transferências Light** é uma aplicação web que simula um sistema de transferências financeiras, permitindo:

- 👥 **Cadastro de usuários** (comuns e lojistas)
- 💳 **Carteiras digitais** com controle de saldo
- 💸 **Transferências entre usuários** com validações
- 🔐 **Autorização externa** via API mock
- 📧 **Sistema de notificações** assíncronas
- 📊 **Histórico completo** de transações
- 🎨 **Interface moderna** e responsiva

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Livewire + AlpineJS + TailwindCSS
- **Banco de Dados**: SQLite (padrão) / MySQL / PostgreSQL
- **Testes**: Pest PHP
- **Containerização**: Docker + Docker Compose

## 📦 Instalação Rápida

### 🐳 Opção 1: Com Docker (Recomendado)

**Pré-requisitos:**
- Docker
- Docker Compose

**Passos:**

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/transferencias-light.git
   cd transferencias-light
   ```

2. **Configure o ambiente**
   ```bash
   cp .env.example .env
   ```

3. **Inicie os containers**
   ```bash
   docker-compose up -d
   ```

4. **Execute as migrações e seeders**
   ```bash
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan db:seed
   ```

5. **Acesse a aplicação**
   - URL: `http://localhost:8000`

### 💻 Opção 2: Instalação Local

**Pré-requisitos:**
- PHP 8.2 ou superior
- Composer
- Node.js 18+ e NPM
- SQLite (ou MySQL/PostgreSQL)

**Passos:**

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/transferencias-light.git
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

5. **Configure o banco de dados**
   
   **Para SQLite (padrão):**
   ```bash
   touch database/database.sqlite
   ```
   
   **Para MySQL/PostgreSQL:**
   - Edite o arquivo `.env` com suas credenciais
   - Exemplo para MySQL:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=transferencias_light
     DB_USERNAME=root
     DB_PASSWORD=sua_senha
     ```

6. **Execute as migrações e seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Compile os assets**
   ```bash
   npm run build
   ```

8. **Inicie o servidor de desenvolvimento**
   ```bash
   php artisan serve
   ```

9. **Acesse a aplicação**
   - URL: `http://localhost:8000`

## 🎯 Funcionalidades

### 👥 Usuários
- **Usuários Comuns**: Podem enviar e receber transferências
- **Lojistas**: Apenas recebem transferências
- **Cadastro seguro** com validação de CPF/CNPJ
- **Sistema de autenticação** completo

### 💸 Transferências
- **Validação de saldo** antes da transferência
- **Autorização externa** via API mock
- **Transações atômicas** com rollback automático
- **Notificações assíncronas** para recebedores
- **Histórico completo** de transações
- **Interface intuitiva** para operações

### 🔐 Validações
- **CPF/CNPJ único** no sistema
- **E-mail único** no sistema
- **Validação de saldo** suficiente
- **Verificação de autorização** externa
- **Validação de dados** em tempo real

## 🚀 Primeiros Passos

### 1. Acesse o Sistema
Após a instalação, acesse `http://localhost:8000`

### 2. Cadastre-se
- Clique em "Registrar"
- Preencha seus dados (nome, CPF/CNPJ, email, senha)
- Escolha o tipo de usuário (Comum ou Lojista)

### 3. Faça Login
- Use suas credenciais para acessar o sistema
- Você será redirecionado para o dashboard

### 4. Configure sua Carteira
- Acesse "Minha Carteira"
- Adicione saldo inicial (simulado)
- Visualize seu saldo atual

### 5. Realize Transferências
- **Para enviar**: Acesse "Transferir"
- **Para receber**: Compartilhe seu CPF/CNPJ
- **Para visualizar**: Acesse "Histórico"

## 📱 Como Usar o Sistema

### Para Usuários Comuns

1. **Enviar Transferência:**
   - Acesse "Transferir" no menu
   - Digite o CPF/CNPJ do destinatário
   - Informe o valor desejado
   - Confirme a operação

2. **Receber Transferência:**
   - Compartilhe seu CPF/CNPJ
   - Aguarde a confirmação
   - Verifique o saldo atualizado

3. **Visualizar Histórico:**
   - Acesse "Histórico" no menu
   - Veja todas as transações
   - Filtre por período ou tipo

### Para Lojistas

1. **Receber Transferências:**
   - Compartilhe seu CPF/CNPJ
   - Aguarde as transferências
   - Verifique o saldo atualizado

2. **Gerenciar Vendas:**
   - Acesse "Minha Carteira"
   - Visualize o saldo disponível
   - Consulte o histórico de recebimentos

## 🧪 Testes

### Executar Testes

**Com Docker:**
```bash
docker-compose exec app php artisan test
```

**Localmente:**
```bash
php artisan test
```

### Tipos de Testes
- **Testes Unitários**: Validação de modelos e serviços
- **Testes de Integração**: Fluxo completo de transferências
- **Testes de API**: Endpoints e validações

## 🔧 Configurações Avançadas

### Variáveis de Ambiente

O arquivo `.env` contém todas as configurações necessárias:

```env
# Aplicação
APP_NAME="Transferências Light"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Banco de Dados
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# APIs Externas
AUTHORIZATION_API_URL=https://util.devi.tools/api/v2/authorize
NOTIFICATION_API_URL=https://util.devi.tools/api/v1/notify
```

### Configuração de Banco de Dados

**SQLite (Padrão):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

**MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=transferencias_light
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

**PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=transferencias_light
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

### Configuração de Email

Para notificações reais, configure o email:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="Transferências Light"
```

## 🚨 Solução de Problemas

### Problemas Comuns

#### 1. Erro de Permissão
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage
sudo chmod -R 755 storage

# Windows (PowerShell como Administrador)
icacls storage /grant Everyone:F /T
```

#### 2. Erro de Chave da Aplicação
```bash
php artisan key:generate
```

#### 3. Erro de Banco de Dados
```bash
# Recriar banco
php artisan migrate:fresh --seed

# Ou apenas resetar
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

#### 4. Erro de Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 5. Erro de Assets
```bash
npm run build
# ou para desenvolvimento
npm run dev
```

### Problemas com Docker

#### 1. Container não inicia
```bash
# Verificar logs
docker-compose logs app

# Reconstruir containers
docker-compose down
docker-compose up --build -d
```

#### 2. Banco de dados não conecta
```bash
# Verificar se o container do banco está rodando
docker-compose ps

# Reiniciar apenas o banco
docker-compose restart db
```

#### 3. Permissões no Docker
```bash
# Ajustar permissões
docker-compose exec app chown -R www-data:www-data storage
docker-compose exec app chmod -R 755 storage
```

### Logs e Debugging

#### Verificar Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Docker logs
docker-compose logs -f app
```

#### Modo Debug
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 📊 Dados de Teste

### Usuários Pré-cadastrados

Após executar `php artisan db:seed`, você terá:

**Usuário Comum:**
- Email: `usuario@teste.com`
- Senha: `password`
- CPF: `123.456.789-00`

**Lojista:**
- Email: `lojista@teste.com`
- Senha: `password`
- CNPJ: `12.345.678/0001-90`

**Admin:**
- Email: `admin@teste.com`
- Senha: `password`

### Saldo Inicial
- Todos os usuários começam com R$ 100,00 de saldo
- Use o sistema de depósito para adicionar mais saldo

## 📁 Estrutura do Projeto

```
transferencias-light/
├── app/
│   ├── Http/Controllers/     # Controllers da aplicação
│   ├── Livewire/            # Componentes Livewire
│   ├── Models/              # Modelos Eloquent
│   ├── Services/            # Serviços de negócio
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Migrações do banco
│   ├── seeders/            # Seeders para dados iniciais
│   └── database.sqlite     # Banco SQLite (criado automaticamente)
├── resources/
│   ├── views/              # Views Blade
│   ├── css/                # Estilos CSS
│   └── js/                 # JavaScript
├── tests/                  # Testes automatizados
├── docker-compose.yml      # Configuração Docker
├── Dockerfile             # Imagem Docker
├── .env.example           # Exemplo de configuração
└── README.md              # Este arquivo
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

### Comandos Úteis

```bash
# Desenvolvimento
php artisan serve              # Iniciar servidor
npm run dev                   # Compilar assets em modo dev
npm run build                 # Compilar assets para produção

# Banco de dados
php artisan migrate           # Executar migrações
php artisan migrate:fresh     # Recriar banco
php artisan db:seed           # Popular banco com dados

# Cache
php artisan cache:clear       # Limpar cache
php artisan config:clear      # Limpar configurações
php artisan route:clear       # Limpar rotas
php artisan view:clear        # Limpar views

# Testes
php artisan test              # Executar testes
php artisan test --coverage   # Testes com cobertura
```

## 📝 Regras de Negócio

1. **Usuários**: Nome completo, CPF/CNPJ, e-mail e senha obrigatórios
2. **Transferências**: Apenas usuários comuns podem enviar dinheiro
3. **Validações**: Saldo suficiente e autorização externa obrigatórias
4. **Transações**: Operações atômicas com rollback em caso de erro
5. **Notificações**: Envio assíncrono para recebedores

## 🚀 Deploy

### Deploy com Docker

1. **Configure o ambiente de produção**
   ```bash
   cp .env.example .env
   # Edite o .env com configurações de produção
   ```

2. **Inicie os containers**
   ```bash
   docker-compose up -d
   ```

3. **Execute as migrações**
   ```bash
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan db:seed
   ```

### Deploy Tradicional

1. **Configure o ambiente de produção no `.env`**
2. **Instale dependências**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   ```
3. **Configure cache**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. **Configure o servidor web (Apache/Nginx)**

## 🎯 Roadmap

### Próximas Funcionalidades
- [ ] Sistema de notificações push
- [ ] Relatórios avançados
- [ ] API REST completa
- [ ] App mobile
- [ ] Integração com PIX
- [ ] Sistema de cashback
- [ ] Transferências agendadas

### Melhorias Técnicas
- [ ] Testes de performance
- [ ] Monitoramento de logs
- [ ] CI/CD pipeline
- [ ] Documentação da API
- [ ] Métricas de uso

## 🤝 Contribuição

1. **Fork o projeto**
2. **Crie uma branch para sua feature**
   ```bash
   git checkout -b feature/nova-funcionalidade
   ```
3. **Commit suas mudanças**
   ```bash
   git commit -m 'Adiciona nova funcionalidade'
   ```
4. **Push para a branch**
   ```bash
   git push origin feature/nova-funcionalidade
   ```
5. **Abra um Pull Request**

### Padrões de Código
- Siga o PSR-12 para PHP
- Use nomes descritivos para variáveis e funções
- Adicione testes para novas funcionalidades
- Documente mudanças importantes

## 📞 Suporte

### Problemas Conhecidos
- Consulte a seção [Solução de Problemas](#-solução-de-problemas)
- Verifique os [Issues](https://github.com/seu-usuario/transferencias-light/issues)

### Contato
- **Email**: suporte@transferencias-light.com
- **GitHub**: [Issues](https://github.com/seu-usuario/transferencias-light/issues)
- **Documentação**: [Wiki](https://github.com/seu-usuario/transferencias-light/wiki)

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 🎉 Agradecimentos

- **Laravel** - Framework PHP
- **Livewire** - Componentes reativos
- **TailwindCSS** - Framework CSS
- **AlpineJS** - Framework JavaScript
- **Spatie** - Pacotes PHP úteis

---

**Desenvolvido com ❤️ para facilitar transferências financeiras**
