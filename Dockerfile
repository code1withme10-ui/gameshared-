# Use PHP + Apache base image
FROM php:8.1-apache

# Copy project files into web root
COPY . /var/www/html/

# Set Apache to serve index.html or index.php
RUN echo "<IfModule dir_module>\n    DirectoryIndex index.html index.php\n</IfModule>" > /etc/apache2/conf-available/dir.conf && \
    a2enconf dir

# Set working directory
WORKDIR /var/www/html/

# Expose port 80
EXPOSE 80
