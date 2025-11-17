FROM php:8.2-apache

# Copy project files to the Apache root
COPY takalani /var/www/html/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html
