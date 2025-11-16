
# Use an official PHP + Apache base image
FROM php:8.2-apache

# Copy all website files into the web root
COPY ampfarisaho /var/www/html/

# Install MySQL extension for PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Give proper permissions to Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]

