<?php


namespace PrismGestion\Middlewares;

use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class QueryParamNetID
{
    public function checkQueryParamNetID(Request $request, Response $response, callable $next)
    {
        if (array_key_exists('NetID', $request->getQueryParams())) {
            return $next($request, $response);
        } else {
            $data = [
                'type' => "error",
                'code' => 401,
                'message' => "no param NetID found in query"
            ];
            return ResponseWriter::ResponseWriter($response, $data);
        }
    }

}