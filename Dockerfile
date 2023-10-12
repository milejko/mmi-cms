# syntax=docker/dockerfile:1.4

FROM debian:bookworm-slim

RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    php-cli \
    php-ldap \
    php-pdo \
    php-pdo-mysql \
    php-pdo-sqlite \
    php-dom \
    php-bcmath \
    php-xdebug \
    php-gd \
    composer

COPY --link . /app

WORKDIR /app

RUN composer install