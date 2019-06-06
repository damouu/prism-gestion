<?php

namespace PrismGestion\Controllers;

use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\FicheReservation;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Type;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class AgendaController extends Controller
{

    public function getAllE(Request $request, Response $response, $args)
    {

        $params = [
            'select' => $request->getQueryParam('select','Tous')
        ];



            $agenda = FicheReservation::with('exemplaire')->get();
            foreach ($agenda as $agen) {

                $delete = array();

                foreach ($agen->exemplaire as $key => $exemplaire) {
                    if($params['select']!='Tous') {
                        $materiel = $exemplaire->materiel()->first();
                        $materiel->type = $materiel->type()->first();
                        $exemplaire->materiel = $materiel;
                        if($exemplaire->materiel->type->nom=!$params['select'])
                        {
                            array_push($delete, $key);
                        }
                    }
                    else
                    {
                        $materiel = $exemplaire->materiel()->first();
                        $materiel->type = $materiel->type()->first();
                        $exemplaire->materiel = $materiel;
                    }
                }
                foreach($delete as $row)
                {
                    array_splice($agen->exemplaire,$row,1);
                }
            }
        $data = [
            'type' => "success",
            'code' => 200,
            'agenda' => $agenda
        ];

        return ResponseWriter::ResponseWriter($response, $data);

    }

    public function getAll(Request $request, Response $response, $args)
    {
        $select = filter_var($request->getQueryParam('select','Tous'));

        $agenda = FicheReservation::with('exemplaire')->get();
        foreach($agenda as $agen)
        {
            $listeExemplaires = [];
            foreach($agen->exemplaire as $exemplaire) {
                if($select == 'Tous'){
                    $enabled = true;
                } else {
                    $enabled = false;
                }

                $materiel = $exemplaire->materiel()->first();
                $materiel->type = $materiel->type()->first();
                if($materiel->type->id == $select) {
                    $enabled = true;
                }
                $exemplaire->materiel = $materiel;
                if($enabled){
                    $listeExemplaires[] = $exemplaire;
                }
            }
            unset($agen->exemplaire);
            $agen->exemplaires = $listeExemplaires;
        }

        $data = [
            'type' => "success",
            'code' => 200,
            'agenda' => $agenda
        ];

        return ResponseWriter::ResponseWriter($response, $data);

    }

}