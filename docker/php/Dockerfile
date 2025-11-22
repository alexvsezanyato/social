FROM php:8.4.14-fpm

RUN apt update
RUN apt install -y libzip-dev libpq-dev
RUN docker-php-ext-install zip pdo_pgsql pgsql
COPY --from=composer:2.9.1 /usr/bin/composer /usr/local/bin/composer
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN groupadd -g 1000 app
RUN useradd -u 1000 -s /bin/sh -g app app
USER app