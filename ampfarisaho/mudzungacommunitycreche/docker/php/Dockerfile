FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy project files into container
COPY . /var/www/html

# Fix permissions for uploads & storage
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose Apache port
EXPOSE 80
