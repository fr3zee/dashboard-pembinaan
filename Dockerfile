# syntax=docker/dockerfile:1
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

FROM node:20 AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js .
RUN npm run build

FROM php:8.3-apache
WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev libpng-dev libicu-dev unzip \
    && docker-php-ext-install pdo_sqlite zip bcmath intl gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

RUN mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 10000
CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]