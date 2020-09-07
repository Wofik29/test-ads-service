<?php

use App\Controllers\MainController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('root', '/')->controller([MainController::class, 'get']);
    $routes->add('get', '/get')->controller([MainController::class, 'get']);
    $routes->add('create', '/create')->controller([MainController::class, 'create'])->methods(['post']);
    $routes->add('update', '/update')->controller([MainController::class, 'update'])->methods(['post']);
};