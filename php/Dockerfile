FROM php:8.1-fpm-alpine
# Install dependencies

RUN apk add --no-cache nodejs npm libstdc++ libgcc && \
    docker-php-ext-install pdo_mysql


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY start.sh /usr/local/bin/start.sh

RUN sed -i 's/\r$//' /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["start.sh"]