# Use the official PHP image as the base image
FROM php:7.4-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the application files to the working directory
COPY . .

# Install any necessary dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install mysqli \ 
    && docker-php-ext-enable mysqli

# Expose port 80 for Apache
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
