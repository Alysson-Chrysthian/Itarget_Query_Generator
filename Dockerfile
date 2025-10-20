FROM composer AS build

COPY ./ /app
RUN composer install
RUN composer update

FROM php:8.4-apache AS app

COPY --from=build /app /var/www/html/

RUN a2enmod rewrite

EXPOSE 80
