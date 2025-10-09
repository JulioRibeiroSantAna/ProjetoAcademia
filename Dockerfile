FROM php:8.2-apache

# Instala extensões necessárias do PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita o mod_rewrite e permite uso de .htaccess
RUN a2enmod rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copia o conteúdo do projeto para dentro do container
COPY . /var/www/html/

# Define o diretório de trabalho
WORKDIR /var/www/html

# Ajusta permissões
RUN chmod -R 755 /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Configuração para garantir que o .htaccess funcione e o index.php seja carregado automaticamente
RUN printf "\n<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n" >> /etc/apache2/apache2.conf

# Garante que o Apache priorize o index.php
RUN echo "<IfModule dir_module>\n\
    DirectoryIndex index.php index.html\n\
</IfModule>" > /etc/apache2/conf-available/docker-php.conf \
 && a2enconf docker-php

# Adiciona um arquivo index.php de teste
RUN echo "<?php phpinfo(); ?>" > /var/www/html/index.php

# Expõe a porta padrão do Apache
EXPOSE 80

# Inicia o servidor Apache em primeiro plano
CMD ["apache2-foreground"]
