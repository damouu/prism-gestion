<?php

namespace PrismGestion\Controllers;



use Illuminate\Database\Capsule\Manager as DB;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\Materiel;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExemplaireController extends Controller
{

    public function getAll(Request $request, Response $response, $args)
    {

        $params = [
            'nb' => intval($request->getQueryParam('nb',10)),
            'page' => intval($request->getQueryParam('page',1)),
        ];

        try
        {

            $exemplaire = Exemplaire::select('id','materiel','reference','prix_achat','fournisseur','num_serie','stockage','url','etat','num_ex','date_achat','date_modif');

            $elementCounter = $exemplaire->count();
            if( (($params['nb']*($params['page']))>$elementCounter) || ($params['nb']<=0) || ($params['page']<=0) )
            {
                $params['nb'] = 10;
                $params['page'] = 1;
            }
            if($params['nb'])
                $exemplaire = $exemplaire->take($params['nb']);
            if($params['page'])
                $exemplaire = $exemplaire->skip($params['nb']*($params['page']-1));
            $pageMax = ceil($elementCounter/$params['nb']);
            $exemplaire = $exemplaire->get();

            $data = [
                'type' => "success",
                'code' => 200,
                'ressource' => [
                    'total' => $elementCounter,
                    'nb_per_page' => $params['nb'],
                    'page' => $params['page'],
                    'page_max' => $pageMax,
                ],
                'exemplaires' => $exemplaire
            ];

        }
        catch(\Exception $e)
        {
            var_dump($e);die;
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
                $exemplaire = Exemplaire::with('fournisseur')->find($id);

                if(empty($exemplaire)){
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'exemplaire' => $exemplaire
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


    public function getExemplairesMateriel(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        try
        {
            $exemplaire = Exemplaire::select('id','materiel','reference','prix_achat','fournisseur','num_serie','url','stockage','etat','num_ex','date_achat','date_modif')->where('id','=',$id)->with('materiel');

            $exemplaire = $exemplaire->first();

            $data = [
                'type' => "success",
                'code' => 200,
                'exemplaires' => $exemplaire
            ];
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

        $exemplaire = Exemplaire::find($id);

        if(empty($exemplaire))
        {
            $data = ApiErrors::NotFound($request->getUri());
        }
        else {
            try{
                $exem = Exemplaire::find($id);
                $mat = Materiel::find($exem->materiel);
                $count = Exemplaire::where('materiel','=',$id)->count();
                DB::transaction( function () use ($exem,$mat,$count) {
                    $mat->nb_ex = ($count)-1;
                    $exem->delete();
                    $mat->save();
                });

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'message' => 'l\'exemplaire ' . $exemplaire->id . ' a bien été supprimé.'
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }


        return ResponseWriter::ResponseWriter($response, $data);
    }



    public function post(Request $request, Response $response, $args)
    {

        $content = $request->getParsedBody();
        $materiel = Materiel::where('id','=',$content['materiel'])->first();

        if( !isset($content['materiel']) || !isset($content['reference']) || !isset($content['etat']) || !isset($content['fournisseur']) || !isset($content['prix_achat']) || !isset($content['num_serie']) || !isset($content['date_achat']) || !isset($content['stockage']) )
        {
            $data = ApiErrors::BadRequest();
        }
        else if(empty($materiel))
        {
            $data = ApiErrors::NotFound($request->getUri());
        }else{
            try
            {
                DB::transaction(function () use ($content) {

                    $count = Exemplaire::where('materiel','=',$content['materiel'])->withTrashed()->count();
                    $count1 = Exemplaire::where('materiel','=',$content['materiel'])->count();
                    $exemplaire = new Exemplaire();
                    $exemplaire->materiel = $content['materiel'];
                    $exemplaire->reference = $content['reference'];
                    $exemplaire->fournisseur = $content['fournisseur'];
                    $exemplaire->prix_achat = $content['prix_achat'];
                    $exemplaire->num_serie = $content['num_serie'];
                    $exemplaire->stockage = $content['stockage'];
                    if(isset($content["url"])){
                        $exemplaire->url = $content['url'];
                    }
                    $exemplaire->etat = $content['etat'];
                    $exemplaire->date_achat = $content['date_achat'];
                    $exemplaire->num_ex = ($count)+1;
                    $exemplaire->save();
                    $materiel = Materiel::where('id','=',$content['materiel'])->first();
                    $materiel->nb_ex = ($count1)+1;
                    $materiel->save();
                });

                $data = [
                    'type' => "success",
                    'code' => 201,
                    'exemplaire' => 'reussi'
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

        $exemplaire = Exemplaire::find($id);


        if( !isset($content['materiel']) || !isset($content['reference']) || !isset($content['etat']) || !isset($content['fournisseur']) || !isset($content['prix_achat']) || !isset($content['num_serie']) || !isset($content['stockage']) || !isset($content['date_achat']) )
        {
            $data = ApiErrors::BadRequest();
        }
        else if(empty($exemplaire))
        {
            $materiel = Materiel::where('id','=',$content['materiel'])->first();

            if(empty($materiel))
            {
                $data = ApiErrors::NotFound($request->getUri());
            }else{
                try
                {
                    DB::transaction(function () use ($content) {

                        $count = Exemplaire::where('materiel','=',$content['materiel'])->withTrashed()->count();
                        $count1 = Exemplaire::where('materiel','=',$content['materiel'])->count();
                        $exemplaire = new Exemplaire();
                        $exemplaire->materiel = $content['materiel'];
                        $exemplaire->reference = $content['reference'];
                        $exemplaire->etat = $content['etat'];
                        $exemplaire->fournisseur = $content['fournisseur'];
                        $exemplaire->prix_achat = $content['prix_achat'];
                        $exemplaire->num_serie = $content['num_serie'];
                        $exemplaire->stockage = $content['stockage'];
                        if(isset($content["url"])){
                            $exemplaire->url = $content['url'];
                        }
                        $exemplaire->num_ex = ($count)+1;
                        $exemplaire->save();
                        $materiel = Materiel::where('id','=',$content['materiel'])->first();
                        $materiel->nb_ex = ($count1)+1;
                        $materiel->save();
                    });

                    $data = [
                        'type' => "success",
                        'code' => 201,
                        'exemplaire' => 'reussi'
                    ];
                }
                catch(\Exception $e)
                {
                    $data = ApiErrors::InternalError();
                }
            }
        }
        else
        {
            try{

                $exemplaire->materiel = $content['materiel'];
                $exemplaire->reference = $content['reference'];
                $exemplaire->etat = $content['etat'];
                $exemplaire->stockage = $content['stockage'];
                $exemplaire->fournisseur = $content['fournisseur'];
                $exemplaire->prix_achat = $content['prix_achat'];
                $exemplaire->num_serie = $content['num_serie'];
                if(isset($content["url"])){
                    $exemplaire->url = $content['url'];
                }
                $exemplaire->save();

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'exemplaire' => $exemplaire
                ];
            }
            catch(\Exception$e)
            {
                $data = ApiErrors::InternalError();
            }
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function patch(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);
        $etat = $request->getParsedBody();

        $exemplaire = Exemplaire::find($id);

        if(!isset($etat['etat'])){
            $data = ApiErrors::BadRequest();
        }
        else if(empty($exemplaire))
        {
            $data = ApiErrors::NotFound($request->getUri());
        }
        else
        {
            try
            {
                $exemplaire->etat = $etat['etat'];
                $exemplaire->save();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'exemplaire' => $exemplaire
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