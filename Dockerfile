FROM php:8.2-apache

# Instala dependências e drivers do PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql pgsql

# Copia o projeto
COPY . /var/www/html/

WORKDIR /var/www/html

# Habilita mod_rewrite
RUN a2enmod rewrite

# Permissões corretas
RUN chown -R www-data:www-data /var/www/html

# Configura Apache para aceitar .htaccess e index.php
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    echo '<Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        DirectoryIndex index.php index.html\n\
    </Directory>' >> /etc/apache2/apache2.conf

# Substitui a porta padrão
RUN sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf

EXPOSE 80
CMD ["apache2-foreground"]
