# Use PHP + Apache base image
FROM php:8.1-apache

# Copy everything from your current folder (gameshared) into Apache root
COPY tshwarelo /var/www/html/


# Enable index.php as the default
RUN echo "<IfModule dir_module>\n    DirectoryIndex index.html index.php\n</IfModule>" > /etc/apache2/conf-available/dir.conf && \
    a2enconf dir

# Set working directory
WORKDIR /var/www/html/

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


EXPOSE 80
