<<<<<<< HEAD
# Use official PHP + Apache image
FROM php:8.2-apache
=======

# Use official PHP CLI image (no Apache)
FROM php:8.2-cli
>>>>>>> 7a102cc337141a3d983b104d76a7dfa5d3283b6d

# Set working directory inside container
WORKDIR /var/www/html

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

<<<<<<< HEAD
# Copy website files into the Apache document root
COPY takalani/ /var/www/html/
=======
# Copy project (optional; for initial build)
COPY ampfarisaho /var/www/
>>>>>>> 7a102cc337141a3d983b104d76a7dfa5d3283b6d

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

<<<<<<< HEAD
# Expose web port
EXPOSE 80

# Start Apache automatically when container runs
CMD ["apache2-foreground"]
=======
# Keep container running — servers started via docker-compose
CMD ["tail", "-f", "/dev/null"]

>>>>>>> 7a102cc337141a3d983b104d76a7dfa5d3283b6d
