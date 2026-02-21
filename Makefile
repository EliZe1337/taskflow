.PHONY: up down setup fresh logs shell install

up:
	docker compose up -d

down:
	docker compose down --remove-orphans

setup:
	cp .env.example .env
	docker compose down --remove-orphans
	docker compose up -d
	@echo "⏳ Ожидаем запуска MySQL (30 секунд)..."
	sleep 30
	docker compose exec app composer install --no-interaction --no-scripts
	docker compose exec app php artisan key:generate
	docker compose exec app composer run-script post-autoload-dump
	docker compose exec app php artisan migrate --seed
	docker compose exec app php artisan storage:link
	@echo ""
	@echo "✅ TaskFlow готов! Откройте http://localhost:8080"

install:
	docker compose exec app composer install --no-interaction --no-scripts
	docker compose exec app php artisan key:generate
	docker compose exec app composer run-script post-autoload-dump
	docker compose exec app php artisan migrate --seed
	docker compose exec app php artisan storage:link

fresh:
	docker compose exec app php artisan migrate:fresh --seed

logs:
	docker compose logs -f app

shell:
	docker compose exec app bash

tinker:
	docker compose exec app php artisan tinker
