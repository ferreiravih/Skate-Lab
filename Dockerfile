# Usa uma imagem oficial do PHP que já vem com o Apache configurado
# 'cli' não tem o Apache. 'apache' é o ideal para Web Services.
FROM php:8.3-apache 

# Define o diretório de trabalho padrão dentro do container
WORKDIR /var/www/html

# --- 1. Instalação de Extensões PHP ---
# O Composer precisa de 'zip' e outras libs comuns
# O PostgreSQL precisa da extensão 'pdo_pgsql' e 'pgsql'
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-enable pdo_pgsql

# --- 2. Instalação do Composer ---
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- 3. Configuração do Apache ---
# Habilita o módulo rewrite do Apache (necessário para URLs bonitas e roteamento, se você tiver .htaccess)
RUN a2enmod rewrite

# --- 4. Cópia dos Arquivos da Aplicação ---
# Copia o código do seu repositório para o diretório de trabalho do Apache
COPY . /var/www/html

# --- 5. Instalação das Dependências PHP ---
# O composer.json está na raiz, então o comando é executado no WORKDIR
RUN composer install --no-dev
# A pasta 'vendor' é criada e as dependências são instaladas

# O Apache já está configurado para rodar na porta 80 e executar arquivos .php.
# O Render cuida da exposição da porta.