# Use official PHP with Apache
FROM php:8.2-apache

# Copy project files into the container
COPY ampfarisaho/ /var/www/html/

# Expose Apache default port
EXPOSE 80

