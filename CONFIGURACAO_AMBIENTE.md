# ⚙️ Configuração de Ambiente - Transferências Light

## 📋 Pré-requisitos

### Para Instalação Local
- **PHP**: 8.2 ou superior
- **Composer**: Última versão
- **Node.js**: 18+ e NPM
- **Banco de Dados**: SQLite (padrão), MySQL ou PostgreSQL

### Para Docker
- **Docker**: 20.10+
- **Docker Compose**: 2.0+

## 🔧 Configuração do .env

### Configurações Básicas

```env
# Aplicação
APP_NAME="Transferências Light"
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

# Chave da aplicação (gerada automaticamente)
APP_KEY=base64:sua_chave_aqui
```

### Configuração de Banco de Dados

#### SQLite (Padrão - Mais Fácil)
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

#### MySQL
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=transferencias_light
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

#### PostgreSQL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=transferencias_light
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

### Configuração de Email

#### Para Desenvolvimento (Log)
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Para Produção (SMTP)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Configuração de Cache

#### Redis (Recomendado)
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Database (Padrão)
```env
CACHE_STORE=database
```

### Configuração de Sessão

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

### APIs Externas

```env
# APIs de autorização e notificação
AUTHORIZATION_API_URL=https://util.devi.tools/api/v2/authorize
NOTIFICATION_API_URL=https://util.devi.tools/api/v1/notify
```

## 🐳 Configuração Docker

### docker-compose.yml

O arquivo `docker-compose.yml` já está configurado com:

- **App**: PHP 8.2 + Laravel
- **Nginx**: Servidor web
- **MySQL**: Banco de dados
- **Redis**: Cache e sessões

### Comandos Docker Úteis

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app

# Executar comandos no container
docker-compose exec app php artisan migrate
docker-compose exec app composer install

# Reconstruir containers
docker-compose up --build -d
```

## 🔍 Verificação da Instalação

### 1. Verificar PHP
```bash
php --version
# Deve mostrar PHP 8.2+
```

### 2. Verificar Composer
```bash
composer --version
# Deve mostrar Composer 2.0+
```

### 3. Verificar Node.js
```bash
node --version
# Deve mostrar Node 18+
npm --version
# Deve mostrar NPM 8+
```

### 4. Verificar Banco de Dados

#### SQLite
```bash
ls -la database/database.sqlite
# Arquivo deve existir
```

#### MySQL
```bash
mysql -u root -p -e "SHOW DATABASES;"
# Deve listar as databases
```

#### PostgreSQL
```bash
psql -U postgres -l
# Deve listar as databases
```

## 🚨 Solução de Problemas

### Erro: "Class 'PDO' not found"
```bash
# Ubuntu/Debian
sudo apt-get install php-pdo php-mysql

# CentOS/RHEL
sudo yum install php-pdo php-mysql

# macOS (Homebrew)
brew install php@8.2
```

### Erro: "SQLite3 not found"
```bash
# Ubuntu/Debian
sudo apt-get install php-sqlite3

# CentOS/RHEL
sudo yum install php-pdo php-sqlite3

# macOS (Homebrew)
brew install php@8.2
```

### Erro: "Composer not found"
```bash
# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Erro: "Node.js not found"
```bash
# Instalar Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

## 📊 Monitoramento

### Logs da Aplicação
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Docker logs
docker-compose logs -f app
```

### Status dos Serviços
```bash
# Verificar containers
docker-compose ps

# Verificar recursos
docker stats
```

## 🔒 Segurança

### Configurações de Produção

```env
# Produção
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

# Banco de dados seguro
DB_PASSWORD=senha_super_segura

# Cache e sessões
CACHE_STORE=redis
SESSION_DRIVER=redis
SESSION_ENCRYPT=true

# Email seguro
MAIL_ENCRYPTION=tls
```

### Permissões de Arquivos

```bash
# Ajustar permissões
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

**✅ Configuração concluída! Seu ambiente está pronto para uso.**
