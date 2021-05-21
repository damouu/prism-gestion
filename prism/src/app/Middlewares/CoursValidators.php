<?php

namespace PrismGestion\Middlewares;

use Exception;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CoursValidators
 * @package PrismGestion\Middlewares
 */
class CoursValidators
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {

        $content = $request->getParsedBody();
        $postValidationIntitule = Validator::notOptional()->StringType()->length(1, 200);
        $postValidationSalle = Validator::notOptional()->intType()->length(1, 5);
        try {
            $postValidationIntitule->assert($content['intitule']);
            $postValidationSalle->assert($content['salle']);
        } catch (Exception$exception) {
            $resp = $response
                ->withStatus(405)
                ->withHeader('Content-Type', 'application/json; charset=utf8');
            $resp->getBody()
                ->write(json_encode('Une ou plusieurs validations n\'ont pas été respectées.'));
            return $resp;
        }
        return $next($request, $response);
    }

}