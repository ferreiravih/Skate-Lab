# Usa imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copia os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Define o diretório de trabalho
WORKDIR /var/www/html

# Habilita mod_rewrite (necessário para .htaccess e rotas amigáveis)
RUN a2enmod rewrite

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Define o diretório raiz do Apache para a pasta 'home'
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/home|' /etc/apache2/sites-available/000-default.conf

# Garante que o .htaccess da pasta home funcione
RUN echo "<Directory /var/www/html/home>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Render define a porta dinamicamente — configuramos o Apache pra usá-la
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

EXPOSE 80

# Inicia o Apache
CMD ["apache2-foreground"]
