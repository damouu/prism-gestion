<?php


$app->get('/', 'HomeController:index')->setName('home.index');
$app->get('/agenda', 'AgendaController:index')->setName('agenda.index');
$app->get('/materiel', 'MaterielController:index')->setName('materiel.index');
$app->get('/reservation', 'ReservationController:index')->setName('reservation.index');
