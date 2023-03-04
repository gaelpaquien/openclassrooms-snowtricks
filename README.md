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

#### Required:

1. [PHP](https://www.php.net/downloads.php)
2. [Symfony CLI](https://symfony.com/download)
3. [Composer](https://getcomposer.org/download/)
4. [MySQL](https://www.mysql.com/fr/downloads/)
5. SMTP configuration, for example you can use [maildev](https://github.com/maildev/maildev)
6. Create an '.env.local' file and copy the content of the '.env' file and change the lines below.

##### Lines to be modified in the new file '.env.local':
> APP_ENV={dev or prod}\
> APP_SECRET={secret token for app}\
> DATABASE_URL="mysql://{username}:{password}@{host}:{port}/{db_name}?serverVersion=8&charset=utf8mb4"\
> MAILER_DSN=smtp://{host}:{port}\
> JWT_SECRET='{secret token for JWTService}'

#### Installation:

1. Download the [GitHub repository](https://github.com/Galuss1/openclassrooms-snowtricks/) on the main branch.
2. Create your '.env.local' file in the root of the project, the information modified in this new file must be correct.
3. Create your database and use the command "symfony console doctrine:schema:create" in a terminal, this command will import the database schema.
4. Use the command "symfony console doctrine:fixtures:load" in a terminal, this command allows generating data in the database.
5. Open a command terminal at the root of the project and use the following commands:\
   5.1. **composer install** *(this command allows you to install the project's dependencies)*\
   5.2. **composer dump-autoload** *(this command allows you to update your autoloader)*
6. Launch your website. For this, there are several solutions:\
   6.1. Use a web server (MAMP, XAMPP...).\
   6.2. Launch a terminal from the root of the project and use the following command: **symfony serve -d**
7. Launch your SMTP service.

 ---

### Links :

[Link to the website](https://snowtricks.gael-paquien.fr/)
