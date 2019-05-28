<?php

namespace PrismGestion\Controllers;

use PrismGestion\Controllers\Controller;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Professeur;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use stdClass;


class ProfesseurController extends Controller {


    public function getAll(Request $request, Response $response, $args) {

        $params = [
            'query' => $request->getQueryParam('query',null),
        ];

        if($params['query'] === null)
        {
            $professeur = Professeur::get();
            $data = [
                'type' => "success",
                'code' => 200,
                'professeurs' => $professeur
            ];
        }
        else if ($params['query'] != null)
        {
            $exploded = explode(" ",$params['query']);
            $counted = count($exploded);
            if($counted>2) {
                $data = ApiErrors::BadRequest();
            }
            else {
                if($counted === 1){
                    $professeur = Professeur::where('nom','like','%'.$exploded[0].'%')
                        ->orWhere('prenom', 'like','%'.$exploded[0].'%')
                        ->get();
                }
                else
                {
                    $professeur = Professeur::where('nom','like','%'.$exploded[0].'%')
                        ->orWhere('prenom', 'like','%'.$exploded[0].'%')
                        ->orWhere(function ($q) use ($exploded) {
                            $q->where('nom','like', '%'.$exploded[0].'%')
                                ->where('prenom','like','%'.$exploded[1].'%');})
                        ->orWhere(function ($q) use ($exploded) {
                            $q->where('nom','like', '%'.$exploded[1].'%')
                                ->where('prenom','like','%'.$exploded[0].'%');})
                        ->get();
                }
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'professeurs' => $professeur
                ];
            }
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function getOne(Request $request, Response $response, $args) {
        $id = intval($args['id']);
        if(is_int($id))
        {
            try {
                $professeur = Professeur::find($id);
                if(empty($professeur))
                {
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else
                {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'professeur' => $professeur
                    ];
                }
            }
            catch (\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else
        {
            $data = ApiErrors::BadRequest();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function post(Request $request, Response $response, $args)
    {
        $content = $request->getParsedBody();

        $postValidateNomPrenom = v::notOptional()->StringType()->length(1,128);
        $postValidateMail = v::notOptional()->email();
        $postValidateTel = v::notOptional()->phone();

        if(!isset($content['nom'])
            ||!isset($content['prenom'])
            ||!isset($content['mail'])
            ||!isset($content['telephone']))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            try{
                $content['nom'] = trim($content['nom']);
                $content['prenom'] = trim($content['prenom']);
                $content['mail'] = trim($content['mail']);
                $content['telephone'] = trim($content['telephone']);
                $postValidateNomPrenom->assert($content['nom']);
                $postValidateNomPrenom->assert($content['prenom']);
                $postValidateMail->assert($content['mail']);
                $postValidateTel->assert($content['telephone']);

                $professeur = new Professeur();
                $professeur->nom = $content['nom'];
                $professeur->prenom = $content['prenom'];
                $professeur->mail = $content['mail'];
                $professeur->telephone = $content['telephone'];
                $professeur->save();

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'professeur' => 'reussi'
                ];
            }
            catch(NestedValidationException $e)
            {
                $data = ApiErrors::ValidationError($e->getMessages());
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = intval($args['id']);
        if(!is_int($id))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            $professeur = Professeur::find($id);
            if(!empty($professeur))
            {
                try
                {
                    $professeur->delete();
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'professeur' => 'reussi'
                    ];
                }
                catch (\Exception $e)
                {
                    $data = ApiErrors::InternalError();
                }
            }
            else
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function put(Request $request, Response $response, $args)
    {
        $id = intval($args['id']);
        $content = $request->getParsedBody();

        $postValidateNomPrenom = v::notOptional()->StringType()->length(1,128);
        $postValidateMail = v::notOptional()->email();
        $postValidateTel = v::notOptional()->phone();

        if(is_int($id))
        {
            $professeur = Professeur::find($id);
            if(isset($content['nom'])&&isset($content['prenom'])&&isset($content['mail'])&&isset($content['telephone']))
            {
                $content['nom'] = trim($content['nom']);
                $content['prenom'] = trim($content['prenom']);
                $content['mail'] = trim($content['mail']);
                $content['telephone'] = trim($content['telephone']);
                if(empty($professeur))
                {
                    try
                    {
                        $postValidateNomPrenom->assert($content['nom']);
                        $postValidateNomPrenom->assert($content['prenom']);
                        $postValidateMail->assert($content['mail']);
                        $postValidateTel->assert($content['telephone']);

                        $professeur = new Professeur();
                        $professeur->nom = $content['nom'];
                        $professeur->prenom = $content['prenom'];
                        $professeur->mail = $content['mail'];
                        $professeur->telephone = $content['telephone'];
                        $professeur->save();
                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'professeur' => 'reussi'
                        ];
                    }
                    catch(NestedValidationException $e)
                    {
                        $data = ApiErrors::ValidationError($e->getMessages());
                    }
                    catch(\Exception $e)
                    {
                        $data = ApiErrors::InternalError();
                    }
                }
                else
                {
                    try
                    {
                        $postValidateNomPrenom->assert($content['nom']);
                        $postValidateNomPrenom->assert($content['prenom']);
                        $postValidateMail->assert($content['mail']);
                        $postValidateTel->assert($content['telephone']);

                        $professeur->nom = $content['nom'];
                        $professeur->prenom = $content['prenom'];
                        $professeur->mail = $content['mail'];
                        $professeur->telephone = $content['telephone'];
                        $professeur->save();
                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'professeur' => 'reussi'
                        ];
                    }
                    catch(NestedValidationException $e)
                    {
                        $data = ApiErrors::ValidationError($e->getMessages());
                    }
                    catch(\Exception $e)
                    {
                        $data = ApiErrors::InternalError();
                    }
                }
            }
            else
            {
                $data = ApiErrors::BadRequest();
            }
        }
        else
        {
            $data = ApiErrors::BadRequest();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }
}