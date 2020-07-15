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

    public function userSignIn(Request $request, Response $response, $args)
    {
        $user = users::where("NetID", '=', $request->getQueryParams("NetID"))->first();
        if (!empty($user)) {
            $token = JWT::encode(
                ['iss' => 'https://iutnc-resamat.univ-lorraine.fr',
                    'aud' => 'https://iutnc-resamat.univ-lorraine.fr',
                    'iat' => time(),
                    'exp' => time() + 3600,
                    'uNetID' => $user->NetID,
                    'lvl' => $user->NetID_Access_level
                ],
                "secret", 'HS512');
            $data = [
                'type' => "success",
                'code' => 200,
                'JWT' => $token
            ];
            return ResponseWriter::ResponseWriter($response, $data);
        } else {
            $token = JWT::encode(
                ['iss' => 'https://iutnc-resamat.univ-lorraine.fr',
                    'aud' => 'https://iutnc-resamat.univ-lorraine.fr',
                    'iat' => time(),
                    'exp' => time() + 3600,
                    'uNetID' => $request->getQueryParams("NetID"),
                    'lvl' => 1
                ],
                "secret", 'HS512');
            $data = [
                'type' => "success",
                'code' => 200,
                'JWT' => $token
            ];
            return ResponseWriter::ResponseWriter($response, $data);
        }


    }

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

}