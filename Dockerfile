# Use official PHP + Apache image
FROM php:8.2-apache

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
