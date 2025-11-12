# Use the official PHP image with Apache
FROM php:7.4-apache

# Set working directory inside container
WORKDIR /var/www/html

# Copy everything inside takalani/ to Apache's web root
COPY sample/ /var/www/html/

# Enable Apache rewrite module (optional, useful for PHP routes)
RUN a2enmod rewrite

# Fix file permissions (important for JSON read/write)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose web port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
# Install PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Enable Apache mod_rewrite (needed for clean URLs)
RUN a2enmod rewrite

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP application files into the container
COPY . /var/www/html/

# Set permissions for the files (needed for Apache to serve the files)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the web server
EXPOSE 80

# Start Apache when the container is run
CMD ["apache2-foreground"]

