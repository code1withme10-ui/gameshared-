FROM php:8.1-apache

# --- START: Add these lines to install the MySQL driver ---
RUN docker-php-ext-install pdo pdo_mysql
# --- END: Add these lines to install the MySQL driver ---

COPY . /var/www/html/