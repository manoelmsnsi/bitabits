# Usa uma imagem base oficial com PHP 7.3
FROM php:7.3-apache

# Instala as dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilita os módulos do Apache necessários
RUN a2enmod rewrite remoteip proxy

# Configura o Apache para permitir o uso de .htaccess e configurar o RemoteIP
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    # Configurações para o módulo remoteip\n\
    RemoteIPHeader X-Forwarded-For\n\
    RemoteIPTrustedProxy 172.19.0.4/24 \n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Define a pasta de trabalho
WORKDIR /var/www/html

# Copia o código do projeto para o container
COPY . /var/www/html

# Expõe a porta 80 para acessar o servidor Apache
EXPOSE 80

# Define o comando padrão a ser executado
CMD ["apache2-foreground"]
