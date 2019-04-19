<?php

namespace PrismGestion\Controllers;

use Slim\Views\Twig as View;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Type;

class HomeController extends Controller
{

    public function index($request, $response)
    {
        $mat = Materiel::find(1);
        $type = Type::find(1);
        $matype = Materiel::with('type')->get();
        $typemat = Type::with('materiel')->get();

        var_dump(json_encode($mat));
        var_dump(json_encode($type));
        var_dump(json_encode($matype));
        var_dump(json_encode($typemat));
        die;


        return $this->view->render($response,'home.twig');
    }
}