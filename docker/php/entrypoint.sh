#!/bin/bash
set -e

# Install composer dependencies if vendor doesn't exist
if [ ! -d "/var/www/vendor" ]; then
    echo "📦 vendor/ не найден — запускаем composer install..."
    cd /var/www
    composer install --no-interaction --prefer-dist --optimize-autoloader
    echo "✅ Composer зависимости установлены"
fi

# Generate app key if not set
if grep -q "APP_KEY=$" /var/www/.env 2>/dev/null || grep -q "APP_KEY=base64:$" /var/www/.env 2>/dev/null; then
    echo "🔑 Генерируем APP_KEY..."
    php artisan key:generate --force
fi

exec "$@"
