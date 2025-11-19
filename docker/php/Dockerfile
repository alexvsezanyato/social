FROM php:8.4.14-fpm

RUN apt update
RUN apt install -y libpq-dev
RUN docker-php-ext-install pdo_pgsql pgsql
COPY --from=composer:2.9.1 /usr/bin/composer /usr/local/bin/composer