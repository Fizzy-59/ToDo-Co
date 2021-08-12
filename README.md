# Todo&Co

Application developed in PHP with :
- [Symfony Framework](https://symfony.com/)
- [Doctrine ORM](https://www.doctrine-project.org/)

Application to manage daily tasks.

## Installation

1. Clone or download the GitHub repository in the desired folder: https://github.com/Fizzy-59/ToDo-Co.git
2. Create and configure .env file for the database connection, SMTP server or mail address in the root directory
   https://symfony.com/doc/current/configuration.html
3. Install dependencies with : "composer install"

### Optional
1. Create the database: php bin/console doctrine:database:create
2. Load schemas: php bin/console doctrine:schema:update --force

### CLI Common commands

- php bin/console make:entity
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate

- composer dump-autoload

- php bin/console doctrine:database:drop --force
- php bin/console doctrine:database:create
- php bin/console doctrine:schema:update --force

- symfony server:start

### Testing
 - source ~/.bash_profile
 - php /usr/local/bin/phpunit
 - php -d memory_limit=5G /usr/local/bin/phpunit
 - php -dxdebug.mode=coverage bin/phpunit --coverage-clover='reports/coverage/coverage.xml' --coverage-html='reports/coverage'

 - php bin/console doctrine:fixtures:load --env=test
 - php bin/console doctrine:database:create --env=test
 - php bin/console doctrine:schema:update --force --env=test

 - php bin/console  cache:clear -e test