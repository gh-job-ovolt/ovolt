FROM php:8.3-fpm-alpine AS base

RUN apk update && \
    apk upgrade && \
    apk add bash && \
    apk add --no-cache $PHPIZE_DEPS

RUN apk add zlib-dev libxml2-dev

RUN pecl install $PHPIZE_DEPS ds && \
    docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable ds && \
    docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN apk add icu-dev && docker-php-ext-install intl && docker-php-ext-configure intl

RUN curl -sf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /app
WORKDIR /app

RUN adduser -D -s /bin/bash user
USER user
