<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
// executes the "php bin/console cache:clear" command
passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
    $_ENV['APP_ENV'],
    __DIR__
));

// executes the "php bin/console doctrine:database:create" command
passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" doctrine:database:create --if-not-exists',
    $_ENV['APP_ENV'],
    __DIR__
));

// migrations
passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" doctrine:migrations:migrate --no-interaction',
    $_ENV['APP_ENV'],
    __DIR__
));

// executes the "php bin/console doctrine:fxture:load" command
// passthru(sprintf(
//   'APP_ENV=%s php "%s/../bin/console" doctrine:fixtures:load --no-interaction',
//    $_ENV['APP_ENV'],
//   __DIR__
// ));
