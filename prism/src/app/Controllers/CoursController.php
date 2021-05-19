<?php


namespace PrismGestion\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PrismGestion\Models\Cours;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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

    public function getCoursUUID(Request $request, Response $response, $args): \Slim\Http\Response
    {
        $cours = cours::where('uuid', '=', $args['uuid'])->firstOrFail();
        $prof = $cours->professeur;
        $data = [
            'type' => "success",
            'code' => 200,
            'prof' => $prof,
            'cours' => $cours
        ];
        return ResponseWriter::ResponseWriter($response, $data);
    }
}