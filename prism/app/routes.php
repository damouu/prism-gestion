<?php


$app->get('/types[/]', 'TypeController:getAll');
$app->post('/types[/]', 'TypeController:post');

$app->get('/materiels[/]', 'MaterielController:getAll');
$app->post('/materiels[/]', 'MaterielController:post');

$app->get('/exemplaires[/]', 'ExemplaireController:getAll');
$app->post('/exemplaires[/]', 'ExemplaireController:post');


$app->group('', function() {
    $this->get('/types/{id}[/]', 'TypeController:getOne');
    $this->put('/types/{id}[/]', 'TypeController:put');
    $this->delete('/types/{id}[/]', 'TypeController:delete');
    $this->get('/types/{id}/materiels[/]','TypeController:getTypeMateriels');

    $this->get('/materiels/{id}[/]', 'MaterielController:getOne');
    $this->get('/materiels/{id}/exemplaires[/]', 'MaterielController:getMaterielExemplaires');
    $this->delete('/materiels/{id}[/]', 'MaterielController:delete');
    $this->put('/materiels/{id}[/]', 'MaterielController:put');
    $this->patch('/materiels/{id}[/]','MaterielController:patch');

    $this->get('/exemplaires/{id}[/]', 'ExemplaireController:getOne');
    $this->put('/exemplaires/{id}[/]', 'ExemplaireController:put');
    $this->delete('/exemplaires/{id}[/]', 'ExemplaireController:delete');
    $this->patch('/exemplaires/{id}[/]', 'ExemplaireController:patch');
    $this->get('/exemplaires/{id}/materiels[/]', 'ExemplaireController:getExemplairesMateriel');
})
->add( new \PrismGestion\Middlewares\IdCheckerMiddleware() );




