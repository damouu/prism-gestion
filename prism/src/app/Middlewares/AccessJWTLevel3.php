<?php


namespace PrismGestion\Middlewares;

use Firebase\JWT\JWT;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccessJWTLevel3
{
    public function AccessJWTLevel3(Request $request, Response $response, callable $next)
    {
        try {
            $token = $request->getAttribute("token");
            if ($token->lvl == 3) {
                return $next($request, $response);
            } else {
                $data = [
                    'type' => "error",
                    'code' => 401,
                    'message' => "you do not have the access to this ressource."
                ];
                return ResponseWriter::ResponseWriter($response, $data);
            }
            return $next($request, $response);
        } catch (ExpiredException $e) {
            $data = [
                'type' => "error",
                'code' => 401,
                'message' => "this JWT has expired."
            ];
            return ResponseWriter::ResponseWriter($response, $data);
        } catch (SignatureInvalidException $e) {
            return $response;
        } catch (BeforeValidException $e) {
            return $response;
        } catch (\UnexpectedValueException $e) {
            return $response;
        }
    }
}