<?php

require __DIR__ . '/../src/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../src/templates');

$twig = new Twig\Environment($loader, [
    'cache' => false
]);

echo $twig->render('index.twig', ["uNetID" => "sehbaoui1"]);