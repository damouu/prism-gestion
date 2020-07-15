<?php

/*-------------CORS------------------*/
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
/* ----------------------------------*/

$app->get('/user/signIn[/]', function ($rq, $rs, $args) {
    return (new \PrismGestion\Controllers\UsersController($this))->userSignIn($rq, $rs, $args);
})->add(\PrismGestion\Middlewares\QueryParamNetID::class . ':checkQueryParamNetID');


//TODO route de test pour les JWT , ligne a supprimer.
$app->get('/JWT[/]', function ($rq, $rs, $args) {
    return (new \PrismGestion\Controllers\UsersController($this))->printJWT($rq, $rs, $args);
})->add(\PrismGestion\Middlewares\AccessJWTLevel2::class . ':AccessJWTLevel2')
    ->add(\PrismGestion\Middlewares\decodeJWT::class . ':decodeJWT')
    ->add(\PrismGestion\Middlewares\checkToken::class . ':checkToken');


$app->get('/types[/]', 'TypeController:getAll');
$app->post('/types[/]', 'TypeController:post');
$app->get('/materiels[/]', 'MaterielController:getAll');
$app->post('/materiels[/]', 'MaterielController:post');
$app->get('/exemplaires[/]', 'ExemplaireController:getAll');
$app->post('/exemplaires[/]', 'ExemplaireController:post');
$app->get('/fournisseurs[/]', 'FournisseurController:getAll');
$app->post('/fournisseurs[/]', 'FournisseurController:post');
$app->get('/departements[/]', 'DepartementController:getAll');
$app->post('/departements[/]', 'DepartementController:post');
$app->get('/etudiants[/]', 'EtudiantController:getAll');
$app->post('/etudiants[/]', 'EtudiantController:post');
$app->get('/professeurs[/]', 'ProfesseurController:getAll');
$app->post('/professeurs[/]', 'ProfesseurController:post');
$app->get('/reservations[/]', 'ReservationController:getAll');
$app->post('/reservations[/]', 'ReservationController:post');

$app->get('/agenda[/]', 'AgendaController:getAll');


$app->group('', function () {

    $this->post('/reservations/{id}/feuilles[/]', 'ReservationController:postOneFeuille');
    $this->get('/reservations/{id}/feuilles[/]', 'ReservationController:getOneFeuille');

    $this->get('/reservations/{id}[/]', 'ReservationController:getOne');

    $this->get('/departements/{id}[/]', 'DepartementController:getOne');
    $this->delete('/departements/{id}[/]', 'DepartementController:delete');
    $this->put('/departements/{id}[/]', 'DepartementController:put');

    $this->get('/etudiants/{id}[/]', 'EtudiantController:getOne');
    $this->delete('/etudiants/{id}[/]', 'EtudiantController:delete');
    $this->put('/etudiants/{id}[/]', 'EtudiantController:put');

    $this->get('/professeurs/{id}[/]', 'ProfesseurController:getOne');
    $this->delete('/professeurs/{id}[/]', 'ProfesseurController:delete');
    $this->put('/professeurs/{id}[/]', 'ProfesseurController:put');

    $this->get('/fournisseurs/{id}[/]', 'FournisseurController:getOne');
    $this->put('/fournisseurs/{id}[/]', 'FournisseurController:put');
    //$this->delete('/fournisseurs/{id}[/]', 'FournisseurController:delete');

    $this->get('/types/{id}[/]', 'TypeController:getOne');
    $this->put('/types/{id}[/]', 'TypeController:put');
    $this->delete('/types/{id}[/]', 'TypeController:delete');
    $this->get('/types/{id}/materiels[/]', 'TypeController:getTypeMateriels');

    $this->get('/materiels/{id}[/]', 'MaterielController:getOne');
    $this->get('/materiels/{id}/exemplaires[/]', 'MaterielController:getMaterielExemplaires');
    $this->delete('/materiels/{id}[/]', 'MaterielController:delete');
    $this->put('/materiels/{id}[/]', 'MaterielController:put');
    $this->patch('/materiels/{id}[/]', 'MaterielController:patch');

    $this->get('/exemplaires/{id}[/]', 'ExemplaireController:getOne');
    $this->put('/exemplaires/{id}[/]', 'ExemplaireController:put');
    $this->delete('/exemplaires/{id}[/]', 'ExemplaireController:delete');
    $this->patch('/exemplaires/{id}[/]', 'ExemplaireController:patch');
    $this->get('/exemplaires/{id}/materiels[/]', 'ExemplaireController:getExemplairesMateriel');
    $this->get('/exemplaires/{id}/reservations', 'ExemplaireController:getExemplaireReservation');
})
    ->add(new \PrismGestion\Middlewares\IdCheckerMiddleware());




