FROM php:8.2-apache

# Atualiza a lista de pacotes e instala dependências
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instala extensões necessárias do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install mysqli pdo pdo_mysql gd mbstring xml

# Habilita o mod_rewrite
RUN a2enmod rewrite
RUN a2enmod headers

# Configurações do Apache
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        Order allow,deny\n\
        Allow from all\n\
    </Directory>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Copia o conteúdo do projeto para dentro do container
COPY . /var/www/html/

# Define o diretório de trabalho
WORKDIR /var/www/html

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Expõe a porta padrão do Apache
EXPOSE 80

# Inicia o servidor Apache em primeiro plano
CMD ["apache2-foreground"]