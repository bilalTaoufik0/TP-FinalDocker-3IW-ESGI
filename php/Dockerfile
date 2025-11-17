# PHP 8.1 (compatible avec tes deps composer)
FROM php:8.1-fpm-alpine

# Node + npm + runtime C++ pour Node
RUN set -eux; \
    apk add --no-cache nodejs npm libstdc++ libgcc; \
    docker-php-ext-install pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Start script
COPY start.sh /usr/local/bin/start.sh
RUN sed -i 's/\r$//' /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["start.sh"]
