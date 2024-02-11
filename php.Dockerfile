# Dockerfile

FROM php:8.2-apache

WORKDIR /var/www/html

# Install Composer
RUN apt-get update && \
    apt-get install -y unzip && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Set DocumentRoot to public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite for URL rewrite
RUN a2enmod rewrite

CMD ["apache2-foreground"]
