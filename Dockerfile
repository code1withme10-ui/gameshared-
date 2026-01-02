# Use official PHP CLI image (no Apache, to be set up below)
FROM php:8.2-apache

# Set non-interactive mode for installation
ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory to the Apache document root
WORKDIR /var/www/html

# Copy project files to the Apache document root
# Assuming 'tshwarelo' is the main directory holding the website files
COPY tshwarelo/ /var/www/html/

# Create the necessary 'uploads' directory
RUN mkdir -p /var/www/html/uploads

# Enable Apache modules (mod_rewrite is essential for clean URLs/routing).
RUN a2enmod rewrite

# Set the default directory index to prioritize index.php over index.html
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

# Set owner/group permissions for the entire web root, including the uploads folder
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (Apache's default)
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]