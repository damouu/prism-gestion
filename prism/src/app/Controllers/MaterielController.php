<?php

namespace PrismGestion\Controllers;

use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Type;
use PrismGestion\Utils\ResponseWriter;
use \Psr\http\Message\ServerRequestInterface as Request;
use \Psr\http\Message\ResponseInterface as Response;

class MaterielController extends Controller
{

    public function getAll(Request $request, Response $response, $args)
    {

        $params = [
            'nb' => intval($request->getQueryParam('nb',10)),
            'page' => intval($request->getQueryParam('page',1)),
            'types' => $request->getQueryParam('types',null),
        ];

        try
        {

            if(is_null($params['types']))
            {
                $materiel = Materiel::select('materiel.id','materiel.constructeur','materiel.modele','materiel.nb_ex','type','materiel.date_creation')
                    ->join('type', 'materiel.type','=','type.id')
                    ->with(['type' => function ($q) {
                        $q->select('type.id','type.nom');
                    }]);
            }
            else
            {
                $materiel = Materiel::select('materiel.id','materiel.constructeur','materiel.modele','materiel.nb_ex','type','materiel.date_creation')
                    ->join('type', 'materiel.type','=','type.id')
                    ->where('type.nom','=',$params['types'])
                    ->with(['type' => function ($q) {
                        $q->select('type.id','type.nom');
                    }]);
            }


            $elementCounter = $materiel->get()->count();
            if( (($params['nb']*($params['page']))>$elementCounter) || ($params['nb']<=0) || ($params['page']<=0) )
            {
                $params['nb'] = 10;
                $params['page'] = 1;
            }
            if($params['nb'])
                $materiel = $materiel->take($params['nb']);
            if($params['page'])
                $materiel = $materiel->skip($params['nb']*($params['page']-1));
            $pageMax = ceil($elementCounter/$params['nb']);

            $materiel = $materiel->get();

            if($materiel->isEmpty())
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
            else
            {
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'ressource' => [
                        'total' => $elementCounter,
                        'nb_per_page' => $params['nb'],
                        'page' => $params['page'],
                        'page_max' => $pageMax,
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
                    'message' => 'Le matériel \'a pas été supprimé: Des exemplaires sont encore liés.'
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

        if(!isset($content['constructeur']) || !isset($content['modele']) || !isset($content['description']) || !isset($content['nb_ex']) || !isset($content['type']))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            $materiel = new Materiel();
            $materiel->constructeur = $content['constructeur'];
            $materiel->modele = $content['modele'];
            $materiel->description = $content['description'];
            $materiel->nb_ex = $content['nb_ex'];
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

        if(!isset($content['constructeur']) || !isset($content['modele']) || !isset($content['description']) || !isset($content['nb_ex']) || !isset($content['type']))
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
            $materiel->nb_ex = $content['nb_ex'];
            $materiel->type = $content['type'];

            try {
                $materiel->save();
                $materiel = Materiel::select('materiel.id','materiel.constructeur','materiel.modele','materiel.nb_ex','type','materiel.date_creation')
                    ->join('type', 'materiel.type','=','type.id')
                    ->with(['type' => function ($q) {
                        $q->select('type.id','type.nom');
                    }])
                ->find($id);

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
                $materiel->nb_ex = $content['nb_ex'];
                $materiel->type = $content['type'];
                $materiel->save();

                $materiel = Materiel::select('materiel.id','materiel.constructeur','materiel.modele','materiel.nb_ex','type','materiel.date_creation','materiel.description')
                    ->join('type', 'materiel.type','=','type.id')
                    ->with(['type' => function ($q) {
                        $q->select('type.id','type.nom');
                    }])
                ->find($id);

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


}