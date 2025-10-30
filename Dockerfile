FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY . /var/www/html/
WORKDIR /var/www/html

RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

# Usa a porta do Render
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

EXPOSE 80
CMD ["apache2-foreground"]
