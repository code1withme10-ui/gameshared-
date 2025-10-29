# Use the official PHP + Apache image
FROM php:8.1-apache

# Copy all project files into the Apache web root
COPY . /var/www/html/

# Expose port 80 so it’s accessible from your browser
EXPOSE 80

# Enable common PHP extensions (optional)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
