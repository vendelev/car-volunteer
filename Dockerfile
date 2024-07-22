FROM php:8.3.6-fpm-alpine3.19

RUN apk update \
    && apk add libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -frv /var/cache/apk/*

RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --update linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .phpize-deps

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www