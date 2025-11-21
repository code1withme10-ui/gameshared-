FROM php:8.2-apache

# Copy project files to the Apache root
COPY ampfarisaho /var/www/html/

# Enable Apache mod_rewrite
RUN a2enmod rewrite


# Enable Apache rewrite
RUN a2enmod rewrite


