<?php


namespace PrismGestion\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PrismGestion\Models\Cours;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response as ResponseAlias;

/**
 * Class CoursController
 * @author damou
 * @package PrismGestion\Controllers
 */
class CoursController extends Controller
{

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function getAllCours(Request $request, Response $response, $args)
    {
        $queryParams = $request->getQueryParams();
        if (isset($queryParams['intitule'])) {
            try {
                $cours = cours::where('intitule', 'LIKE', '%' . $queryParams['intitule'] . '%')->firstOrFail();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'cours' => $cours
                ];
            } catch (ModelNotFoundException $modelNotFoundException) {
                $data = $this->ApiErrors->NotFound($modelNotFoundException->getCode());
            }
        } elseif (isset($queryParams['salle'])) {
            try {
                $cours = cours::where('salle', '=', $queryParams['salle'])->firstOrFail();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'cours' => $cours
                ];
            } catch (ModelNotFoundException $modelNotFoundException) {
                $data = $this->ApiErrors->NotFound($modelNotFoundException->getCode());
            }
        } else {
            $cours = cours::all();
            $data = [
                'type' => "success",
                'code' => 200,
                'cours' => $cours
            ];
        }
        return $this->ResponseWriter->responseWriter($response, $data);
    }

    /**
     * Ta daronne s'est un homme wallah.
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseAlias
     * @throws ModelNotFoundException si la ressource demande exists pas.
     */
    public function getCoursUUID(Request $request, Response $response, $args): ResponseAlias
    {
        try {
            $cours = cours::where('uuid', '=', $args['uuid'])->firstOrFail();
            $prof = $cours->professeur;
            $data = [
                'type' => "success",
                'code' => 200,
                'cours' => $cours
            ];
        } catch (ModelNotFoundException $modelNotFoundException) {
            $data = $this->ApiErrors->NotFound($modelNotFoundException->getCode());
        }
        return $this->ResponseWriter->responseWriter($response, $data);
    }

    /**
     * Supprime un cours existant.
     * @param Request $request
     * @param Response $response
     * @param $args $exemple cdhckjdhcdhkcd
     * @return mixed
     * @author damou
     */
    public function deleteCourse(Request $request, Response $response, $args)
    {
        try {
            $cours = cours::where('uuid', '=', $args['uuid'])->firstOrFail();
            $cours->delete();
            $data = [
                'type' => "success",
                'code' => 204
            ];
        } catch (ModelNotFoundException $modelNotFoundException) {
            $data = $this->ApiErrors->NotFound($modelNotFoundException->getCode());
        }
        return $this->ResponseWriter->responseWriter($response, $data);
    }


    /**
     * Cree une nouvelle entite cours.
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseAlias
     */
    public function postCourse(Request $request, Response $response, $args): ResponseAlias
    {
        return $this->CourseRepository->postCourse($request, $response, $args);
    }
}