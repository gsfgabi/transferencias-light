# 🚀 Instalação Rápida - Transferências Light

## ⚡ Instalação em 5 Minutos

### Opção 1: Com Docker (Mais Fácil)

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/transferencias-light.git
cd transferencias-light

# 2. Configure o ambiente
cp .env.example .env

# 3. Inicie os containers
docker-compose up -d

# 4. Execute as migrações
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# 5. Acesse a aplicação
# URL: http://localhost:8000
```

### Opção 2: Instalação Local

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/transferencias-light.git
cd transferencias-light

# 2. Instale as dependências
composer install
npm install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Crie o banco SQLite
touch database/database.sqlite

# 5. Execute as migrações
php artisan migrate
php artisan db:seed

# 6. Compile os assets
npm run build

# 7. Inicie o servidor
php artisan serve

# 8. Acesse a aplicação
# URL: http://localhost:8000
```

## 🔑 Dados de Acesso

Após a instalação, use estes dados para testar:

**Usuário Comum:**
- Email: `usuario@teste.com`
- Senha: `password`

**Lojista:**
- Email: `lojista@teste.com`
- Senha: `password`

**Admin:**
- Email: `admin@teste.com`
- Senha: `password`

## 🆘 Problemas Comuns

### Erro de Permissão
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage
sudo chmod -R 755 storage

# Windows (PowerShell como Administrador)
icacls storage /grant Everyone:F /T
```

### Erro de Chave
```bash
php artisan key:generate
```

### Erro de Banco
```bash
php artisan migrate:fresh --seed
```

### Erro de Assets
```bash
npm run build
```

## 📞 Precisa de Ajuda?

- Consulte o [README completo](README.md)
- Verifique a seção [Solução de Problemas](README.md#-solução-de-problemas)
- Abra uma [Issue](https://github.com/seu-usuario/transferencias-light/issues)

---

**🎉 Pronto! Seu sistema está funcionando!**
