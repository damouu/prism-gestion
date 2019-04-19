<?php

namespace PrismGestion\Controllers;


use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Type;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TypeController extends Controller
{


    public function getAll(Request $request, Response $response, $args)
    {

        $params = [
            'nb' => intval($request->getQueryParam('nb',10)),
            'page' => intval($request->getQueryParam('page',1)),
        ];

        try
        {

            $type = Type::select('id','nom');

            $elementCounter = $type->count();
            if( (($params['nb']*($params['page']))>$elementCounter) || ($params['nb']<=0) || ($params['page']<=0) )
            {
                $params['nb'] = 10;
                $params['page'] = 1;
            }
            if($params['nb'])
                $type = $type->take($params['nb']);
            if($params['page'])
                $type = $type->skip($params['nb']*($params['page']-1));
            $pageMax = ceil($elementCounter/$params['nb']);
            $type = $type->get();

            $data = [
                'type' => "success",
                'code' => 200,
                'ressource' => [
                    'total' => $elementCounter,
                    'nb_per_page' => $params['nb'],
                    'page' => $params['page'],
                    'page_max' => $pageMax,
                ],
                'types' => $type
            ];

        }
        catch(\Exception $e)
        {
            $data = ApiErrors::NotFound($request->getUri());
        }

        $resp = $response
            ->withStatus($data['code'])
            ->withHeader('Content-Type', 'application/json; charset=utf8');
        $resp->getBody()
            ->write(json_encode($data));

        return $resp;

    }

    public function getOne(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);

        if($id==0)
            $id=1;

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

        $resp = $response
            ->withStatus($data['code'])
            ->withHeader('Content-Type', 'application/json; charset=utf8');
        $resp->getBody()
            ->write(json_encode($data));

        return $resp;
    }


    public function getTypeMateriels(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $params = [
            'nb' => intval($request->getQueryParam('nb',10)),
            'page' => intval($request->getQueryParam('page',1)),
        ];

        try
        {

            $type = Type::where('type.id','=',$id)->with('materiels');

            if(empty($type))
            {
                $data = ApiErrors::NotFound($request->getUri());
                $resp = $response
                    ->withStatus($data['code'])
                    ->withHeader('Content-Type', 'application/json; charset=utf8');
                $resp->getBody()
                    ->write(json_encode($data));
                return $resp;
            }

            $elementCounter = $type->count();
            if( (($params['nb']*($params['page']))>$elementCounter) || ($params['nb']<=0) || ($params['page']<=0) )
            {
                $params['nb'] = 10;
                $params['page'] = 1;
            }
            if($params['nb'])
                $type = $type->take($params['nb']);
            if($params['page'])
                $type = $type->skip($params['nb']*($params['page']-1));
            $pageMax = ceil($elementCounter/$params['nb']);
            $type = $type->first();

            $data = [
                'type' => "success",
                'code' => 200,
                'ressource' => [
                    'total' => $elementCounter,
                    'nb_per_page' => $params['nb'],
                    'page' => $params['page'],
                    'page_max' => $pageMax,
                ],
                'types' => $type
            ];

        }
        catch(\Exception $e)
        {
            $data = ApiErrors::NotFound($request->getUri());
        }

        $resp = $response
            ->withStatus($data['code'])
            ->withHeader('Content-Type', 'application/json; charset=utf8');
        $resp->getBody()
            ->write(json_encode($data));

        return $resp;
    }

    public function delete(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);

        if($id==0)
        {
            $data = ApiErrors::BadRequest();
            $resp = $response
                ->withStatus($data['code'])
                ->withHeader('Content-Type', 'application/json; charset=utf8');
            $resp->getBody()
                ->write(json_encode($data));
            return $resp;
        }

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

        $resp = $response
            ->withStatus($data['code'])
            ->withHeader('Content-Type', 'application/json; charset=utf8');
        $resp->getBody()
            ->write(json_encode($data));

        return $resp;
    }



    public function post(Request $request, Response $response, $args)
    {

        $content = $request->getParsedBody();

        if(!isset($content['nom']))
        {
            $data = ApiErrors::BadRequest();
        }
        else
        {
            try
            {
                $type = new Type();
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

        $resp = $response
            ->withStatus($data['code'])
            ->withHeader('Content-Type', 'application/json; charset=utf8');
        $resp->getBody()
            ->write(json_encode($data));

        return $resp;
    }



    public function put(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);
        $content = $request->getParsedBody();

        $type = Type::find($id);

        if($id==0){
            $data = ApiErrors::BadRequest();
        }
        else if(!isset($content['nom']))
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

        $resp = $response
            ->withStatus($data['code'])
            ->withHeader('Content-Type', 'application/json; charset=utf8');
        $resp->getBody()
            ->write(json_encode($data));

        return $resp;

    }

}