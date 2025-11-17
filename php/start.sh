#!/bin/sh
set -eu
cd /var/www/html

# Composer (si vendor absent)
if [ -f composer.json ] && [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist --no-progress \
  || composer update --no-interaction --prefer-dist --with-all-dependencies || true
fi


# Génère la clé si manquante
if [ -f artisan ] && ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force || true
fi

# Premier run : migrations + seed (une fois)
if [ -f artisan ] && [ ! -f storage/.migrated ]; then
  php artisan migrate:fresh --seed --force || true
  mkdir -p storage && touch storage/.migrated
fi

# Permissions (best effort)
mkdir -p storage bootstrap/cache
chmod -R ug+rw storage/bootstrap/cache || true
chmod -R ug+rw storage bootstrap/cache || true

exec php-fpm
