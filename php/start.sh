#!/bin/sh
set -eu

cd /var/www/html

# Dossiers obligatoires
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache

# Dépendances PHP
if [ ! -d vendor ]; then
    composer install --no-interaction
fi

# Générer APP_KEY
if [ -f artisan ] && ! grep -q "^APP_KEY=.\+" .env 2>/dev/null; then
    php artisan key:generate --force
fi

# Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

exec php-fpm