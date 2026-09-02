# ---
# Build Stage - Install and compile extensions
# ---
FROM php:8.2-apache AS build

# Install build dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libzip-dev \
    && docker-php-ext-install zip mysqli pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---
# Production Stage
# ---
FROM php:8.2-apache AS production

LABEL maintainer="NatureCAN Team"
LABEL description="NatureCAN Medicinal Plants Database"

# Install runtime dependencies only
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libzip5 \
        curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

# Copy installed extensions from the build stage
COPY --from=build /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

# Enable PHP extensions and Apache modules
RUN docker-php-ext-enable zip mysqli pdo pdo_mysql && \
    a2enmod headers rewrite

# Remove default index.html
RUN rm -rf /var/www/html/*

# Copy secured php.ini (production version)
COPY app/php.ini /usr/local/etc/php/

# Copy secured Apache config
COPY config/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copy Apache security configuration (optional - skip if missing)
COPY config/security.conf /etc/apache2/conf-available/security.conf
RUN a2enconf security || true

# Copy source code (excluding unnecessary files via .dockerignore)
COPY --chown=www-data:www-data app/src/ /var/www/html/

# Remove archive, test, and unused files in production build
RUN rm -rf /var/www/html/_archive /var/www/html/test_*.php \
    /var/www/html/db_config_test_working_fine.php \
    /var/www/html/Nconnect.php \
    /var/www/html/view_data.php \
    /var/www/html/"add this in table counter.php" \
    /var/www/html/Unique_Count.py \
    /var/www/html/tables/*.xlsx || true

# Set working directory
WORKDIR /var/www/html

# Create required directories with proper permissions
RUN mkdir -p /var/www/html/uploads \
    && mkdir -p /var/lib/php/sessions \
    && mkdir -p /var/run/apache2 \
    && mkdir -p /var/lock/apache2 \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/php/sessions \
    && chown -R www-data:www-data /var/run/apache2 \
    && chown -R www-data:www-data /var/lock/apache2 \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/uploads \
    && chmod -R 700 /var/lib/php/sessions

# Expose only the port used by Apache
EXPOSE 80

# Healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl -f http://localhost/ || exit 1
