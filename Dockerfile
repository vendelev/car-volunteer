FROM php:8.3.6-fpm-alpine3.19

RUN apk update \
    && apk add libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -frv /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /vaw/www