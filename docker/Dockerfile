# Récupère l'image "8.1-apache" depuis Docker Hub
FROM php:8.1-apache

# Installation d'un dépot git permetant d'installer des extensions PHP provenant d'images officiels
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Installation des extensions PHP
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql zip

# Installation de composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \ 
    mv composer.phar /usr/local/bin/composer

# Installation de node.js & npm
#RUN apt update && apt install -yqq nodejs npm

# Copie le répertoire courant contenant l'application vers '/var/www'
COPY . /var/www

# Remplace le contenu du fichier de config apache par notre propre fichier de config apache
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Execute une suite de commande afin d'installer toutes les dépendances du projet (composer, npm...)
RUN cd /var/www && \
    composer install
    #npm install --force && \
    #npm run build

# Définis le repertoire de travail (default: /var/www/html/)
WORKDIR /var/www/

# Définis le port exposé vers l'exterieur
EXPOSE 80

# Commandes:
# Build: docker build . -f ./docker/Dockerfile
# Entrer dans le conteneur: docker run -it ID bash
# Lancer les conteneurs: cd docker -> docker-compose up -d


