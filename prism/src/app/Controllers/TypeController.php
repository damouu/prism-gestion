<?php

namespace PrismGestion\Controllers;


use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Type;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TypeController extends Controller
{


    public function getAll(Request $request, Response $response, $args)
    {

        try
        {

            $type = Type::select('id','nom','couleur');
            $type = $type->get();

            $data = [
                'type' => "success",
                'code' => 200,
                'types' => $type
            ];

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
                $type = Type::find($id);

                if(empty($type)){
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'types' => $type
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


    public function getTypeMateriels(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        try
        {
            $type = Type::where('type.id','=',$id)->with('materiels');

            if(empty($type))
            {
                $data = ApiErrors::NotFound($request->getUri());
            }
            else
            {
                $type = $type->first();
                $elementCounter = $type->materiels->count();

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'ressource' => [
                        'total' => $elementCounter,
                    ],
                    'types' => $type
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
            $type = Type::where('id','=',$id)->with('materiels')->first();

            if($type->materiels->isEmpty())
            {
                $type->delete();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'message' => 'le type '. $type->id . ' a bien été supprimé.'
                ];
            }
            else
            {
                $data = [
                    'type' => 'error',
                    'code' => 403,
                    'message' => 'Le type n\'a pas été supprimé: Des matériels sont encore liés.'
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

        if(!isset($content['nom']) || !isset($content['couleur']))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            try
            {
                $type = new Type();
                $type->nom = $content['nom'];
                $type->couleur = $content['couleur'];

                $type->save();
                $data = [
                    'type' => "success",
                    'code' => 201,
                    'types' => $type
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

        $type = Type::find($id);

        if(!isset($content['nom']))
        {
            $data = ApiErrors::BadRequest();
        }
        else if(empty($materiel))
        {
            try
            {
                $type = new Type();
                $type->id = $id;
                $type->nom = $content['nom'];

                $type->save();
                $data = [
                    'type' => "success",
                    'code' => 201,
                    'types' => $type
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else
        {
            try {

                $type->nom = $content['nom'];

                $materiel->save();

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'types' => $type
                ];
            } catch (\Exception$e) {
                $data = ApiErrors::InternalError();
            }
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }

}