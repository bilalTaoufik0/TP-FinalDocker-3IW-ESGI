#!/bin/sh
set -eu
cd /var/www/html

# Dossiers indispensables
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache public/build

# Composer (si vendor absent)
if [ -f composer.json ] && [ ! -d vendor ]; then
  echo "[start] composer install/update..."
  composer install --no-interaction --prefer-dist --no-progress \
  || composer update --no-interaction --prefer-dist --with-all-dependencies
  composer dump-autoload -o || true
fi

# Build Vite si manifest absent (uniquement sur le primaire)
if [ "${PRIMARY:-0}" = "1" ] && [ -f package.json ] && [ ! -f public/build/manifest.json ]; then
  echo "[start] build vite (npm ci || npm i) && npm run build"
  node -v && npm -v
  (npm ci || npm i)
  npm run build
  ls -lah public/build || true
else
  echo "[start] build vite ignoré (PRIMARY=${PRIMARY:-0}, manifest=$( [ -f public/build/manifest.json ] && echo ok || echo absent ))"
fi

# Génère la clé uniquement si APP_KEY est vide (ligne "APP_KEY=")
if [ -f artisan ] && grep -q '^APP_KEY=$' .env; then
  php artisan key:generate --force || true
fi


# Migrations (premier run)
if [ -f artisan ] && [ ! -f storage/.migrated ]; then
  echo "[start] artisan migrate:fresh --seed"
  php artisan migrate:fresh --seed --force || true
  touch storage/.migrated
fi

# Permissions
chown -R www-data:www-data storage bootstrap/cache public/build || true
chmod -R ug+rwX storage bootstrap/cache public/build || true

exec php-fpm
