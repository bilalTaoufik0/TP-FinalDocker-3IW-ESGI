#!/bin/sh
set -eu
cd /var/www/html || exit 0

# Créer les dossiers nécessaires
mkdir -p storage/logs storage/framework/{cache,sessions,views} \
         bootstrap/cache public/build

# Installer les dépendances Composer
if [ -f composer.json ] && [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist || \
    composer update --no-interaction --with-all-dependencies || true
    composer dump-autoload -o || true
fi

# Build des assets Vite (seulement sur le conteneur principal)
if [ "${PRIMARY:-0}" = "1" ] && [ -f package.json ] && [ ! -f public/build/manifest.json ]; then
    npm ci || npm i
    npm run build || true
fi

# Générer APP_KEY si absent
if [ -f artisan ] && [ -f .env ]; then
    if ! grep -q "^APP_KEY=.\+" .env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=base64:$(openssl rand -base64 32)|" .env
    fi
fi

# Migrations (une seule fois)
if [ -f artisan ] && [ ! -f storage/.migrated ]; then
    php artisan migrate --force || true
    touch storage/.migrated
fi

# Permissions
chown -R www-data:www-data storage bootstrap/cache public/build 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache public/build 2>/dev/null || true

exec php-fpm