# OpenClassrooms - Develop the SnowTricks community website

## Repository containing the context and deliverables of the project
https://github.com/Galuss1/openclassrooms-archive/tree/main/php-symfony-application-developer/project-6

## Setting up

### Required
1. [PHP 8.1](https://www.php.net/downloads.php)
2. [Composer](https://getcomposer.org/download/)
3. [MySQL](https://www.mysql.com/fr/downloads/)
4. [npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

### Optional
1. [Docker](https://www.docker.com/)
2. SMTP (*example: [maildev](https://github.com/maildev/maildev)*) (*SMTP is already included if you are using docker*)
3. [Symfony CLI](https://symfony.com/download)

### Installation
1. **Clone the repository on the main branch**
<br>

2. **Create the .env.local file and replace the values of the .env origin file**
```bash
###> symfony/framework-bundle ###
APP_ENV=#env|prod|test#
APP_SECRET=#secret#
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL=#"mysql://user:password@host:port/database?serverVersion=15&charset=utf8"#
###< doctrine/doctrine-bundle ###

###> symfony/webapp-pack ###
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/webapp-pack ###

###> symfony/mailer ###
MAILER_DSN=#smtp://host:port#
###< symfony/mailer ###

###> app/JWTService ###
JWT_SECRET=#secret#
###< app/JWTService ###

###> docker/database ###
DATABASE_HOST=#database_host#
MYSQL_DATABASE=#database_name#
MYSQL_ROOT_PASSWORD=#database_root_password#
MYSQL_USER=#database_user#
MYSQL_PASSWORD=#database_user_password#
MYSQL_DATABASE_TEST=#database_test_name#
###< docker/database ###
```
<br>

3. **If you are using docker, install your environment and start the project**
```bash
docker-compose up --build -d
```
<br>

4. **Installing dependencies**
```bash
composer install
```
<br>

5. **Setting up the database**<br />
*If you are using docker, the first command is not necessary and the database "training_snowtricks" is already created without the data at localhost:3310*
```bash
php bin/console doctrine:database:create
```
```bash
php bin/console doctrine:schema:create
```
```bash
php bin/console doctrine:fixtures:load
```
<br>

6. **Start the project**<br>
*If you are using docker, the project is already accessible at http://localhost:8080*
```bash
php -S 127.0.0.1:8080 -t public
```
```bash
symfony server:start
```
<br>

--- --- ---

### Links
[Website](https://formation.snowtricks.gaelpaquien.com/)\
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-snowtricks/dashboard)
