<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => parse_ini_file('conf.ini')
    ],

]);



$container = $app->getContainer();


$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();


$container['db'] = function($container) use ($capsule) {
    return $capsule;
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    return $view;
};



$container['HomeController'] = function ($container) {
    return new \PrismGestion\Controllers\HomeController($container);
};
$container['AgendaController'] = function ($container) {
    return new \PrismGestion\Controllers\AgendaController($container);
};
$container['MaterielController'] = function ($container) {
    return new \PrismGestion\Controllers\MaterielController($container);
};
$container['ReservationController'] = function ($container) {
    return new \PrismGestion\Controllers\ReservationController($container);
};


require __DIR__ . '/../app/routes.php';