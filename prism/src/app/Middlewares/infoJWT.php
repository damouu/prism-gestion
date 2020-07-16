<?php


namespace PrismGestion\Middlewares;

use Firebase\JWT\JWT;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class infoJWT
{

    public function infoJWT(Request $request, Response $response, callable $next)
    {
        try {
            $token = $request->getAttribute("token");
            $data = [
                'type' => "message",
                'code' => 200,
                'data' => $token
            ];
            return ResponseWriter::ResponseWriter($response, $data);
        } catch (ExpiredException $e) {
            $data = [
                'type' => "error",
                'code' => 401,
                'message' => "this JWT has expired."
            ];
            return ResponseWriter::ResponseWriter($response, $data);
        }
    }
}