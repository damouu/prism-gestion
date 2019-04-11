<?php

namespace PrismGestion\Controllers;

use Slim\Views\Twig as View;
use PrismGestion\Models\Materiel;

class HomeController extends Controller
{

    public function index($request, $response)
    {
        return $this->view->render($response,'home.twig');
    }
}