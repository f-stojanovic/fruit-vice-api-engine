# FruitVice Simple API Engine

**Symfony 5.4 project**

### Environment
- nginx 1.17
- php-fpm 8.1
- mysql

### Requirements
Docker compose, Git and as the main IDE is PhpStorm (preferably the latest version with Shell Configuration supports).

### Installation
Open project root folder and run next configurations:
1. Open terminal and run: `docker-compose build`
2. Then: `docker-compose -f docker-compose.yml up`
3. Enter the PHP container: `docker exec -it fruit-vice-api-php-1 /bin/bash`
4. In the container run `composer install` (after execute, it will take a little time to index the installed vendors)
5. Run the command `php bin/console doctrine:migrations:migrate` to populate database
6. Then run the command `php bin/console messenger:consume async -vv` so you can check the status of email being sent.
7. Now run: `php bin/console fruits:fetch` which will populate database with fruits data and trigger email sending.
8. There is one test example for one of the endpoint that can be checked `vendor/bin/phpunit` command 
   (Do not forget to create test db with `php bin/console doctrine:database:create --env=test`
    and `php bin/console doctrine:migrations:migrate --env=test`).

On the http://localhost/ you can check all functionality described in the task. I did not receive task in some convenient
timeframe, so I did not have time to implement frontend with Vue.js, just with twig. But I think it is worth the look. :)

Hope you like it.. :) 