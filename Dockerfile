# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache modules
RUN a2enmod rewrite headers

# Install additional tools for debugging
RUN apt-get install -y curl

# Configure Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set global ServerName to suppress Apache warning
RUN echo "ServerName lab-inventory.local" >> /etc/apache2/apache2.conf

# Copy application code
COPY . /var/www/html

# Create a basic .env file for build process if it doesn't exist
RUN if [ ! -f .env ]; then \
        if [ -f .env.production ]; then \
            cp .env.production .env; \
        else \
            echo "APP_NAME=Laravel" > .env; \
            echo "APP_ENV=production" >> .env; \
            echo "APP_KEY=" >> .env; \
            echo "APP_DEBUG=false" >> .env; \
            echo "APP_URL=http://localhost" >> .env; \
            echo "DB_CONNECTION=pgsql" >> .env; \
        fi \
    fi

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy custom Apache configuration
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Create storage and cache directories if they don't exist
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache

# Generate application key only if needed during build
RUN if [ -z "$(grep '^APP_KEY=' .env | cut -d'=' -f2 | tr -d ' ')" ] || [ "$(grep '^APP_KEY=' .env | cut -d'=' -f2 | tr -d ' ')" = "" ]; then \
        php artisan key:generate --force; \
    fi

# Create entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/healthcheck.sh

# Expose port 80
EXPOSE 80

# Use entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
