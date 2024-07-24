# Use the official PHP image with Apache
FROM php:8.2-apache

# Set the ServerName directive
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable mod_rewrite
RUN a2enmod rewrite

# Update Apache configuration to allow .htaccess overrides
RUN sed -i -e 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Install additional system dependencies if needed
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        libzip-dev \
        unzip \
        git \
        mariadb-client     # Install MySQL client package

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html/ExpensesTracker

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html/ExpensesTracker \
    && chmod -R 755 /var/www/html/ExpensesTracker

# Copy entrypoint script
COPY myStartupScript.sh /usr/local/bin/myStartupScript.sh

# Make entrypoint script executable
RUN chmod +x /usr/local/bin/myStartupScript.sh

# Expose port 80 (optional, depends on your use case)
EXPOSE 80

# Specify the entry point command
#ENTRYPOINT ["/usr/local/bin/myStartupScript.sh"]
