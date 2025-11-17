#!/bin/sh
set -eu
cd /var/www/html

# Setup directories
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache public/build

# Install Composer dependencies
[ -f composer.json ] && [ ! -d vendor ] && (composer install --no-interaction --prefer-dist --no-progress || composer update --no-interaction --prefer-dist --with-all-dependencies) && composer dump-autoload -o || true

# Build Vite assets
if [ "${PRIMARY:-0}" = "1" ] && [ -f package.json ] && [ ! -f public/build/manifest.json ]; then
  (npm ci || npm i) && npm run build || true
fi

# Generate APP_KEY if empty
if [ -f artisan ]; then
  CURRENT_KEY=$(grep "^APP_KEY=" .env | cut -d'=' -f2 || echo "")
  if [ -z "$CURRENT_KEY" ]; then
    APP_KEY="base64:$(openssl rand -base64 32)"
    APP_KEY_ESCAPED=$(printf '%s\n' "$APP_KEY" | sed 's:[\/&]:\\&:g')
    sed -i "s/^APP_KEY=.*/APP_KEY=$APP_KEY_ESCAPED/" .env
  fi
fi

# Run migrations on first start
if [ -f artisan ] && [ ! -f storage/.migrated ]; then
  php artisan migrate:fresh --seed --force || true
  touch storage/.migrated
fi

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache public/build || true
chmod -R ug+rwX storage bootstrap/cache public/build || true

exec php-fpm
