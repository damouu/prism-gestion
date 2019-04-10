<?php

namespace PrismGestion\Controllers;

use Slim\Views\Twig as View;
use PrismGestion\Models\Materiel;

class HomeController extends Controller
{

    public function index($request, $response)
    {
        var_dump($this->db->table('materiel')->find(1));


        $requete = Materiel::get();
        var_dump($requete);
        die;
        return $this->view->render($response,'home.twig');
    }
}