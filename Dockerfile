FROM php:8.2-apache

# Instala dependências de sistema e extensões PHP (PostgreSQL + MySQL)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql pgsql

# Copia o projeto para o diretório padrão do Apache
COPY . /var/www/html/

WORKDIR /var/www/html

# Habilita o mod_rewrite (para .htaccess e rotas amigáveis)
RUN a2enmod rewrite

# Corrige permissões
RUN chown -R www-data:www-data /var/www/html

# Define ServerName e força DocumentRoot para /home
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/home\n\
    <Directory /var/www/html/home>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        DirectoryIndex index.php index.html\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Atualiza o arquivo de portas (Render usa porta dinâmica)
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

# Define variáveis de ambiente e porta
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
EXPOSE 80

CMD ["apache2-foreground"]
