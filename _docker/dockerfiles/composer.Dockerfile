FROM composer:latest

RUN mkdir -p /var/www/laravel
WORKDIR /var/www/laravel
RUN adduser -D user && chown -R user /var/www/laravel
USER user

ENTRYPOINT ["composer", "--ignore-platform-reqs"]