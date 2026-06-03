FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts

FROM php:8.2-cli

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=vendor /app/vendor /var/www/html/vendor
COPY . /var/www/html

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
