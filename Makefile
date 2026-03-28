.PHONY: help up down build rebuild shell migrate seed logs restart clean tinker dev-up dev-down dev-build prod-up prod-down prod-build prod-logs prod-migrate

DEV_COMPOSE=docker compose -f docker-compose.yml --profile dev
PROD_COMPOSE=docker compose -f docker-compose.yml -f docker-compose.prod.yml

help:
	@echo "JobFunnel - Available commands:"
	@echo "  make up          - Start all containers"
	@echo "  make down        - Stop all containers"
	@echo "  make build       - Build and start all containers"
	@echo "  make rebuild     - Force rebuild and start containers"
	@echo "  make dev-up      - Start developer environment"
	@echo "  make dev-down    - Stop developer environment"
	@echo "  make dev-build   - Rebuild developer environment"
	@echo "  make prod-up     - Start production-mode environment"
	@echo "  make prod-down   - Stop production-mode environment"
	@echo "  make prod-build  - Rebuild production-mode environment"
	@echo "  make prod-logs   - Show production logs"
	@echo "  make prod-migrate - Run production migrations"
	@echo "  make shell       - Access PHP container shell"
	@echo "  make migrate     - Run database migrations"
	@echo "  make seed        - Run database seeding"
	@echo "  make logs        - Show container logs"
	@echo "  make restart     - Restart all containers"
	@echo "  make clean       - Remove containers, volumes, and images"
	@echo "  make tinker      - Access Laravel Tinker (interactive shell)"
	@echo ""
	@echo "Developer URLs:"
	@echo "  App             - http://localhost:8080"
	@echo "  Vite            - http://localhost:5173"
	@echo "  Adminer         - http://localhost:8081"

up:
	$(DEV_COMPOSE) up -d
	@echo "✓ Containers started. App: http://localhost:8080 | Adminer: http://localhost:8081"

down:
	$(DEV_COMPOSE) down

build:
	$(DEV_COMPOSE) up -d --build
	sleep 5
	$(DEV_COMPOSE) exec -T php composer install --no-interaction || true
	$(DEV_COMPOSE) exec -T php php artisan key:generate || true
	$(DEV_COMPOSE) exec -T php php artisan migrate --force || true
	@echo "✓ Application built and started. Adminer: http://localhost:8081"

rebuild:
	$(DEV_COMPOSE) down -v
	$(DEV_COMPOSE) up -d --build
	$(DEV_COMPOSE) exec -T php php artisan key:generate || true
	$(DEV_COMPOSE) exec -T php php artisan migrate --force || true
	@echo "✓ Application rebuilt"

dev-up: up
dev-down: down
dev-build: build

prod-up:
	$(PROD_COMPOSE) up -d
	@echo "✓ Production-mode containers started"

prod-down:
	$(PROD_COMPOSE) down

prod-build:
	$(PROD_COMPOSE) up -d --build
	sleep 5
	$(PROD_COMPOSE) exec -T php php artisan config:cache || true
	$(PROD_COMPOSE) exec -T php php artisan route:cache || true
	$(PROD_COMPOSE) exec -T php php artisan view:cache || true
	@echo "✓ Production-mode environment built"

prod-logs:
	$(PROD_COMPOSE) logs -f

prod-migrate:
	$(PROD_COMPOSE) exec -T php php artisan migrate --force

shell:
	$(DEV_COMPOSE) exec php sh

migrate:
	$(DEV_COMPOSE) exec php php artisan migrate

seed:
	$(DEV_COMPOSE) exec php php artisan db:seed

logs:
	$(DEV_COMPOSE) logs -f

restart:
	$(DEV_COMPOSE) restart

clean:
	$(DEV_COMPOSE) down -v
	$(PROD_COMPOSE) down -v
	docker system prune -f
	@echo "✓ Cleanup complete"

tinker:
	$(DEV_COMPOSE) exec php php artisan tinker
