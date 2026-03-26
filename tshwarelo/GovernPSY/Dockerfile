# Use PHP with Apache server
FROM php:8.2-apache

# Update the server and install extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# --- OPTION B FIX START ---
# Change the Apache Document Root to point to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# --- OPTION B FIX END ---

RUN a2enmod rewrite
WORKDIR /var/www/html
COPY . /var/www/html/
EXPOSE 80