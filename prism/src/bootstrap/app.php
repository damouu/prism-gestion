<?php

require __DIR__ . '/../vendor/autoload.php';


$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => parse_ini_file('conf.ini')
    ],

]);


$container = $app->getContainer();
print_r($container);
die();

$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();


$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};


$container['MaterielController'] = function ($container) {
    return new \PrismGestion\Controllers\MaterielController($container);
};
$container['ExemplaireController'] = function ($container) {
    return new \PrismGestion\Controllers\ExemplaireController($container);
};
$container['TypeController'] = function ($container) {
    return new \PrismGestion\Controllers\TypeController($container);
};
$container['FournisseurController'] = function ($container) {
    return new \PrismGestion\Controllers\FournisseurController($container);
};
$container['DepartementController'] = function ($container) {
    return new \PrismGestion\Controllers\DepartementController($container);
};
$container['EtudiantController'] = function ($container) {
    return new \PrismGestion\Controllers\EtudiantController($container);
};
$container['ProfesseurController'] = function ($container) {
    return new \PrismGestion\Controllers\ProfesseurController($container);
};
$container['ReservationController'] = function ($container) {
    return new \PrismGestion\Controllers\ReservationController($container);
};


$container['HomeController'] = function ($container) {
    return new \PrismGestion\Controllers\HomeController($container);
};
$container['AgendaController'] = function ($container) {
    return new \PrismGestion\Controllers\AgendaController($container);
};


require __DIR__ . '/../app/routes.php';