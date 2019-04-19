<?php


$app->get('/types[/]', 'TypeController:getAll');
$app->get('/types/{id}[/]', 'TypeController:getOne');
$app->post('/types[/]', 'TypeController:post');
$app->put('/types/{id}[/]', 'TypeController:put');
$app->delete('/types/{id}[/]', 'TypeController:delete');
$app->get('/types/{id}/materiels[/]','TypeController:getTypeMateriels');



$app->get('/materiels[/]', 'MaterielController:getAll');
$app->get('/materiels/{id}[/]', 'MaterielController:getOne');
$app->get('/materiels/{id}/exemplaires[/]', 'MaterielController:getMaterielExemplaires');
$app->post('/materiels[/]', 'MaterielController:post');
$app->delete('/materiels/{id}[/]', 'MaterielController:delete');
$app->put('/materiels/{id}[/]', 'MaterielController:put');
$app->patch('/materiels/{id}[/]','MaterielController:patch');



$app->get('/exemplaires[/]', 'ExemplaireController:getAll');
$app->get('/exemplaires/{id}[/]', 'ExemplaireController:getOne');
$app->post('/exemplaires[/]', 'ExemplaireController:post');
$app->put('/exemplaires/{id}[/]', 'ExemplaireController:put');
$app->delete('/exemplaires/{id}[/]', 'ExemplaireController:delete');
$app->patch('/exemplaires/{id}[/]', 'ExemplaireController:patch');
$app->get('/exemplaires/{id}/materiels[/]', 'ExemplaireController:getExemplairesMateriel');
