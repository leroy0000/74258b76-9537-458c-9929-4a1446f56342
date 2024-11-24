FROM php:8.3-cli-alpine

WORKDIR /app

RUN apk update && apk add --no-cache \
    unzip \
    git \
    bash \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

RUN chown -R www-data:www-data /app
