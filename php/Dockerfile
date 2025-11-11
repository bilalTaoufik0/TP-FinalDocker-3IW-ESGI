# Node 12 (pour npm ci / npm run build)
FROM node:12-alpine AS node12

# PHP-FPM
FROM php:8.3-fpm-alpine

RUN docker-php-ext-install pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 12 dans l'image PHP
COPY --from=node12 /usr/local/bin/node /usr/local/bin/node
COPY --from=node12 /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
 && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

WORKDIR /var/www/html

# Script d'init
COPY start.sh /usr/local/bin/start.sh
RUN sed -i 's/\r$//' /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh
CMD ["start.sh"]


