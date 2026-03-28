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

# Clear stale package/config caches before any Artisan command.
# This avoids boot failures when cached providers reference missing packages.
rm -f /var/www/html/bootstrap/cache/*.php 2>/dev/null || true

# Ensure dependencies are available. In this dev environment we need dev packages
# (e.g., nunomaduro/collision) for Artisan and tests to boot reliably.
if [ ! -f "/var/www/html/vendor/autoload.php" ] || [ ! -f "/var/www/html/vendor/nunomaduro/collision/src/Adapters/Laravel/CollisionServiceProvider.php" ]; then
  echo "Installing composer dependencies (with dev packages)..."
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

