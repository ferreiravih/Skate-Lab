
FROM php:8.2-apache

# Atualiza o sistema e instala dependências necessárias
# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copia os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Define o diretório de trabalho
WORKDIR /var/www/html

# Habilita mod_rewrite (necessário para .htaccess e rotas amigáveis)
RUN a2enmod rewrite

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Cria um novo VirtualHost apontando para /home
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
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

# Render usa uma porta dinâmica -> configuramos o Apache pra usá-la
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

EXPOSE 80
# Inicia o Apache em primeiro plano
CMD ["apache2-foreground"]
