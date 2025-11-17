# Node 12 (consigne)
FROM node:12-alpine AS node12

# PHP-FPM **8.1** (compatible avec tes deps)
FROM php:8.1-fpm-alpine

RUN docker-php-ext-install pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 12 dans l'image (pour npm si besoin)
COPY --from=node12 /usr/local/bin/node /usr/local/bin/node
COPY --from=node12 /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
 && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

WORKDIR /var/www/html

COPY start.sh /usr/local/bin/start.sh
RUN sed -i 's/\r$//' /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["start.sh"]
