# Usa uma imagem oficial do Apache com PHP
FROM php:8.2-apache

# Instala extensões comuns do PHP (opcional, mas útil)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia todos os arquivos do seu projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Habilita o mod_rewrite do Apache (se precisar de URLs amigáveis)
RUN a2enmod rewrite

# Permite que o Apache rode como root (útil para dev)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80