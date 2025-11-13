<<<<<<< HEAD
# Use official PHP + Apache image
FROM php:8.2-apache

# Set working directory inside container
WORKDIR /var/www/html
=======
# Use official PHP CLI image (no Apache)
FROM php:8.2-cli
>>>>>>> 1342fc20a2d9b908bedd33f3aac9cfb7ae5e8653

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

# Copy website files into the Apache document root
#COPY takalani/ /var/www/html/
#COPY ampfarisaho/ /var/www/html/

<<<<<<< HEAD
# (Optional) Copy another project folder if needed
# COPY ampfarisaho /var/www/
=======
# Copy project (optional; for initial build)
COPY . /var/www/
>>>>>>> 1342fc20a2d9b908bedd33f3aac9cfb7ae5e8653

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose web port
# Expose ports (for each site)
EXPOSE 8040 8041 8042 8043 8044 8045 8046 8047

<<<<<<< HEAD
# Start Apache automatically when container runs
CMD ["apache2-foreground"]
=======
## Start Apache automatically when container runs
#CMD ["apache2-foreground"]

# Keep container running — servers started via docker-compose
CMD ["tail", "-f", "/dev/null"]


>>>>>>> 1342fc20a2d9b908bedd33f3aac9cfb7ae5e8653
