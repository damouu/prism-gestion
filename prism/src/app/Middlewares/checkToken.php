<?php


namespace PrismGestion\Middlewares;

use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class checkToken
{
    public function checkToken(Request $request, Response $response, callable $next)
    {
        $header = $request->getHeader('Authorization')[0];
        $tokenString = sscanf($header, "Bearer %s")[0];
        if (!empty($tokenString)) {
            $request = $request->withAttribute("token", $tokenString);
            return $next($request, $response);
        } else {
            $data = [
                'type' => "error",
                'code' => 401,
                'message' => "no token found"
            ];
            return ResponseWriter::ResponseWriter($response, $data);
        }
    }
}