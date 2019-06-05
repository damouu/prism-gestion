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

    public function getAll(Request $request, Response $response, $args)
    {

        $agenda = FicheReservation::with('exemplaire')->get();
        foreach($agenda as $agen)
        {
            foreach($agen as $row)
            {
                $row->type =
                    Materiel::whereHas('exemplaire', function($query) use ($agen){
                        $query->where('exemplaire.id','=',$agen.id);
                    })->first();
            }

        }

        /*

                ->join('exemplaire','=','')

        */
        $data = [
            'type' => "success",
            'code' => 200,
            'agenda' => $agenda
        ];

        return ResponseWriter::ResponseWriter($response, $data);

    }
}