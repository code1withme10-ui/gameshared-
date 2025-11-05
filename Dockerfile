# Use official PHP with Apache (We use the team's newer 8.2 version)
FROM php:8.2-apache

 HEAD
# CRITICAL: Install MySQL extension for database connectivity
RUN docker-php-ext-install pdo pdo_mysql 

# Copy contents of your project directory into the container
# NOTE: Ensure 'tshwarelo/' is the correct path for YOUR project files
COPY tshwarelo/ /var/www/html/

# Enable index.php as the default and set ServerName
RUN echo "<?IfModule dir_module>\nDirectoryIndex index.html index.php</IfModule>\n" > /etc/apache2/conf-available/dir.conf \
    && a2enconf dir
WORKDIR /var/www/html/
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose Apache default port
EXPOSE 80

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
