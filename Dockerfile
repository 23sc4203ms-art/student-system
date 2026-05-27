FROM php:8.2-fpm

ARG DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        pkg-config \
        ca-certificates \
        git \
        unzip \
        curl \
        zip \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libcurl4-openssl-dev \
        libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (required for building assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip mbstring exif bcmath intl curl

# Install Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application files
COPY . .

# Create .env file from .env.example for build time
RUN if [ ! -f .env ]; then cp .env.example .env || echo "APP_KEY=" > .env; fi

# Install PHP dependencies with environment variables set
# The key flag is --no-scripts. This prevents Composer from running @php artisan package:discover,
# which is the source of the error.
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction --prefer-dist --no-progress

# Generate APP_KEY if not set
RUN php artisan key:generate --force || true

# Run scripts after environment is ready
RUN composer dump-autoload --optimize --no-interaction

# Install Node dependencies and build frontend assets
# Use --legacy-peer-deps to bypass Vite/@tailwindcss/vite peer dependency conflicts
RUN npm install --legacy-peer-deps && npm run build

# Cache configuration for faster startup
RUN php artisan config:cache && php artisan route:cache

# Create necessary directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www

EXPOSE 8000

ENV PORT=8000
ENV APP_ENV=production
ENV APP_DEBUG=false

# Start the application with proper production setup
CMD php artisan migrate --force && \
    php artisan db:seed --class=AdminUserSeeder --force --no-interaction && \
    php -S 0.0.0.0:${PORT} -t public/