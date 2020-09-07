<?php
require __DIR__ . '/vendor/autoload.php';

use \Symfony\Component\Console\Application;

define('BASE_PATH', __DIR__);


$app = new Application();
$container = new \Core\App(__DIR__ . '/public');
$container->instance('application', $app);

// register;
$app->add(new \App\Commands\MigrationCommand());
$app->add(new \App\Commands\MigrateInstallCommand());


$app->run();

