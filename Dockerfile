FROM php:8.2-apache
COPY ampfarisaho /var/www/html/
EXPOSE 80
RUN docker-php-ext-install mysqli
