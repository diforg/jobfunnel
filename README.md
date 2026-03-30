# JobFunnel

Aplicacao Laravel + Inertia + Vue para gestao de candidaturas a vagas.

## Tecnologias

- PHP 8.3 (Laravel 11)
- Vue 3 + Inertia
- PostgreSQL 16
- Redis 7
- Nginx
- Docker Compose

## Ambiente de Desenvolvimento

### 1) Pre-requisitos

- Docker e Docker Compose instalados
- Git instalado

### 2) Clonar o projeto

```bash
git clone https://github.com/diforg/jobfunnel.git
cd jobfunnel
```

### 3) Configurar variaveis de ambiente

```bash
cp .env.example .env
```

No arquivo `.env`, mantenha no minimo:

```dotenv
APP_URL=http://localhost:8080
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=jobfunnel
DB_USERNAME=jobfunnel
DB_PASSWORD=secret
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PORT=6379
```

### 4) Subir containers

Opcao rapida:

```bash
make up
```

Ou com build completo:

```bash
make build
```

### 5) Executar migrations e seed

```bash
make migrate
make seed
```

Usuario padrao do seed:
- Email: `user@user.com.br`
- Senha: `senha123`

### 6) Acessos locais

- Aplicacao: http://localhost:8080
- Vite (dev server): http://localhost:5173
- Adminer (PostgreSQL UI): http://localhost:8081

Conexao no Adminer:
- Sistema: PostgreSQL
- Servidor: `postgres`
- Usuario: valor de `DB_USERNAME`
- Senha: valor de `DB_PASSWORD`
- Base: valor de `DB_DATABASE`

### 7) Testes

```bash
docker compose exec -T php ./vendor/bin/pest --no-coverage
```

## Ambiente de Producao (Azure) - Resumo

Passo a passo completo em [docs/azure-production.md](docs/azure-production.md).

Fluxo recomendado:
- Provisionar VM Linux no Azure
- Instalar Docker e Compose na VM
- Configurar DNS e TLS (Nginx/Reverse Proxy)
- Fazer deploy com `docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build`
- Integrar com GitHub Actions para deploy automatico via SSH a cada push na branch principal

## Comandos uteis (Makefile)

```bash
make help
make up
make down
make build
make rebuild
make prod-up
make prod-down
make prod-build
make prod-migrate
```
