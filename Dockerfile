# Use the official lightweight PHP image
FROM php:8.2-cli

# Install mysqli and pdo_mysql extensions for MySQL support
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /app

# Copy project files into the container
COPY . /app

# Expose port for Render
EXPOSE 10000

# Start PHP's built-in web server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
