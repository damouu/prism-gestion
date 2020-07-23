<?php

namespace PrismGestion\Controllers;

use PrismGestion\Controllers\Controller;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Users as users;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;


class UsersController extends Controller
{


    public function printJWT(Request $request, Response $response, $args)
    {
        $token = $request->getAttribute("token");
        $data = [
            'type' => "success",
            'code' => 200,
            'JWT' => $token
        ];
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function getAllUsers(Request $request, Response $response, $args)
    {
        $users = users::all();
        $data = [
            'type' => "success",
            'code' => 200,
            'users' => $users
        ];
        return ResponseWriter::ResponseWriter($response, $data);
    }

}