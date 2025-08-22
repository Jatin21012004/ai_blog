# Use official PHP with Apache
FROM php:8.2-apache

# Install required extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files into Apache root
COPY . /var/www/html/

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
