# PHP/Symfony application developer training - OpenclassRooms (P6)

## Exercise: Develop the SnowTricks community website

### Skills assessed

1. Get to know the Symfony framework
2. Develop an application with the features expected by the customer
3. Manage a MySQL or NoSQL database with Doctrine
4. Organize your code to ensure readability and maintainability
5. Learn how to use the Twig templating engine
6. To respect the good development practices in force
7. Select the appropriate programming languages for the development of the application

---

### Setting up the website

#### Required
1. [PHP](https://www.php.net/downloads.php)
2. [Symfony CLI](https://symfony.com/download)
3. [Composer](https://getcomposer.org/download/)
4. [MySQL](https://www.mysql.com/fr/downloads/)
5. [npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)
6. SMTP service, for example [maildev](https://github.com/maildev/maildev)

#### Installation
1. Download the [GitHub repository](https://github.com/Galuss1/openclassrooms-snowtricks/) on the main branch.
2. Create '.env.local' file in the root of the project, and copy the content of the '.env' file and change [the lines below](#lines-to-be-modified).
3. Create a database.
4. Open a command terminal at the root of the project and use the following commands:\
   4.1. **composer install** *(this command allows you to install the project's dependencies)*\
   4.2. **composer dump-autoload** *(this command allows you to update your autoloader)*\
   4.3  **symfony console doctrine:schema:create --env=dev** *(this command will import the database schema)*\
   4.4  **symfony console doctrine:fixtures:load --env=dev** *(this command allows generating data in the database)*\
   4.5. **npm install** *(this command installs the npm packages)*
5. Launch your SMTP service.
6. Launch your website. For this, there are several solutions:\
   6.1. Use a web server (MAMP, XAMPP...).\
   6.2. Launch a terminal from the root of the project and use the following command: **symfony serve -d** or **symfony server:start**

##### Lines to be modified
> APP_ENV={dev or prod}\
> APP_SECRET={secret token for app}\
> DATABASE_URL="mysql://{username}:{password}@{host}:{port}/{db_name}?serverVersion=8&charset=utf8mb4"\
> MAILER_DSN=smtp://{host}:{port}\
> JWT_SECRET='{secret token for JWTService}'


 ---

### Links

[Link to the website](https://snowtricks.gael-paquien.fr/)\
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-snowtricks/dashboard?branch=main)
