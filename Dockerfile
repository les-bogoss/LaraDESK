FROM composer:2.3.5 as build
WORKDIR /app
COPY . /app
RUN composer update --ignore-platform-req=ext-http
RUN composer install --ignore-platform-req=ext-http

FROM php:8.1.1-apache
RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 80
COPY --from=build /app /var/www/
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .env /var/www/.env
RUN chmod 777 -R /var/www/storage/ && \
    echo "Listen 8080" >> /etc/apache2/ports.conf && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite