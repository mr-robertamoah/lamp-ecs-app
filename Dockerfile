FROM php:7.4-apache

# Install mysqli and other dependencies
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY ./app/ /var/www/html/
EXPOSE 80