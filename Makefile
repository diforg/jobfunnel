.PHONY: help up down build shell migrate seed logs restart clean

help:
	@echo "JobFunnel - Available commands:"
	@echo "  make up          - Start all containers"
	@echo "  make down        - Stop all containers"
	@echo "  make build       - Build and start all containers"
	@echo "  make rebuild     - Force rebuild and start containers"
	@echo "  make shell       - Access PHP container shell"
	@echo "  make migrate     - Run database migrations"
	@echo "  make seed        - Run database seeding"
	@echo "  make logs        - Show container logs"
	@echo "  make restart     - Restart all containers"
	@echo "  make clean       - Remove containers, volumes, and images"
	@echo "  make tinker      - Access Laravel Tinker (interactive shell)"

up:
	docker-compose up -d
	@echo "✓ Containers started. Access at http://localhost:8080"

down:
	docker-compose down

build:
	docker-compose up -d --build
	sleep 5
	docker-compose exec -T php composer install --no-interaction || true
	docker-compose exec -T php php artisan key:generate || true
	docker-compose exec -T php php artisan migrate --force || true
	@echo "✓ Application built and started"

rebuild:
	docker-compose down -v
	docker-compose up -d --build
	docker-compose exec -T php php artisan key:generate || true
	docker-compose exec -T php php artisan migrate --force || true
	@echo "✓ Application rebuilt"

shell:
	docker-compose exec php sh

migrate:
	docker-compose exec php php artisan migrate

seed:
	docker-compose exec php php artisan db:seed

logs:
	docker-compose logs -f

restart:
	docker-compose restart

clean:
	docker-compose down -v
	docker system prune -f
	@echo "✓ Cleanup complete"

tinker:
	docker-compose exec php php artisan tinker
