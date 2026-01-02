FROM php:8.2-apache


RUN docker-php-ext-install pdo pdo_mysql


COPY ampfarisaho /var/www/html/


RUN chmod -R 777 /var/www/html/data /var/www/html/uploads

RUN a2enmod rewrite

EXPOSE 80
