# Use official PHP + Apache image
FROM php:8.2-apache

# Copy everything in the current folder to Apache web root
COPY . /var/www/html/

# Enable Apache rewrite module (optional but useful)
RUN a2enmod rewrite

# Expose web port
EXPOSE 80

CMD ["apache2-foreground"]