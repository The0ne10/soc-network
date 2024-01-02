FROM php:8.2-fpm-alpine

RUN mkdir -p /var/www/laravel
RUN docker-php-ext-install pdo pdo_mysql
RUN apk update && apk add nodejs && apk add npm


RUN adduser -D user && chown -R user /var/www/laravel
USER user

WORKDIR /var/www/laravel






