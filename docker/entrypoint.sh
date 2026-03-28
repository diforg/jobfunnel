#!/bin/bash
set -e

# Ensure required directories exist with proper permissions
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache public
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# Wait for database to be ready
echo "Waiting for database..."
until pg_isready -h "${DB_HOST:-postgres}" -U "${DB_USERNAME:-jobfunnel}" > /dev/null 2>&1; do
  echo "Database is unavailable - sleeping..."
  sleep 1
done
echo "Database is ready!"

# Run composer install if vendor doesn't exist
if [ ! -d "/var/www/html/vendor" ]; then
  echo "Installing composer dependencies..."
  composer install --no-interaction --optimize-autoloader
fi

# Only generate app key if APP_KEY is empty in .env
APP_KEY_VALUE=$(grep -E '^APP_KEY=base64:.{40,}' /var/www/html/.env 2>/dev/null | head -1 || true)
if [ -z "$APP_KEY_VALUE" ]; then
  echo "Generating application key..."
  php artisan key:generate --force
else
  echo "Application key already set, skipping."
fi

# Clear cached config to pick up .env changes
php artisan config:clear 2>/dev/null || true

# Create symbolic link for storage (idempotent)
php artisan storage:link --force 2>/dev/null || true

echo "Application is ready!"

exec "$@"

