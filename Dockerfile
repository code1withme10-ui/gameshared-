FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Allow index.php and index.html
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf
