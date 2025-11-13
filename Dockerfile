
FROM php:8.2-apache
RUN docker-php-install mysqli
COPY ampfarisaho /var/www/html/
EXPOSE 80
