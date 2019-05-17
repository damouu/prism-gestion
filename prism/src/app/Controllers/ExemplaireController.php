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

    /**
     * @api {get} /exemplaires getAll
     * @apiGroup Exemplaires
     * @apiName getAll
     * @apiVersion 0.1.0
     * @apiDescription Récupère tous les exemplaires selon le paramètre passé dans le ?select.
     * Paramètres possibles:
     * ?select=reforme
     * ?select=service
     *
     * @apiSuccess {String} type Type de la réponse
     * @apiSuccess {String} code Code de la réponse
     * @apiSuccess {Object[]} exemplaires Liste d'exemplaires
     * @apiSuccess {Number} exemplaires.id Identifiant primaire de l'exemplaire
     * @apiSuccess {Number} exemplaires.materiel Identifiant étranger du matériel
     * @apiSuccess {String} exemplaires.reference Référence UL de l'exemplaire
     * @apiSuccess {Number} exemplaires.fournisseur Identifiant étranger du fournisseur
     * @apiSuccess {Number} exemplaires.prix_ht Prix Hors Taxes d'achat de l'exemplaire
     * @apiSuccess {Number} exemplaires.prix_ttc Prix Toutes Taxes Comprises d'achat de l'exemplaire
     * @apiSuccess {String} exemplaires.num_serie Numéro de série constructeur de l'exemplaire
     * @apiSuccess {String} exemplaires.url URL de référence de l'objet
     * @apiSuccess {String} exemplaires.stockage Lieu de stockage de l'exemplaire
     * @apiSuccess {String} exemplaire.etat Etat de l'exemplaire
     * @apiSuccess {Number} exemplaires.num_ex Numéro d'exemplaire
     * @apiSuccess {String} exemplaires.financement Organisme de financement d'achat de l'exemplaire
     * @apiSuccess {String} exemplaires.bon_commande Numéro de bon de commande
     * @apiSuccess {String} exemplaires.immobilisation Numéro de fiche d'immobilisation
     * @apiSuccess {Date} exemplaires.date_achat Date d'achat de l'exemplaire
     * @apiSuccess {Date} exemplaires.date_modif Date de dernière modification
     * @apiSuccess {Date} exemplaires.date_Sortie Date de sortie de l'exemplaire
     *
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *           "type": "success",
     *           "code": 200,
     *           "exemplaires": [
     *                               {
     *                                   "id": 2,
     *                                   "materiel": 1,
     *                                   "reference": "ESOSHAJSD",
     *                                   "fournisseur": 2,
     *                                   "prix_ht": "200.00",
     *                                   "prix_ttc": "200.00",
     *                                   "num_serie": "AZEYI738-3739",
     *                                   "url": "http://www.google.fr",
     *                                   "stockage": "701",
     *                                   "etat": "réparation",
     *                                   "num_ex": 2,
     *                                   "financement": "Tech de co",
     *                                   "bon_commande": "345678AZ",
     *                                   "immobilisation": "4567890987",
     *                                   "date_achat": "2019-04-18",
     *                                   "date_modif": null,
     *                                   "date_sortie": null
     *                               },
     *                               {
     *                                   "id": 3,
     *                                   "materiel": 1,
     *                                   "reference": "ESOSHDDD",
     *                                   "fournisseur": 1,
     *                                   "prix_ht": "200.00",
     *                                   "prix_ttc": "200.00",
     *                                   "num_serie": "AZEYTUE-YSJ",
     *                                   "url": "http://www.google.fr",
     *                                   "stockage": "bureau",
     *                                   "etat": "emprunté",
     *                                   "num_ex": 3,
     *                                   "financement": "UL",
     *                                   "bon_commande": "TYUI567890",
     *                                   "immobilisation": "45654567890",
     *                                   "date_achat": "2019-04-18",
     *                                   "date_modif": null,
     *                                   "date_sortie": null
     *                               }
     *                          ]
     *      }
     *
     * @apiError BadRequest La méthode n'est pas correctement utilisée.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "La méthode n'est pas correctement utilisée."
     *      }
     *
     * @apiError NotFound Ressource non trouvée.
     * @apiErrorExample Not Found:
     *      HTTP/1.1 404 Not Found
     *      {
     *           "type": "error",
     *           "code": 404,
     *           "message": "Ressource non trouvée."
     *      }
     */

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
            $data = ApiErrors::NotFound($request->getUri());
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }


    /**
     * @api {get} /exemplaires/:id getOne
     * @apiGroup Exemplaires
     * @apiName getOne
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Identifiant primaire de l'exemplaire
     * @apiDescription Récupère un exemplaire selon le paramètre passé dans le ?select.
     * Paramètres possibles:
     * ?select=reforme
     * ?select=service
     *
     * @apiSuccess {String} type Type de la réponse
     * @apiSuccess {String} code Code de la réponse
     * @apiSuccess {Object[]} exemplaires Liste d'exemplaires
     * @apiSuccess {Number} exemplaires.id Identifiant primaire de l'exemplaire
     * @apiSuccess {Object[]} exemplaires.materiel Objet étranger du matériel
     * @apiSuccess {String} exemplaires.reference Référence UL de l'exemplaire
     * @apiSuccess {Object[]} exemplaires.fournisseur Objet étranger du fournisseur
     * @apiSuccess {Number} exemplaires.prix_ht Prix Hors Taxes d'achat de l'exemplaire
     * @apiSuccess {Number} exemplaires.prix_ttc Prix Toutes Taxes Comprises d'achat de l'exemplaire
     * @apiSuccess {String} exemplaires.num_serie Numéro de série constructeur de l'exemplaire
     * @apiSuccess {String} exemplaires.url URL de référence de l'objet
     * @apiSuccess {String} exemplaires.stockage Lieu de stockage de l'exemplaire
     * @apiSuccess {String} exemplaire.etat Etat de l'exemplaire
     * @apiSuccess {Number} exemplaires.num_ex Numéro d'exemplaire
     * @apiSuccess {String} exemplaires.financement Organisme de financement d'achat de l'exemplaire
     * @apiSuccess {String} exemplaires.bon_commande Numéro de bon de commande
     * @apiSuccess {String} exemplaires.immobilisation Numéro de fiche d'immobilisation
     * @apiSuccess {Date} exemplaires.date_achat Date d'achat de l'exemplaire
     * @apiSuccess {Date} exemplaires.date_modif Date de dernière modification
     * @apiSuccess {Date} exemplaires.date_Sortie Date de sortie de l'exemplaire
     *
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *           "type": "success",
     *           "code": 200,
     *           "exemplaire": {
     *                             "id": 2,
     *                             "materiel": {...},
     *                             "reference": "ESOSHAJSD",
     *                             "fournisseur": {...},
     *                             "prix_ht": "200.00",
     *                             "prix_ttc": "200.00",
     *                             "num_serie": "AZEYI738-3739",
     *                             "url": "http://www.google.fr",
     *                             "stockage": "701",
     *                             "etat": "réparation",
     *                             "num_ex": 2,
     *                             "financement": "Tech de co",
     *                             "bon_commande": "345678AZ",
     *                             "immobilisation": "4567890987",
     *                             "date_achat": "2019-04-18",
     *                             "date_modif": null,
     *                             "date_sortie": null
     *                          }
     *
     *      }
     *
     * @apiError BadRequest La méthode n'est pas correctement utilisée.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "La méthode n'est pas correctement utilisée."
     *      }
     *
     * @apiError NotFound Ressource non trouvée.
     * @apiErrorExample Not Found:
     *      HTTP/1.1 404 Not Found
     *      {
     *           "type": "error",
     *           "code": 404,
     *           "message": "Ressource non trouvée."
     *      }
     */
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


    /**
     * @api {get} /exemplaires/:id/materiels getExemplaireMateriel
     * @apiGroup Exemplaires
     * @apiName getExemplaireMateriel
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Identifiant primaire de l'exemplaire
     * @apiDescription Récupère un exemplaire avec les informations de materiel
     *
     * @apiSuccess {String} type Type de la réponse
     * @apiSuccess {String} code Code de la réponse
     * @apiSuccess {Object[]} exemplaires Liste d'exemplaires
     * @apiSuccess {Number} exemplaires.id Identifiant primaire de l'exemplaire
     * @apiSuccess {Object[]} exemplaires.materiel Objet étranger du matériel
     * @apiSuccess {Number} exemplaires.materiel.id Identifiant primaire du matériel
     * @apiSuccess {String} exemplaires.materiel.constructeur Constructeur du matériel
     * @apiSuccess {String} exemplaires.materiel.modele Modèle du matériel
     * @apiSuccess {String} exemplaires.materiel.description Description du matériel
     * @apiSuccess {Number} exemplaires.materiel.nb_ex Nombre d'exemplaires du matériel
     * @apiSuccess {Number} exemplaires.materiel.type Identifiant étranger du type
     * @apiSuccess {Date} exemplaires.materiel.date_creation Date de création du matériel
     * @apiSuccess {Date} exemplaires.materiel.date_modification Date de dernière modification du matériel
     * @apiSuccess {Date} exemplaires.materiel.date_suppression Date de suppression du matériel
     * @apiSuccess {String} exemplaires.reference Référence UL de l'exemplaire
     * @apiSuccess {Number} exemplaires.fournisseur Identifiant étranger du fournisseur
     * @apiSuccess {Number} exemplaires.prix_ht Prix Hors Taxes d'achat de l'exemplaire
     * @apiSuccess {Number} exemplaires.prix_ttc Prix Toutes Taxes Comprises d'achat de l'exemplaire
     * @apiSuccess {String} exemplaires.num_serie Numéro de série constructeur de l'exemplaire
     * @apiSuccess {String} exemplaires.url URL de référence de l'objet
     * @apiSuccess {String} exemplaires.stockage Lieu de stockage de l'exemplaire
     * @apiSuccess {String} exemplaire.etat Etat de l'exemplaire
     * @apiSuccess {Number} exemplaires.num_ex Numéro d'exemplaire
     * @apiSuccess {String} exemplaires.financement Organisme de financement d'achat de l'exemplaire
     * @apiSuccess {String} exemplaires.bon_commande Numéro de bon de commande
     * @apiSuccess {String} exemplaires.immobilisation Numéro de fiche d'immobilisation
     * @apiSuccess {Date} exemplaires.date_achat Date d'achat de l'exemplaire
     * @apiSuccess {Date} exemplaires.date_modif Date de dernière modification
     * @apiSuccess {Date} exemplaires.date_Sortie Date de sortie de l'exemplaire
     *
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *           "type": "success",
     *           "code": 200,
     *           "exemplaire": {
     *                             "id": 2,
     *                             "materiel": {
     *                                           "id": 1,
     *                                           "constructeur": "Canon",
     *                                           "modele": "EOS X5",
     *                                           "description": "appareil photo classique",
     *                                           "nb_ex": 2,
     *                                           "type": 1,
     *                                           "date_creation": "2019-04-16",
     *                                           "date_modification": "2019-05-10",
     *                                           "date_suppression": null
     *                                           },
     *                             "reference": "ESOSHAJSD",
     *                             "fournisseur": 1,
     *                             "prix_ht": "200.00",
     *                             "prix_ttc": "200.00",
     *                             "num_serie": "AZEYI738-3739",
     *                             "url": "http://www.google.fr",
     *                             "stockage": "701",
     *                             "etat": "réparation",
     *                             "num_ex": 2,
     *                             "financement": "Tech de co",
     *                             "bon_commande": "345678AZ",
     *                             "immobilisation": "4567890987",
     *                             "date_achat": "2019-04-18",
     *                             "date_modif": null,
     *                             "date_sortie": null
     *                          }
     *
     *      }
     *
     * @apiError BadRequest La méthode n'est pas correctement utilisée.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "La méthode n'est pas correctement utilisée."
     *      }
     *
     * @apiError NotFound Ressource non trouvée.
     * @apiErrorExample Not Found:
     *      HTTP/1.1 404 Not Found
     *      {
     *           "type": "error",
     *           "code": 404,
     *           "message": "Ressource non trouvée."
     *      }
     */
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




    /**
     * @api {delete} /exemplaires/:id delete
     * @apiGroup Exemplaires
     * @apiName delete
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Identifiant primaire de l'exemplaire
     * @apiDescription Supprime un exemplaire
     *
     * @apiSuccess {String} type Type de la réponse
     * @apiSuccess {String} code Code de la réponse
     * @apiSuccess {String} message Message réponse
     *
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *           "type": "success",
     *           "code": 200,
     *           "message": "L'exemplaire :id a bien été supprimé."
     *
     *      }
     *
     * @apiError (500) InternalError Internal Server Error..
     * @apiErrorExample Internal Error:
     *      HTTP/1.1 500 Internal Error
     *      {
     *           "type": "error",
     *           "code": 500,
     *           "message": "Internal Server Error."
     *      }
     *
     * @apiError NotFound Ressource non trouvée.
     * @apiErrorExample Not Found:
     *      HTTP/1.1 404 Not Found
     *      {
     *           "type": "error",
     *           "code": 404,
     *           "message": "Ressource non trouvée."
     *      }
     */
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
                $exemplaire->delete();
                $exemplaire->save();

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



    /**
     * @api {post} /exemplaires post
     * @apiGroup Exemplaires
     * @apiName post
     * @apiVersion 0.1.0
     *
     * @apiDescription Crée un nouvel exemplaire avec les informations fournies
     *
     * @apiParam {Number} materiel Identifiant primaire d'un matériel
     * @apiParam {String} reference Référence UL de l'exemplaire
     * @apiParam {String} etat Etat de l'exemplaire
     * @apiParam {Number} fournisseur Identifiant primaire d'un fournisseur
     * @apiParam {Number} prix_ttc Prix Toutes Taxes Comprises de l'exemplaire
     * @apiParam {Number} prix_ht Prix Hors Taxes de l'exemplaire
     * @apiParam {String} num_serie Numéro de série constructeur
     * @apiParam {String} financement Financement de l'exemplaire
     * @apiParam {String} bon_commande Numéro de bon de commande de l'exemplaire
     * @apiParam {Date} date_achat Date d'achat de l'exemplaire
     * @apiParam {String} stockage Lieu de stockage de l'exemplaire
     * @apiParam (optional) {String} url Url de référence de l'exemplaire
     * @apiParam (optional) {String} immobilisation Numéro de fiche d'immobilisation
     *
     * @apiSuccess {String} type Type de la réponse
     * @apiSuccess {String} code Code de la réponse
     * @apiSuccess {String} Message de réussite
     *
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *           "type": "success",
     *           "code": 200,
     *           "exemplaire": "reussi"
     *      }
     *
     * @apiError BadRequest La méthode n'est pas correctement utilisée.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "La méthode n'est pas correctement utilisée."
     *      }
     *
     * @apiError ValidationError Une ou plusieurs validations n'ont pas été respectées.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "Une ou plusieurs validations n'ont pas été respectées."
     *           "validation": "erreurs à afficher"
     *      }
     *
     * @apiError NotFound Ressource non trouvée.
     * @apiErrorExample Not Found:
     *      HTTP/1.1 404 Not Found
     *      {
     *           "type": "error",
     *           "code": 404,
     *           "message": "Ressource non trouvée."
     *      }
     *
     * @apiError (500) InternalError Internal Server Error..
     * @apiErrorExample Internal Error:
     *      HTTP/1.1 500 Internal Error
     *      {
     *           "type": "error",
     *           "code": 500,
     *           "message": "Internal Server Error."
     *      }
     */
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
        $postValidateFinancement = v::Optional(v::StringType()->length(1,128));
        $postValidateBonCommande = v::Optional(v::StringType()->length(1,128));
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
            $postValeur->materiel = (int)$content['materiel'];
            $postValeur->reference = $content['reference'];
            $postValeur->etat = trim($content['etat']);
            $postValeur->fournisseur = $content['fournisseur'];
            $postValeur->prix_ht = (float)$content['prix_ht'];
            $postValeur->prix_ttc = (float)$content['prix_ttc'];
            $postValeur->num_serie = trim($content['num_serie']);
            if(isset($content["financement"])){
                $postValeur->financement = trim($content['financement']);
            }
            else
            {
                $postValeur->financement = null;
            }
            if(isset($content["bon_commande"])){
                $postValeur->bon_commande = trim($content['bon_commande']);
            }
            else
            {
                $postValeur->bon_commande = null;
            }
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


    /**
     * @api {put} /exemplaires/:id put
     * @apiGroup Exemplaires
     * @apiName put
     * @apiVersion 0.1.0
     *
     * @apiDescription Modifie ou crée un exemplaire avec les informations fournies
     *
     * @apiParam {Number} materiel Identifiant primaire d'un matériel
     * @apiParam {String} reference Référence UL de l'exemplaire
     * @apiParam {String} etat Etat de l'exemplaire
     * @apiParam {Number} fournisseur Identifiant primaire d'un fournisseur
     * @apiParam {Number} prix_ttc Prix Toutes Taxes Comprises de l'exemplaire
     * @apiParam {Number} prix_ht Prix Hors Taxes de l'exemplaire
     * @apiParam {String} num_serie Numéro de série constructeur
     * @apiParam {String} financement Financement de l'exemplaire
     * @apiParam {String} bon_commande Numéro de bon de commande de l'exemplaire
     * @apiParam {Date} date_achat Date d'achat de l'exemplaire
     * @apiParam {String} stockage Lieu de stockage de l'exemplaire
     * @apiParam (optional) {String} url Url de référence de l'exemplaire
     * @apiParam (optional) {String} immobilisation Numéro de fiche d'immobilisation
     *
     * @apiSuccess (201) {String} type Type de la réponse
     * @apiSuccess (201) {String} code Code de la réponse
     * @apiSuccess (201) {String} Message de réussite
     *
     * @apiSuccessExample SuccessAdd-Response:
     *      HTTP/1.1 201 CREATED
     *      {
     *           "type": "success",
     *           "code": 201,
     *           "exemplaire": "reussi"
     *      }
     *
     * @apiSuccess {String} type Type de la réponse
     * @apiSuccess {String} code Code de la réponse
     * @apiSuccess {String} Message de réussite
     *
     * @apiSuccessExample SuccessEdit-Response:
     *      HTTP/1.1 200 OK
     *      {
     *           "type": "success",
     *           "code": 200,
     *           "exemplaire": "reussi"
     *      }
     *
     * @apiError BadRequest La méthode n'est pas correctement utilisée.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "La méthode n'est pas correctement utilisée."
     *      }
     *
     * @apiError ValidationError Une ou plusieurs validations n'ont pas été respectées.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "Une ou plusieurs validations n'ont pas été respectées."
     *           "validation": "erreurs à afficher"
     *      }
     *
     * @apiError NotFound Ressource non trouvée.
     * @apiErrorExample Not Found:
     *      HTTP/1.1 404 Not Found
     *      {
     *           "type": "error",
     *           "code": 404,
     *           "message": "Ressource non trouvée."
     *      }
     *
     * @apiError (500) InternalError Internal Server Error..
     * @apiErrorExample Internal Error:
     *      HTTP/1.1 500 Internal Error
     *      {
     *           "type": "error",
     *           "code": 500,
     *           "message": "Internal Server Error."
     *      }
     */
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
        $putValidateFinancement = v::Optional(v::StringType()->length(1,128));
        $putValidateBonCommande = v::Optional(v::StringType()->length(1,128));
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
            if(isset($content["bon_commande"])){
                $putValeur->bon_commande = trim($content['bon_commande']);
            }
            else
            {
                $putValeur->bon_commande = null;
            }
            if(isset($content["financement"])){
                $putValeur->financement = trim($content['financement']);
            }
            else
            {
                $putValeur->financement = null;
            }
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
            $putValeur->materiel = (int)$content['materiel'];
            $putValeur->reference = $content['reference'];
            $putValeur->etat = trim($content['etat']);
            $putValeur->fournisseur = $content['fournisseur'];
            $putValeur->prix_ht = (float)$content['prix_ht'];
            $putValeur->prix_ttc = (float)$content['prix_ttc'];
            $putValeur->num_serie = trim($content['num_serie']);
            if(isset($content["bon_commande"])){
                $putValeur->bon_commande = trim($content['bon_commande']);
            }
            else
            {
                $putValeur->bon_commande = null;
            }
            if(isset($content["financement"])){
                $putValeur->financement = trim($content['financement']);
            }
            else
            {
                $putValeur->financement = null;
            }
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



    /**
     * @api {patch} /exemplaires/:id patch
     * @apiGroup Exemplaires
     * @apiName patch
     * @apiVersion 0.1.0
     *
     * @apiDescription Remet en service un exemplaire supprimé grace au parametre ?select=reforme
     *
     * @apiSuccess {String} type Type de la réponse
     * @apiSuccess {String} code Code de la réponse
     * @apiSuccess {String} Message de réussite
     *
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *           "type": "success",
     *           "code": 200,
     *           "exemplaire": "reussi"
     *      }
     *
     * @apiError BadRequest La méthode n'est pas correctement utilisée.
     * @apiErrorExample Bad Request:
     *      HTTP/1.1 405 Bad Request
     *      {
     *           "type": "error",
     *           "code": 405,
     *           "message": "La méthode n'est pas correctement utilisée."
     *      }
     *
     * @apiError NotFound Ressource non trouvée.
     * @apiErrorExample Not Found:
     *      HTTP/1.1 404 Not Found
     *      {
     *           "type": "error",
     *           "code": 404,
     *           "message": "Ressource non trouvée."
     *      }
     */
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
                                'exemplaire' => "reussi"
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