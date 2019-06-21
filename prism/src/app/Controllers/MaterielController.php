<?php

namespace PrismGestion\Controllers;

use DateTime;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Type;
use PrismGestion\Utils\ResponseWriter;
use \Psr\http\Message\ServerRequestInterface as Request;
use \Psr\http\Message\ResponseInterface as Response;

class MaterielController extends Controller
{

    public function getAll(Request $request, Response $response, $args)
    {
        $params= [
            'select' => filter_var($request->getParam('select', 'Tous')),
        ];

        if($params['select']==='Tous')
        {
            try
            {

                $materiel = Materiel::select('materiel.id','materiel.constructeur','materiel.modele','type','materiel.date_creation')
                    ->join('type', 'materiel.type','=','type.id')
                    ->with(['type' => function ($q) {
                        $q->select('type.id','type.nom');
                    }]);

                $materiel = $materiel->get();

                if($materiel->isEmpty())
                {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'materiels' => [],
                    ];
                }
                else
                {
                    foreach($materiel as $row){
                        $row->nb_ex = Exemplaire::where('materiel','=',$row->id)->count();
                    }
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'materiels' => $materiel,
                    ];
                }

            }
            catch(\Exception $e)
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
        }
        else if($params['select']==='reservation')
        {

            try
            {
                $date = new DateTime();
                $materiel = Materiel::with('type')
                    ->with(['exemplaire' => function ($q) use ($date) {
                        $q->with('fiche_resa');
                }])->get();

                if($materiel->isEmpty())
                {
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else
                {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'materiels' => $materiel,
                    ];
                }

            }
            catch(\Exception $e)
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
        }
        else
        {
            $data = ApiErrors::NotFound($request->getUri());
        }



        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function getOne(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);

        if(!is_null($id) )
        {
            try
            {
                $materiel = Materiel::with('type')->find($id);
                
                if(empty($materiel)){
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else {
                    $materiel->nb_ex = Exemplaire::where('materiel','=',$materiel->id)->count();
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'materiel' => $materiel
                    ];
                }
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
        }
        else
        {
            $data = ApiErrors::BadRequest();
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function getMaterielExemplaires(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        try
        {
            $materiel = Materiel::where('materiel.id','=',$id)
                ->with('exemplaire')
                ->with('type')
            ;

            if(empty($materiel))
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
            else{

                $materiel = $materiel->first();
                $elementCounter = $materiel->exemplaire->count();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'ressource' => [
                        'total' => $elementCounter,
                    ],
                    'materiels' => $materiel
                ];
            }
        }
        catch(\Exception $e)
        {
            $data = ApiErrors::NotFound($request->getUri());
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function delete(Request $request, Response $response, $args)
    {
        
        $id = intval($args['id']);

        try
        {
            $materiel = Materiel::find($id);
            $materiel->nb_ex = Exemplaire::where('materiel','=',$materiel->id)->count();

            if($materiel->nb_ex == 0)
            {
                $materiel->delete();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'message' => 'le matériel '. $materiel->id . ' a bien été supprimé.'
                ];
            }
            else
            {
                $data = [
                    'type' => 'error',
                    'code' => 403,
                    'message' => 'Le matériel n\'a pas été supprimé: Des exemplaires sont encore liés.'
                ];
            }
        }
        catch(\Exception$e)
        {
            $data = ApiErrors::NotFound($request->getUri());
        }


        return ResponseWriter::ResponseWriter($response, $data);
    }



    public function post(Request $request, Response $response, $args)
    {

        $content = $request->getParsedBody();

        if(!isset($content['constructeur']) || !isset($content['modele']) || !isset($content['type']))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            $materiel = new Materiel();
            $materiel->constructeur = $content['constructeur'];
            $materiel->modele = $content['modele'];
            if(isset($content['description'])){
                $materiel->description = $content['description'];
            }
            $materiel->type = $content['type'];
            try
            {
                $materiel->save();
                $data = [
                    'type' => "success",
                    'code' => 201,
                    'materiel' => $materiel
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }



    public function put(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);
        $content = $request->getParsedBody();

        $materiel = Materiel::find($id);

        if(!isset($content['constructeur']) || !isset($content['modele']) || !isset($content['description']) || !isset($content['type']))
        {
            $data = ApiErrors::BadRequest();
        }
        else if(empty($materiel))
        {
            $materiel = new Materiel();
            $materiel->id = $id;
            $materiel->constructeur = $content['constructeur'];
            $materiel->modele = $content['modele'];
            $materiel->description = $content['description'];
            $materiel->type = $content['type'];

            try {
                $materiel->save();
                $materiel = Materiel::select('materiel.id','materiel.constructeur','materiel.modele','type','materiel.date_creation')
                    ->join('type', 'materiel.type','=','type.id')
                    ->with(['type' => function ($q) {
                        $q->select('type.id','type.nom');
                    }])
                ->find($id);

                $materiel->nb_ex = Exemplaire::where('materiel','=',$materiel->id)->count();

                $data = [
                    'type' => "success",
                    'code' => 201,
                    'materiel' => $materiel
                ];
            } catch (\Exception $e) {
                $data = ApiErrors::InternalError();
            }
        }
        else
        {
            try {

                $materiel->constructeur = $content['constructeur'];
                $materiel->modele = $content['modele'];
                $materiel->description = $content['description'];
                $materiel->type = $content['type'];
                $materiel->save();

                $materiel = Materiel::select('materiel.id','materiel.constructeur','materiel.modele','materiel.nb_ex','type','materiel.date_creation','materiel.description')
                    ->join('type', 'materiel.type','=','type.id')
                    ->with(['type' => function ($q) {
                        $q->select('type.id','type.nom');
                    }])
                ->find($id);
                    $materiel->nb_ex = Exemplaire::where('materiel','=',$materiel->id)->count();


                $data = [
                    'type' => "success",
                    'code' => 200,
                    'materiel' => $materiel
                ];
            } catch (\Exception$e) {
                $data = ApiErrors::InternalError();
            }
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }


/*
    public function patch(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);
        $nb_ex = $request->getParsedBody();

        $materiel = Materiel::find($id);

        if(!isset($nb_ex['nb_ex'])){
            $data = ApiErrors::BadRequest();
        }
        else if(empty($materiel))
        {
            $data = ApiErrors::NotFound($request->getUri());
        }
        else
        {
            try
            {
                $materiel->nb_ex = $nb_ex['nb_ex'];
                $materiel->save();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'materiel' => $materiel
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }

        }

        return ResponseWriter::ResponseWriter($response, $data);
    }
*/

}