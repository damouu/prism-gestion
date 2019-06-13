<?php

namespace PrismGestion\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use DateTime;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Etudiant;
use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\Reservation;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;


class ReservationController extends Controller
{

    public function getAll(Request $request, Response $response, $args)
    {

        $params = [
            'select' => $request->getQueryParam('select','all')
        ];

        if($params['select'] === 'all')
        {
            try{
                $reservation = Reservation::with('departement')
                    ->with('professeur')
                    ->orderBy('created_at','desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation
                ];
            }
            catch(\Exception $e)
            {
                var_dump($e);die;
                $data = ApiErrors::InternalError();
            }

        }
        else if($params['select'] === 'past')
        {
            $dateT = new Datetime();
            try{
                $reservation = Reservation::where('date_retour','<=',$dateT)->orderBy('created_at','desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation,
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else if($params['select'] === 'ongoing')
        {
            $dateT = new Datetime();
            try{
                $reservation = Reservation::where('date_retour','>=',$dateT)->where('date_depart','<=',$dateT)->orderBy('created_at','desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation,
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else if($params['select'] === 'future')
        {
            $dateT = new Datetime();
            try{
                $reservation = Reservation::where('date_depart','>',$dateT)->orderBy('created_at','desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation,
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function getOne(Request $request, Response $response, $args) {
        $id = $args['id'];
        try {
            $reservation = Reservation::find($id);
            if(empty($reservation))
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
            else
            {
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservation' => $reservation
                ];
            }
        }
        catch (\Exception $e)
        {
            $data = ApiErrors::InternalError();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function post(Request $request, Response $response, $args)
    {
        $content = $request->getParsedBody();

        if(isset($content['groupes']))
        {
            $etudiants = $content['groupes'];
        }
        else{
            $etudiants = null;
        };


        if(!isset($content['responsable_projet'])
            || !isset($content['departement'])
            || !isset($content['matiere'])
            || !isset($content['annee'])
            || !isset($content['dep_groupe']))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            try{
                $content['responsable_projet'] = trim($content['responsable_projet']);
                $content['departement'] = trim($content['departement']);
                $content['matiere'] = trim($content['matiere']);
                $content['annee'] = trim($content['annee']);
                $content['dep_groupe'] = trim($content['dep_groupe']);

                if(isset($content['observation']))
                {
                    $content['observation'] = trim($content['observation']);
                }
                else {
                    $content['observation'] = null;
                }

                $reservation = new Reservation();
                $reservation->responsable_projet = $content['responsable_projet'];
                $reservation->departement = $content['departement'];
                $reservation->matiere = $content['matiere'];
                $reservation->annee = $content['annee'];
                $reservation->dep_groupe = $content['dep_groupe'];
                $reservation->observation = $content['observation'];

                if(is_null($etudiants))
                {
                    $reservation->save();
                }
                else
                {
                    DB::transaction(function () use ($content, $etudiants, $reservation) {

                        foreach($etudiants as $key => $row)
                        {
                            if($key===0)
                            {
                                $etudiant = Etudiant::find($row);
                                $etudiant->groupe()->save($reservation, ['referent'=>1]);
                            }
                            else
                            {
                                $etudiant = Etudiant::find($row);
                                $etudiant->groupe()->save($reservation, ['referent'=>0]);
                            }
                        }
                    });
                }

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservation' => $reservation,
                ];

            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }




}