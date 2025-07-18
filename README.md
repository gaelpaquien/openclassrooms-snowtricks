# OpenClassrooms - SnowTricks

## Description
Creation of a collaborative website, allowing users to share snowboard tricks.\
This project was carried out as part of a training course. Below you will find the link to the initial repository containing all the training deliverables.

## Setting up

### Required
1. [PHP ⩾8.1](https://www.php.net/downloads.php)
2. [Composer](https://getcomposer.org/download/)
3. [MySQL](https://www.mysql.com/fr/downloads/)
4. [npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

### Optional
1. [Docker](https://www.docker.com/)
2. [Symfony CLI](https://symfony.com/download)

### Installation
1. **Clone the repository on the main branch**

2. **Create the .env.local file and replace the values of the .env origin file**

3. **Only if you are using Docker, environment installation**
```bash
docker-compose up --build
```
Wait a few moments for the environment to fully install. \
The website is accessible at http://localhost:8080 \
Mailhog web interface (SMTP) is accessible at http://localhost:8025 \
The database was created with data at localhost:3310 \
Your installation is complete, you do not need to follow the next steps.

4. **Installing dependencies**
```bash
composer install
```
```bash
npm install
```

5. **Setting up the database**
```bash
php bin/console doctrine:database:create
```
```bash
php bin/console doctrine:schema:create
```
```bash
php bin/console doctrine:fixtures:load
```

6. **Start the project**
```bash
php -S 127.0.0.1:8080 -t public
```
```bash
symfony server:start
```

--- --- ---

### Links
[Website](https://formation.snowtricks.gaelpaquien.com/) \
[Codacy Review](https://app.codacy.com/gh/gaelpaquien/openclassrooms-snowtricks/dashboard) \
[Old repository containing training deliverables](https://github.com/gaelpaquien/openclassrooms-archive/tree/main/php-symfony-application-developer/project-6)
