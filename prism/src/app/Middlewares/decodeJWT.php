<?php

namespace PrismGestion\Middlewares;

use Firebase\JWT\JWT;
use \lbs\common\bootstrap\Eloquent;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class decodeJWT
{
    function decodeJWT(Request $rq, Response $rs, callable $next)
    {
        try {
            $h = $rq->getHeader('Authorization')[0];
            $tokenString = sscanf($h, "Bearer %s")[0];
            $secret = file_get_contents('../../src/conf/jwt-secret', true);
            $token = JWT::decode($tokenString, $secret, ['HS512']);
            $rq = $rq->withAttribute("token", $token);
            return $next($rq, $rs);
        } catch (ExpiredException $e) {
            return $rs;
        } catch (SignatureInvalidException $e) {
            return $rs;
        } catch (BeforeValidException $e) {
            return $rs;
        } catch (\UnexpectedValueException $e) {
            return $rs;
        }
    }

}