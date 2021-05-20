<?php


namespace PrismGestion\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PrismGestion\Models\Cours;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response as ResponseAlias;

class CoursController extends Controller
{

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

    public function postCourse(Request $request, Response $response, $args): ResponseAlias
    {
        return $this->CourseRepository->postCourse($request, $response, $args);
    }
}