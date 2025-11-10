# Use the official lightweight PHP image from Docker Hub
FROM php:8.2-cli

# Set the working directory inside the container
WORKDIR /app

# Copy all your project files into the container
COPY . /app

# Expose port 10000 for Render
EXPOSE 10000

# Start PHP built-in web server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
