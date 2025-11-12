# Use official PHP CLI image (no Apache)
FROM php:8.2-cli

# Set working directory inside container
WORKDIR /var/www/html

# Copy everything inside takalani/ to Apache's web root
COPY takalani/ /var/www/html/

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
    && docker-php-ext-install gd pdo pdo_mysql mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

# Copy project (optional; for initial build)
COPY . /var/www/

# Expose ports (for each site)
EXPOSE 8000 8001 8002 8003 8004

# Keep container running — servers started via docker-compose
CMD ["tail", "-f", "/dev/null"]
