#!/bin/bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

if [[ "$BUILD_ENV" != "production" && ! -d "vendor" ]]; then
  composer install --no-interaction --no-progress
fi

if ! grep -q "^APP_KEY=" .env || grep -q "^APP_KEY=$" .env; then
  php artisan key:generate
fi

if ! grep -q "^JWT_SECRET=" .env || grep -q "^JWT_SECRET=$" .env; then
  php artisan jwt:secret -f
fi

if [ "$BUILD_ENV" = "production" ]; then
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi

exec "$@"
