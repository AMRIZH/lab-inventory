# Railway Dockerfile for GitHub deployment
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
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install additional tools for debugging
RUN apt-get update && apt-get install -y curl

# Enable Apache modules
RUN a2enmod rewrite headers

# Set global ServerName to suppress Apache warning
RUN echo "ServerName lab-inventory.railway.app" >> /etc/apache2/apache2.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy application files
COPY . /var/www/html

# Create environment file from Railway template if .env doesn't exist
RUN if [ ! -f .env ]; then \
        if [ -f .env.railway ]; then \
            cp .env.railway .env; \
        elif [ -f .env.production ]; then \
            cp .env.production .env; \
        else \
            echo "APP_NAME=Lab Inventory" > .env; \
            echo "APP_ENV=production" >> .env; \
            echo "APP_KEY=" >> .env; \
            echo "APP_DEBUG=false" >> .env; \
        fi \
    fi

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js dependencies and build assets
RUN npm ci && npm run build

# Copy custom Apache configuration
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Create storage and cache directories if they don't exist
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Generate application key and optimize Laravel
RUN php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
