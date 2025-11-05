# Use PHP + Apache base image
FROM php:8.1-apache

# Copy the main index.html from the gameshared folder
COPY ../index.html /var/www/html/

# Copy everything from the takalani folder into Apache root
COPY . /var/www/html/takalani/

# Enable index.php and index.html as default pages
RUN echo "<IfModule dir_module>\n    DirectoryIndex index.php index.html\n</IfModule>" > /etc/apache2/conf-available/dir.conf && \
    a2enconf dir

# Set working directory
WORKDIR /var/www/html/

# Fix Apache warnings and set hostname
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose web server port
EXPOSE 80

