FROM php:8.2-apache

# පද්ධතියට අවශ්‍ය ලයිබ්‍රරි ඉන්ස්ටෝල් කිරීම
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# PHP extensions ඉන්ස්ටෝල් කිරීම
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Apache rewrite module එක enable කිරීම
RUN a2enmod rewrite

# Files කොපි කිරීම
COPY . /var/www/html/

# Permissions හැදීම
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Port එක සෙට් කිරීම
ENV PORT=80
EXPOSE 80

# Apache start කිරීම
CMD ["apache2-foreground"]