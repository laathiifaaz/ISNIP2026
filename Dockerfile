FROM php:8.2-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Configure Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Setup SQLite Database & Asset Compilation
RUN touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache database \
    && npm install && npm run build

# Run migrations during container startup
CMD php artisan migrate --force && apache2-foreground