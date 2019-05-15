<?php

namespace PrismGestion\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Type;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExemplaireController extends Controller
{

    public function getAll(Request $request, Response $response, $args)
    {

        $params = [
            'select' => $request->getQueryParam('select','service')
        ];

        try
        {
            if($params['select'] === 'service')
            {
                $exemplaire = Exemplaire::get();

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'exemplaires' => $exemplaire
                ];
            }
            else if ($params['select'] === 'reforme')
            {
                $exemplaire = Exemplaire::onlyTrashed()->get();
                foreach($exemplaire as $row) {
                    $row->materiel = Materiel::withTrashed()->find($row->materiel);
                    $row->materiel['type'] = Type::withTrashed()->find($row->materiel['type']);
                };

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reformes' => $exemplaire
                ];
            }
            else {
                $data = ApiErrors::BadRequest();
            }

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
        $params = [
            'select' => $request->getQueryParam('select','service')
        ];
        $id = intval($args['id']);

        if(!is_null($id) )
        {
            try {
                if($params['select'] === 'service')
                {
                    $exemplaire = Exemplaire::with('fournisseur')->with('materiel')->find($id);

                    if (empty($exemplaire)) {
                        $data = ApiErrors::NotFound($request->getUri());
                    } else {
                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'exemplaire' => $exemplaire
                        ];
                    }

                }
                else if ($params['select'] === 'reforme')
                {

                    $exemplaire = Exemplaire::onlyTrashed()->with('fournisseur')->find($id);
                    $exemplaire->materiel = Materiel::withTrashed()->find($exemplaire->materiel);
                    $exemplaire->materiel['type'] = Type::withTrashed()->find($exemplaire->materiel['type']);

                    if (empty($exemplaire)) {
                        $data = ApiErrors::NotFound($request->getUri());
                    } else {
                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'exemplaire' => $exemplaire
                        ];
                    }
                }
                else
                {
                    $data = ApiErrors::BadRequest();
                }
            } catch (\Exception $e) {
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
            $exemplaire = Exemplaire::where('id','=',$id)
                ->with('materiel')
                ->first();

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

                DB::transaction( function () use ($exem,$mat) {
                    $exem->delete();
                    $mat->nb_ex = Exemplaire::where('materiel','=',$exem->materiel)->count();
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

        if(
            !isset($content['materiel'])
            || !isset($content['reference'])
            || !isset($content['etat'])
            || !isset($content['fournisseur'])
            || !isset($content['prix_ht'])
            || !isset($content['prix_ttc'])
            || !isset($content['num_serie'])
            || !isset($content['financement'])
            || !isset($content['bon_commande'])
            || !isset($content['date_achat'])
            || !isset($content['stockage'])
        )
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
                    $exemplaire = new Exemplaire();
                    $exemplaire->materiel = $content['materiel'];
                    $exemplaire->reference = $content['reference'];
                    $exemplaire->fournisseur = $content['fournisseur'];
                    $exemplaire->prix_ht = $content['prix_ht'];
                    $exemplaire->prix_ttc = $content['prix_ttc'];
                    $exemplaire->num_serie = $content['num_serie'];
                    $exemplaire->financement = $content['financement'];
                    $exemplaire->bon_commande = $content['bon_commande'];
                    $exemplaire->stockage = $content['stockage'];
                    if(isset($content["url"])){
                        $exemplaire->url = $content['url'];
                    }
                    if(isset($content["immobilisation"])){
                        $exemplaire->immobilisation = $content['immobilisation'];
                    }
                    $exemplaire->etat = $content['etat'];
                    $exemplaire->date_achat = $content['date_achat'];
                    $exemplaire->num_ex = ($count)+1;
                    $exemplaire->save();

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


        if(
            !isset($content['materiel'])
            || !isset($content['reference'])
            || !isset($content['etat'])
            || !isset($content['fournisseur'])
            || !isset($content['prix_ht'])
            || !isset($content['prix_ttc'])
            || !isset($content['num_serie'])
            || !isset($content['financement'])
            || !isset($content['bon_commande'])
            || !isset($content['stockage'])
            || !isset($content['date_achat'])
        )
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
                        $exemplaire->prix_ht = $content['prix_ht'];
                        $exemplaire->prix_ttc = $content['prix_ttc'];
                        $exemplaire->num_serie = $content['num_serie'];
                        $exemplaire->financement = $content['financement'];
                        $exemplaire->bon_commande = $content['bon_commande'];
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

        $params = [
            'select' => $request->getQueryParam('select','service')
        ];
        $id = intval($args['id']);

        if(!is_null($id) )
        {
            try {

                if($params['select'] === 'service')
                {
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
                        $exemplaire->etat = $etat['etat'];
                        $exemplaire->save();
                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'exemplaire' => $exemplaire
                        ];
                    }

                }
                else if ($params['select'] === 'reforme')
                {
                    $exemplaire = Exemplaire::onlyTrashed()->find($id);
                    $materiel = Materiel::withTrashed()->find($exemplaire->materiel);
                    $type = Type::withTrashed()->find($materiel->type);

                    if (empty($exemplaire)) {
                        $data = ApiErrors::NotFound($request->getUri());
                    } else {

                        DB::transaction(function () use ($exemplaire, $materiel, $type) {
                            $exemplaire->date_sortie = null;
                            if(!empty($materiel->date_suppression)){
                                $materiel->date_suppression = null;
                                $materiel->save();
                            }
                            if(!empty($type->date_suppression)){
                                $type->date_suppression = null;
                                $type->save();
                            }
                            $exemplaire->save();
                        });

                        $data = [
                            'type' => "success",
                            'code' => 200,
                            'exemplaire' => $exemplaire
                        ];
                    }
                }
                else
                {
                    $data = ApiErrors::BadRequest();
                }
            } catch (\Exception $e) {
                $data = ApiErrors::NotFound($request->getUri());
            }

        }
        else
        {
            $data = ApiErrors::BadRequest();
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }

}