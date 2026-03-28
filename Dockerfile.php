FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    autoconf \
    automake \
    bash \
    build-base \
    curl \
    git \
    libpq \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-client \
    supervisor \
    unzip

# Install PHP extensions one by one to debug issues
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install xml
RUN docker-php-ext-install zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure git to allow this directory (fix for composer)
RUN git config --global --add safe.directory /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Create storage and bootstrap/cache directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install PHP dependencies (include dev packages for local development/test tooling)
RUN composer install --no-interaction --optimize-autoloader 2>&1 || true

# Create non-root user
RUN addgroup -g 1000 laravel && adduser -D -u 1000 -G laravel laravel

# Copy entrypoint script as root first
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint && \
    mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache public && \
    chown -R laravel:laravel /var/www/html

USER laravel

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]
CMD ["php-fpm"]
