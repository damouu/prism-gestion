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
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use stdClass;

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

        $postValidateMateriel = v::notOptional()->intType()->length(1,11);
        $postValidateReference = v::notOptional()->StringType()->length(1,20);
        $postValidateEtat = v::notOptional()->stringType();
        $postValidateFournisseur = v::notOptional()->intType()->length(1,11);
        $postValidatePrixHt = v::notOptional()->floatType();
        $postValidatePrixTtc = v::notOptional()->floatType();
        $postValidateNumSerie = v::notOptional()->StringType()->length(1,128);
        $postValidateFinancement = v::notOptional()->StringType()->length(1,128);
        $postValidateBonCommande = v::notOptional()->StringType()->length(1,128);
        $postValidateImmobilisation = v::Optional(v::StringType()->length(1,128));
        $postValidateUrl = v::Optional(v::url());
        $postValidateDateAchat = v::date('Y-m-d');
        $postValidateStockage = v::notOptional()->StringType()->length(1,128);

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

            $postValeur = new stdClass;
            $postValeur->materiel = $content['materiel'];
            $postValeur->reference = $content['reference'];
            $postValeur->etat = trim($content['etat']);
            $postValeur->fournisseur = $content['fournisseur'];
            $postValeur->prix_ht = $content['prix_ht'];
            $postValeur->prix_ttc = $content['prix_ttc'];
            $postValeur->num_serie = trim($content['num_serie']);
            $postValeur->financement = trim($content['financement']);
            $postValeur->bon_commande = trim($content['bon_commande']);
            if(isset($content["immobilisation"])){
                $postValeur->immobilisation = trim($content['immobilisation']);
            }
            else
            {
                $postValeur->immobilisation = null;
            }
            if(isset($content["url"])){
                $postValeur->url = trim($content['url']);
            }
            else
            {
                $postValeur->url = null;
            }
            $postValeur->date_achat = trim($content['date_achat']);
            $postValeur->stockage = trim($content['stockage']);

            try
            {

                $postValidateMateriel->assert($postValeur->materiel);
                $postValidateReference->assert($postValeur->reference);
                $postValidateEtat->assert($postValeur->etat);
                $postValidateFournisseur->assert($postValeur->fournisseur);
                $postValidatePrixHt->assert($postValeur->prix_ht);
                $postValidatePrixTtc->assert($postValeur->prix_ttc);
                $postValidateNumSerie->assert($postValeur->num_serie);
                $postValidateFinancement->assert($postValeur->financement);
                $postValidateBonCommande->assert($postValeur->bon_commande);
                $postValidateImmobilisation->assert($postValeur->immobilisation);
                $postValidateUrl->assert($postValeur->url);
                $postValidateDateAchat->assert($postValeur->date_achat);
                $postValidateStockage->assert($postValeur->stockage);

                DB::transaction(function () use ($postValeur) {

                    $count = Exemplaire::where('materiel','=',$postValeur->materiel)->withTrashed()->count();
                    $exemplaire = new Exemplaire();
                    $exemplaire->materiel = $postValeur->materiel;
                    $exemplaire->reference = $postValeur->reference;
                    $exemplaire->fournisseur = $postValeur->fournisseur;
                    $exemplaire->prix_ht = $postValeur->prix_ht;
                    $exemplaire->prix_ttc = $postValeur->prix_ttc;
                    $exemplaire->num_serie = $postValeur->num_serie;
                    $exemplaire->financement = $postValeur->financement;
                    $exemplaire->bon_commande = $postValeur->bon_commande;
                    $exemplaire->stockage = $postValeur->stockage;
                    $exemplaire->url = $postValeur->url;
                    $exemplaire->immobilisation = $postValeur->immobilisation;
                    $exemplaire->etat = $postValeur->etat;
                    $exemplaire->date_achat = $postValeur->date_achat;
                    $exemplaire->num_ex = ($count)+1;
                    $exemplaire->save();
                });

                $data = [
                    'type' => "success",
                    'code' => 201,
                    'exemplaire' => 'reussi'
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



    public function put(Request $request, Response $response, $args)
    {

        $id = intval($args['id']);
        $content = $request->getParsedBody();

        $exemplaire = Exemplaire::find($id);

        $putValidateMateriel = v::notOptional()->intType()->length(1,11);
        $putValidateReference = v::notOptional()->StringType()->length(1,20);
        $putValidateEtat = v::notOptional()->stringType();
        $putValidateFournisseur = v::notOptional()->intType()->length(1,11);
        $putValidatePrixHt = v::notOptional()->floatType();
        $putValidatePrixTtc = v::notOptional()->floatType();
        $putValidateNumSerie = v::notOptional()->StringType()->length(1,128);
        $putValidateFinancement = v::notOptional()->StringType()->length(1,128);
        $putValidateBonCommande = v::notOptional()->StringType()->length(1,128);
        $putValidateImmobilisation = v::Optional(v::StringType()->length(1,128));
        $putValidateUrl = v::Optional(v::url());
        $putValidateDateAchat = v::date('Y-m-d');
        $putValidateStockage = v::notOptional()->StringType()->length(1,128);


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

            $putValeur = new stdClass;
            $putValeur->materiel = $content['materiel'];
            $putValeur->reference = $content['reference'];
            $putValeur->etat = trim($content['etat']);
            $putValeur->fournisseur = $content['fournisseur'];
            $putValeur->prix_ht = $content['prix_ht'];
            $putValeur->prix_ttc = $content['prix_ttc'];
            $putValeur->num_serie = trim($content['num_serie']);
            $putValeur->financement = trim($content['financement']);
            $putValeur->bon_commande = trim($content['bon_commande']);
            if(isset($content["immobilisation"])){
                $putValeur->immobilisation = trim($content['immobilisation']);
            }
            else
            {
                $putValeur->immobilisation = null;
            }
            if(isset($content["url"])){
                $putValeur->url = trim($content['url']);
            }
            else
            {
                $putValeur->url = null;
            }
            $putValeur->date_achat = trim($content['date_achat']);
            $putValeur->stockage = trim($content['stockage']);

            $materiel = Materiel::where('id','=',$putValeur->materiel)->first();

            if(empty($materiel))
            {
                $data = ApiErrors::NotFound($request->getUri());
            }else{
                try
                {
                    $putValidateMateriel->assert($putValeur->materiel);
                    $putValidateReference->assert($putValeur->reference);
                    $putValidateEtat->assert($putValeur->etat);
                    $putValidateFournisseur->assert($putValeur->fournisseur);
                    $putValidatePrixHt->assert($putValeur->prix_ht);
                    $putValidatePrixTtc->assert($putValeur->prix_ttc);
                    $putValidateNumSerie->assert($putValeur->num_serie);
                    $putValidateFinancement->assert($putValeur->financement);
                    $putValidateBonCommande->assert($putValeur->bon_commande);
                    $putValidateImmobilisation->assert($putValeur->immobilisation);
                    $putValidateUrl->assert($putValeur->url);
                    $putValidateDateAchat->assert($putValeur->date_achat);
                    $putValidateStockage->assert($putValeur->stockage);

                    DB::transaction(function () use ($putValeur) {

                        $count = Exemplaire::where('materiel','=',$putValeur->materiel)->withTrashed()->count();
                        $exemplaire = new Exemplaire();
                        $exemplaire->materiel = $putValeur->materiel;
                        $exemplaire->reference = $putValeur->reference;
                        $exemplaire->fournisseur = $putValeur->fournisseur;
                        $exemplaire->prix_ht = $putValeur->prix_ht;
                        $exemplaire->prix_ttc = $putValeur->prix_ttc;
                        $exemplaire->num_serie = $putValeur->num_serie;
                        $exemplaire->financement = $putValeur->financement;
                        $exemplaire->bon_commande = $putValeur->bon_commande;
                        $exemplaire->stockage = $putValeur->stockage;
                        $exemplaire->url = $putValeur->url;
                        $exemplaire->immobilisation = $putValeur->immobilisation;
                        $exemplaire->etat = $putValeur->etat;
                        $exemplaire->date_achat = $putValeur->date_achat;
                        $exemplaire->num_ex = ($count)+1;
                        $exemplaire->save();
                    });

                    $data = [
                        'type' => "success",
                        'code' => 201,
                        'exemplaire' => 'reussi'
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
            $putValeur = new stdClass;
            $putValeur->materiel = $content['materiel'];
            $putValeur->reference = $content['reference'];
            $putValeur->etat = trim($content['etat']);
            $putValeur->fournisseur = $content['fournisseur'];
            $putValeur->prix_ht = $content['prix_ht'];
            $putValeur->prix_ttc = $content['prix_ttc'];
            $putValeur->num_serie = trim($content['num_serie']);
            $putValeur->financement = trim($content['financement']);
            $putValeur->bon_commande = trim($content['bon_commande']);
            if(isset($content["immobilisation"])){
                $putValeur->immobilisation = trim($content['immobilisation']);
            }
            else
            {
                $putValeur->immobilisation = null;
            }
            if(isset($content["url"])){
                $putValeur->url = trim($content['url']);
            }
            else
            {
                $putValeur->url = null;
            }
            $putValeur->date_achat = trim($content['date_achat']);
            $putValeur->stockage = trim($content['stockage']);

            try{

                $putValidateReference->assert($putValeur->reference);
                $putValidateEtat->assert($putValeur->etat);
                $putValidateFournisseur->assert($putValeur->fournisseur);
                $putValidatePrixHt->assert($putValeur->prix_ht);
                $putValidatePrixTtc->assert($putValeur->prix_ttc);
                $putValidateNumSerie->assert($putValeur->num_serie);
                $putValidateFinancement->assert($putValeur->financement);
                $putValidateBonCommande->assert($putValeur->bon_commande);
                $putValidateImmobilisation->assert($putValeur->immobilisation);
                $putValidateUrl->assert($putValeur->url);
                $putValidateDateAchat->assert($putValeur->date_achat);
                $putValidateStockage->assert($putValeur->stockage);

                $exemplaire->reference = $putValeur->reference;
                $exemplaire->fournisseur = $putValeur->fournisseur;
                $exemplaire->prix_ht = $putValeur->prix_ht;
                $exemplaire->prix_ttc = $putValeur->prix_ttc;
                $exemplaire->num_serie = $putValeur->num_serie;
                $exemplaire->financement = $putValeur->financement;
                $exemplaire->bon_commande = $putValeur->bon_commande;
                $exemplaire->stockage = $putValeur->stockage;
                $exemplaire->url = $putValeur->url;
                $exemplaire->immobilisation = $putValeur->immobilisation;
                $exemplaire->etat = $putValeur->etat;
                $exemplaire->date_achat = $putValeur->date_achat;
                $exemplaire->save();

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'exemplaire' => $exemplaire
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




    public function patch(Request $request, Response $response, $args)
    {

        $params = [
            'select' => $request->getQueryParam('select','service')
        ];
        $id = intval($args['id']);

        if(!is_null($id) )
        {
            try {

                if ($params['select'] === 'reforme')
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