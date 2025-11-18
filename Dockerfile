FROM php:8.2-apache

<<<<<<< HEAD
# Copy project files to the Apache root
COPY takalani /var/www/html/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html
=======
# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Allow index.php and index.html
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf
>>>>>>> whole-project-update
