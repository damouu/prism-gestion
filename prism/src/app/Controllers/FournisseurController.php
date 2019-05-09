<?php

namespace PrismGestion\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\Fournisseur;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FournisseurController extends Controller
{
    public function getAll(Request $request, Response $response, $args)
    {

        try
        {

            $fournisseurs = Fournisseur::select('id','nom','adresse','ville','code_postal','site_web','mail','tel','commercial_nom','commercial_prenom','commercial_prenom','commercial_mail','commercial_tel','date_creation');

            $elementCounter = $fournisseurs->count();

            $fournisseurs = $fournisseurs->get();

            $data = [
                'type' => "success",
                'code' => 200,
                'ressource' => [
                    'total' => $elementCounter,
                ],
                'fournisseurs' => $fournisseurs
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
                $fournisseur = Fournisseur::find($id);
                if(empty($fournisseur)){
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'fournisseur' => $fournisseur
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


    public function getFournisseursExemplaire(Request $request, Response $response, $args)
    {

    }

    public function post(Request $request, Response $response, $args)
    {

        $content = $request->getParsedBody();

        if(!isset($content['nom']) || !isset($content['adresse']) || !isset($content['ville']) || !isset($content['code_postal']) || !isset($content['mail']) || !isset($content['tel'])){
            $data = ApiErrors::BadRequest();
        }
        else
        {
            $fournisseur = new Fournisseur();
            $fournisseur->nom = $content['nom'];
            $fournisseur->adresse = $content['adresse'];
            $fournisseur->ville = $content['ville'];
            $fournisseur->code_postal = $content['code_postal'];
            $fournisseur->mail = $content['mail'];
            $fournisseur->tel = $content['tel'];

            if(isset($content['site_web']))
                $fournisseur->site_web = $content['site_web'];

            if(isset($content['commercial_nom']))
                $fournisseur->site_web = $content['commercial_nom'];

            if(isset($content['commercial_prenom']))
                $fournisseur->site_web = $content['commercial_prenom'];

            if(isset($content['commercial_tel']))
                $fournisseur->site_web = $content['commercial_tel'];

            if(isset($content['commercial_mail']))
                $fournisseur->site_web = $content['commercial_mail'];

            try{
                $fournisseur->save();
                $data = [
                    'type' => "success",
                    'code' => 201,
                    'fournisseur' => $fournisseur
                ];
            }
            catch (\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }

        };

        return ResponseWriter::ResponseWriter($response, $data);

    }

    public function put(Request $request, Response $response, $args)
    {

    }

    public function delete(Request $request, Response $response, $args)
    {

    }
}