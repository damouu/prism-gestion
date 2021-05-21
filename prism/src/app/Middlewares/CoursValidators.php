<?php

namespace PrismGestion\Middlewares;

use Exception;
use JsonException;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CoursValidators
 * @author damou
 * @package PrismGestion\Middlewares
 */
class CoursValidators
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     * @throws JsonException
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
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
                ->write(json_encode('Une ou plusieurs validations n\'ont pas été respectées.', JSON_THROW_ON_ERROR));
            return $resp;
        }
        return $next($request, $response);
    }

}