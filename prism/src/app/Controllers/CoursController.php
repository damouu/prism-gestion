<?php


namespace PrismGestion\Controllers;

use PrismGestion\Models\Cours;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CoursController extends Controller
{
    public function getAllCours(Request $request, Response $response, $args): \Slim\Http\Response
    {
        $cours = cours::find(1);
        $data = [
            'type' => "success",
            'code' => 200,
            'users' => $cours
        ];
        return ResponseWriter::ResponseWriter($response, $data);
    }
}