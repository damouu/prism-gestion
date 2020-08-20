<?php

namespace PrismGestion\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use DateTime;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Departement;
use PrismGestion\Models\Etudiant;
use PrismGestion\Models\Exemplaire;
use PrismGestion\Models\FicheReservation;
use PrismGestion\Models\ficheReservationscheReservation;
use PrismGestion\Models\Materiel;
use PrismGestion\Models\Reservation;
use PrismGestion\Models\Type;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;


class ReservationController extends Controller
{

    public function getAll(Request $request, Response $response, $args)
    {

        $params = [
            'select' => $request->getQueryParam('select', 'all')
        ];

        if ($params['select'] === 'all') {
            try {
                $reservation = Reservation::with('departement')
                    ->with('professeur')
                    ->orderBy('created_at', 'desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation
                ];
            } catch (\Exception $e) {
                $data = ApiErrors::InternalError();
            }

        } else if ($params['select'] === 'ficheReservationsches') {
            try {
                $reservation = Reservation::with('ficheReservationsche_resa')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation
                ];
            } catch (\Exception $e) {
                $data = ApiErrors::InternalError();
            }
        } else {
            $data = ApiErrors::NotFound($request->getUri());
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function getOne(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        try {
            $reservation = Reservation::with('departement')
                ->with('professeur')
                ->with('groupe')
                ->with(['fiche_resa' => function ($q) {
                    $q->with(['exemplaire' => function ($w) {
                        $w->with('materiel');
                    }]);
                }])
                ->find($id);
            if (empty($reservation)) {
                $data = ApiErrors::NotFound($request->getUri());
            } else {
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservation' => $reservation
                ];
            }
        } catch (\Exception $e) {
            $data = ApiErrors::InternalError();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function post(Request $request, Response $response, $args)
    {
        $content = $request->getParsedBody();

        if (isset($content['groupes'])) {
            $etudiants = $content['groupes'];
        } else {
            $etudiants = null;
        };


        if (!isset($content['responsable_projet'])
            || !isset($content['departement'])
            || !isset($content['matiere'])
            || !isset($content['annee'])
            || !isset($content['dep_groupe'])) {
            $data = ApiErrors::BadRequest();
        } else {
            try {
                $content['responsable_projet'] = trim($content['responsable_projet']);
                $content['departement'] = trim($content['departement']);
                $content['matiere'] = trim($content['matiere']);
                $content['annee'] = trim($content['annee']);
                $content['dep_groupe'] = trim($content['dep_groupe']);

                if (isset($content['observation'])) {
                    $content['observation'] = trim($content['observation']);
                } else {
                    $content['observation'] = null;
                }

                $reservation = new Reservation();
                $reservation->responsable_projet = $content['responsable_projet'];
                $reservation->departement = $content['departement'];
                $reservation->matiere = $content['matiere'];
                $reservation->annee = $content['annee'];
                $reservation->dep_groupe = $content['dep_groupe'];
                $reservation->observation = $content['observation'];

                if (is_null($etudiants)) {
                    $reservation->save();
                } else {
                    DB::transaction(function () use ($content, $etudiants, $reservation) {

                        foreach ($etudiants as $key => $row) {
                            if ($key === 0) {
                                $etudiant = Etudiant::ficheReservationsnd($row);
                                $etudiant->groupe()->save($reservation, ['referent' => 1]);
                            } else {
                                $etudiant = Etudiant::ficheReservationsnd($row);
                                $etudiant->groupe()->save($reservation, ['referent' => 0]);
                            }
                        }
                    });
                }

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservation' => $reservation,
                ];

            } catch (\Exception $e) {
                $data = ApiErrors::InternalError();
            }
        }

        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function postOneFeuille(Request $request, Response $response, $args)
    {
        $id = (int)trim($args['id']);
        $content = $request->getParsedBody();
        try {
            if (!isset($content['date_depart']) || !isset($content['date_retour']) || !isset($content['rendu']) || !isset($content['observation']) || !isset($content['exemplaires'])) {
                $data = ApiErrors::BadRequest();
            } else {
                $reservation = Reservation::findOrFail($id);
                $test = $reservation->fiche_resa()->count();
                $date = date('ym');
                $idfeuille = $date . '-' . $id . ($test + 1);
                $ficheReservationsche_resa = new FicheReservation();
                $ficheReservationsche_resa->id = $idfeuille;
                $ficheReservationsche_resa->reservation = $id;
                $ficheReservationsche_resa->date_depart = trim($content['date_depart']);
                $ficheReservationsche_resa->date_retour = trim($content['date_retour']);
                $ficheReservationsche_resa->observation = trim($content['observation']);
                $ficheReservationsche_resa->rendu = $content['rendu'];
                foreach ($content['exemplaires'] as $row) {
                    $exemplaire = Exemplaire::findOrFail($row);
                    $exemplaire->fiche_resa()->save($ficheReservationsche_resa, ['emprunt' => 1, 'rendu' => 0]);
                }
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'ficheReservationsche_reservation' => $ficheReservationsche_resa,
                ];

            }

        } catch (\Exception $e) {
            $data = [
                'type' => "error",
                'code' => 500,
                'reservation' => $e,
            ];
            $data = ApiErrors::InternalError();
        }

        return ResponseWriter::ResponseWriter($response, $data);


    }

    public function getEmprunt(Request $request, Response $response, $args)
    {
        try {
            $ficheReservation = FicheReservation::findOrFail($args['id']);
            $empruntsFicheReservation = $ficheReservation->exemplaire->where('reservation_exemplaire.emprunt', '=', 1);
            if ($empruntsFicheReservation->isEmpty() == true) {
                $data = [
                    'type' => "message",
                    'code' => 200,
                    'emprunts' => "cette fiche reservation ne contient pas d'emprunt",
                ];
            } else {
                $emprunt = array();
                $utilisateur = Reservation::find($ficheReservation->reservation);
                $departement = Departement::find($utilisateur->departement);
                $informationsFicheResa["informationsFicheResa"]["id"] = $ficheReservation->id;
                $informationsFicheResa["informationsFicheResa"]["date_depart"] = $ficheReservation->date_depart;
                $informationsFicheResa["informationsFicheResa"]["date_retour"] = $ficheReservation->retour;
                $informationsFicheResa["informationsFicheResa"]['rendu'] = $ficheReservation->rendu;
                $informationsFicheResa["informationsFicheResa"]["rendu"] = $ficheReservation->observation;
                $informationsUtilisateur = array();
                $informationsUtilisateur["nom"] = $utilisateur->professeur->nom;
                $informationsUtilisateur["departement"] = $departement->nom;
                $informationsFicheResa["utilisateur"][] = $informationsUtilisateur;
                foreach ($empruntsFicheReservation as $empruntFicheReservation) {
                    $informationsMateriel = array();
                    $materiel = Materiel::find($empruntFicheReservation->id);
                    $informationsMateriel["id_exemplaire"] = $empruntFicheReservation->id;
                    $informationsMateriel["id_nateriel"] = $empruntFicheReservation->materiel;
                    $informationsMateriel["exemplaire"] = $empruntFicheReservation->reference;
                    $informationsMateriel["constructeur"] = $materiel->constructeur;
                    $informationsMateriel["modele"] = $materiel->modele;
                    $informationsMateriel["description"] = $materiel->description;
                    $informationsFicheResa["materiels"][] = $informationsMateriel;
                }
                $emprunt[] = $informationsFicheResa;
                $data = [
                    'type' => "collection",
                    'code' => 200,
                    'emprunts' => $emprunt,
                ];
            }
            return ResponseWriter::ResponseWriter($response, $data);
        } catch (\Exception $exception) {
            $data = ApiErrors::InternalError();
        }
    }

    public function getFicheReservations(Request $request, Response $response, $args)
    {
        $emprunt = array();
        $ficheReservations = FicheReservation::whereHas('exemplaire', function ($q) {
            $q->where('emprunt', '=', 1);
        })->get();
        $nbEmprunts = count($ficheReservations);
        foreach ($ficheReservations as $ficheReservation) {
            $informationsFicheResa = array();
            $informationsFicheResa["informationsFicheResa"]["id"] = $ficheReservation->id;
            $informationsFicheResa["informationsFicheResa"]["date_depart"] = $ficheReservation->date_depart;
            $informationsFicheResa["informationsFicheResa"]["date_retour"] = $ficheReservation->date_retour;
            $informationsFicheResa["informationsFicheResa"]["rendu"] = $ficheReservation->rendu;
            $informationsFicheResa["informationsFicheResa"]["observation"] = $ficheReservation->observation;
            $emprunt[] = $informationsFicheResa;
        }
        $data = [
            'type' => "collection",
            'code' => 200,
            'nombre fiche d_emprunts ' => $nbEmprunts,
            'emprunts' => $emprunt,
        ];
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function getExemplaireFicheResa(Request $request, Response $response, $args)
    {
        $emprunt = array();
        $ficheReservation = FicheReservation::findOrFail($args['id']);
        $exemplaires = $ficheReservation->exemplaire()->wherePivot('emprunt', '=', 1)->get();
        foreach ($exemplaires as $exemplaire) {
            $informationsMateriel = array();
            $materiel = Materiel::find($exemplaire->materiel);
            $informationsMateriel["id_exemplaire"] = $exemplaire->id;
            $informationsMateriel["id_materiel"] = $exemplaire->materiel;
            $informationsMateriel["exemplaire"] = $exemplaire->reference;
            $informationsMateriel["constructeur"] = $materiel->constructeur;
            $informationsMateriel["modele"] = $materiel->modele;
            $informationsMateriel["description"] = $materiel->description;
            $informationsFicheResa["materiels"][] = $informationsMateriel;
        }
        $emprunt[] = $informationsFicheResa;
        $data = ['type' => "collection",
            'code' => 200,
            'emprunts' => $emprunt,];
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function updateRetourExemplaire(Request $request, Response $response, $args)
    {
        $exemplaire = Exemplaire::findOrFail($args['id']);
        $ficheReservations = $exemplaire->fiche_resa;
        foreach ($ficheReservations as $ficheReservation) {
            $exemplaire->fiche_resa()->updateExistingPivot($ficheReservation->id, ['emprunt' => 0, 'rendu' => 1]);
        }
        $data = [
            'type' => "collection",
            'code' => 200,
            'text' => "cet exemplaire de matériel a bien été retourné.",
        ];
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function retourExemplairesFeuilles(Request $request, Response $response, $args)
    {
        try {
            $ficheReservation = FicheReservation::findOrFail($args['id']);
            $exemplaires = $ficheReservation->exemplaire;
            foreach ($exemplaires as $exemplaire) {
                $exemplaire->fiche_resa()->updateExistingPivot($ficheReservation->id, ['emprunt' => 0, 'rendu' => 1]);
            }
            $ficheReservation->rendu = 1;
            $ficheReservation->save();
            $data = [
                'type' => "collection",
                'code' => 200,
                'text' => "les exemplaires ont bien été retourné.",
            ];
        } catch (\Exception $exception) {
            $data = ApiErrors::InternalError();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function deleteFicheReservation(Request $request, Response $response, $args)
    {
        try {
            $ficheReservation = FicheReservation::findOrFail($args['id']);
            $ficheReservation->delete();
            $data = [
                'type' => "message",
                'code' => 200,
                'text' => "fiche de reservation" . " " . $ficheReservation->id . ' ' . "supprime",
            ];
        } catch (\Exception $exception) {
            $data = ApiErrors::InternalError();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }
}