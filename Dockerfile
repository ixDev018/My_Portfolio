FROM php:8.2-apache

# Install dependencies including libpq-dev for PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql pgsql pdo_mysql gd zip

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Install Node.js for building Vite assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Update Apache Document Root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Force AllowOverride All in Apache config by appending to the end of the file
RUN echo "<Directory ${APACHE_DOCUMENT_ROOT}>\n    AllowOverride All\n</Directory>" >> /etc/apache2/apache2.conf

# Enable PHP errors to show up in Render Logs
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-error-display.ini
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-error-display.ini
RUN echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/docker-php-ext-error-display.ini

# Listen on default Apache port 80. Render will automatically route to this port.
EXPOSE 80

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all project files to container
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build frontend assets
RUN npm install && npm run build

# Ensure storage and bootstrap/cache directories exist and have proper permissions
RUN mkdir -p /var/www/html/storage/framework/views
RUN mkdir -p /var/www/html/storage/framework/cache
RUN mkdir -p /var/www/html/storage/framework/sessions
RUN mkdir -p /var/www/html/storage/logs
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# End of Dockerfile
