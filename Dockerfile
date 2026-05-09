# PHP 8.3 වලට මාරු කළා
FROM php:8.3-apache

# 1. පද්ධතියට අවශ්‍ය ලයිබ්‍රරි ඉන්ස්ටෝල් කිරීම
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl

# 2. PHP extensions ඉන්ස්ටෝල් කිරීම
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

# 3. Apache rewrite module එක enable කිරීම
RUN a2enmod rewrite

# 4. Apache Document Root එක Laravel public ෆෝල්ඩරයට වෙනස් කිරීම
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Composer ඉන්ස්ටෝල් කිරීම
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. ප්‍රොජෙක්ට් එක සර්වර් එකට කොපි කිරීම
WORKDIR /var/www/html
COPY . .

# 7. Dependencies ඉන්ස්ටෝල් කිරීම (මෙහිදී platform requirements වලට බලපෑම් කරන්න ඉඩ දෙන්න එපා)
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# 8. Permissions නිවැරදි කිරීම
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
