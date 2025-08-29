FROM php:8.2-apache

# Instala extensões necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita mod_rewrite e permite .htaccess
RUN a2enmod rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copia todos os arquivos para dentro do container
COPY . /var/www/html/

# Define o diretório raiz como seu projeto
WORKDIR /var/www/html

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
