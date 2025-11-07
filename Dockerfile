# Use official PHP image with Apache
FROM php:7.4-apache

HEAD
# Copy everything in the current folder to Apache web root
COPY . /var/www/html/

# Enable Apache rewrite module (optional but useful)
=======
# Enable mod_rewrite for URL rewriting
2185b1953e99bf536512579a153d0521d26e92c2
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Set the working directory
WORKDIR /var/www/html/

# Copy the application code into the container
COPY ampfarisaho /var/www/html/

# Expose the port Apache will run on
EXPOSE 80

HEAD
CMD ["apache2-foreground"]
=======
2185b1953e99bf536512579a153d0521d26e92c2
