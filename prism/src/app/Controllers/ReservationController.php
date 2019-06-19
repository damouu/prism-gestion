<?php

namespace PrismGestion\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use DateTime;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Etudiant;
use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\FicheReservation;
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
                $data = ApiErrors::InternalError();
            }

        }
        else if($params['select']==='fiches'){
            try{
                $reservation = Reservation::with('fiche_resa')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else {
            $data = ApiErrors::NotFound($request->getUri());
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function getOne(Request $request, Response $response, $args) {
        $id = $args['id'];
        try {
            $reservation = Reservation::with('departement')
                ->with('professeur')
                ->with('groupe')
                ->with(['fiche_resa' => function ($q) {
                    $q->with(['exemplaire' => function ($w){
                        $w->with('materiel');
                    }]);
                }])
                ->find($id);
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


    public function getOneFeuille(Request $request, Response $response, $args){

    }


    public function postOneFeuille(Request $request, Response $response, $args){

        $id = (int)trim($args['id']);
        $content = $request->getParsedBody();

        try{

            if(!isset($content['date_depart']) || !isset($content['date_retour']) || !isset($content['rendu']) || !isset($content['observation']) || !isset($content['exemplaires'])){
                $data = ApiErrors::BadRequest();
            }
            else {
                $reservation = Reservation::with('fiche_resa')->find($id);
                $test = $reservation->fiche_resa()->count();
                $date = date('ym');
                $idfeuille = $date.'-'.$id.($test+1);

                $fiche_resa = new FicheReservation();
                $fiche_resa->id = $idfeuille;
                $fiche_resa->reservation = $id;
                $fiche_resa->date_depart = trim($content['date_depart']).':00';
                $fiche_resa->date_retour = trim($content['date_retour']).':00';
                $fiche_resa->observation = trim($content['observation']);
                $fiche_resa->rendu = $content['rendu'];





                foreach($content['exemplaires'] as $row){

                    $exemplaire = Exemplaire::find($row['id']);
                    $exemplaire->fiche_resa()->save($fiche_resa, ['emprunt'=>0, 'rendu'=>0]);
                }

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'fiche_reservation' => $fiche_resa,
                ];

            }


        }
        catch(\Exception $e){
            $data = [
                'type' => "error",
                'code' => 500,
                'reservation' => $e,
            ];
            //$data = ApiErrors::InternalError();
        }

        return ResponseWriter::ResponseWriter($response, $data);


    }



}