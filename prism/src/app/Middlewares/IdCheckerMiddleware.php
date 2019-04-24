<?php

namespace PrismGestion\Middlewares;

use PrismGestion\Errors\ApiErrors;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IdCheckerMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {

        $routeParam = $request->getAttribute('route')->getArguments('id');

        if(intval($routeParam['id'])==0)
        {
            $data = ApiErrors::BadRequest();
            return ResponseWriter::ResponseWriter($response,$data);
        }

        $response = $next($request, $response);
        return $response;
    }
}