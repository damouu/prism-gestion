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


    }

    public function put(Request $request, Response $response, $args)
    {

    }

    public function delete(Request $request, Response $response, $args)
    {

    }
}