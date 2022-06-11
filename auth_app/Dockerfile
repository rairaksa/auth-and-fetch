FROM composer:2.2 as vendor

WORKDIR /app

COPY database/ database/

COPY composer.json composer.json
# COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

WORKDIR /app

RUN mkdir -p ./public/js
RUN mkdir -p ./public/css

COPY resources/js ./resources/js
COPY resources/sass ./resources/sass
COPY resources/views ./resources/views
COPY public/vendors ./public/vendors

RUN npm install && npm run production

#
# Application
#
FROM php:8.0.13-fpm-alpine3.15

ARG WWWGROUP=33
ARG ENV_FILE=.env.production

LABEL maintainer="Rai Raksa M <rairaksa7@gmail.com>"

WORKDIR /var/www

COPY . ./
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/js ./public/js
COPY --from=frontend /app/public/css ./public/css
COPY --from=frontend /app/public/mix-manifest.json ./public/mix-manifest.json

RUN rm -f .env && cp $ENV_FILE .env

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd imap pdo_mysql zip bcmath soap intl msgpack igbinary pcntl redis

RUN apk add --update openssl nginx supervisor

RUN php artisan package:discover

COPY ./docker/php/php.ini /usr/local/etc/php/99-php.ini

COPY ./docker/php/z-www.conf /usr/local/etc/php-fpm.d/www.conf

COPY ./docker/nginx/conf.d/nginx.conf /etc/nginx/nginx.conf

COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

RUN adduser -S $WWWGROUP -G www-data

RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www

COPY ./docker/start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

ENTRYPOINT ["start-container"]
