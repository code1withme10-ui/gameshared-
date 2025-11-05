# Use official PHP + Apache image
FROM php:8.2-apache

# Copy main index.html from root folder
COPY index.html /var/www/html/

# Copy everything from takalani folder into Apache root
COPY takalani/ /var/www/html/

# Enable Apache rewrite module (optional but useful)
RUN a2enmod rewrite

# Expose web port
EXPOSE 80
