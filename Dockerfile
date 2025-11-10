# Use official PHP image with Apache
FROM php:8.0-apache

# Enable mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Set the working directory
WORKDIR /var/www/html/

# Copy the application code into the container
COPY ampfarisaho /var/www/html/

# Expose the port Apache will run on
EXPOSE 80


