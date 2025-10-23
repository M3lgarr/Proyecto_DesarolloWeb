# Etapa vendor (Composer)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist

# App PHP + Apache
FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql \
 && a2enmod rewrite

# DocumentRoot -> /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
  /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor /var/www/html/vendor

# carpeta de logs (por tu Monolog)
RUN mkdir -p /var/www/html/logs && chown -R www-data:www-data /var/www/html

EXPOSE 80
