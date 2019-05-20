<?php

namespace PrismGestion\Controllers;

use PrismGestion\Controllers\Controller;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Departement;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use stdClass;


class DepartementController extends Controller {


    public function getAll(Request $request, Response $response, $args) {
        $departement = Departement::get();
        $data = [
            'type' => "success",
            'code' => 200,
            'departements' => $departement
        ];
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function getOne(Request $request, REsponse $response, $args) {
        $id = intval($args['id']);
        if(is_int($id))
        {
            try {
                $departement = Departement::find($id);
                if(empty($departement))
                {
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else
                {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'departement' => $departement
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

        $postValidateNom = v::notOptional()->StringType()->length(1,128);

        if(!isset($content['nom']))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            try{
                $content['nom'] = trim($content['nom']);
                $postValidateNom->assert($content['nom']);

                $departement = new Departement();
                $departement->nom = $content['nom'];
                $departement->save();
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
            $departement = Departement::find($id);
            if(!empty($departement))
            {
                try
                {
                    $departement->delete();
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'departement' => 'reussi'
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

        $postValidateNom = v::notOptional()->StringType()->length(1,128);

        if(is_int($id))
        {
            $departement = Departement::find($id);
            if(isset($content['nom']))
            {
                $content['nom'] = trim($content['nom']);
                if(empty($departement))
                {
                    try
                    {
                        $postValidateNom->assert($content['nom']);
                        $dep = new Departement();
                        $dep->nom = $content['nom'];
                        $dep->save();
                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'departement' => 'reussi'
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
                        $postValidateNom->assert($content['nom']);
                        $departement->nom = $content['nom'];
                        $departement->save();
                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'departement' => 'reussi'
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