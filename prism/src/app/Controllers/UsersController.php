<?php

namespace PrismGestion\Controllers;


use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Users as users;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Illuminate\Database\Eloquent\ModelNotFoundException;


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

    public function updateUser(Request $request, Response $response, $args)
    {
        $id = intval($args['id']);
        $content = $request->getParsedBody();
        $postValidateNetID_Access_level = v::notOptional()->intType()->between(1, 3);
        if (is_int($id)) {
            if (isset($content['NetID_Access_level'])) {
                try {
                    $user = users::findOrFail($args['id']);
                    $postValidateNetID_Access_level->assert($content['NetID_Access_level']);
                    $user->NetID_Access_level = filter_var($content['NetID_Access_level'], FILTER_VALIDATE_INT);
                    $user->save();
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'user' => $user
                    ];
                } catch (ModelNotFoundException $ex) {
                    $data = [
                        'type' => "error",
                        'code' => 405,
                    ];
                } catch (NestedValidationException $e) {
                    $data = ApiErrors::ValidationError($e->getMessages());
                } catch (\Exception $e) {
                    $data = ApiErrors::InternalError();
                }
            } else {
                $data = ApiErrors::BadRequest();
            }
        } else {
            $data = ApiErrors::BadRequest();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function deleteUser(Request $request, Response $response, $args)
    {
        $id = intval($args['id']);
        if (!is_int($id)) {
            $data = ApiErrors::BadRequest();
        } else {
            $user = users::findOrFail($args['id']);
            if (!empty($user)) {
                try {
                    $user->delete();
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'user' => $user
                    ];
                } catch (\Exception $e) {
                    $data = ApiErrors::InternalError();
                }
            } else {
                $data = ApiErrors::NotFound($request->getUri());
            }
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }


    public function postUser(Request $request, Response $response, $args)
    {
        $content = $request->getParsedBody();
        $postValidateNetID = v::notOptional()->StringType()->length(1, 128);
        $postValidateNetID_Access_level = v::notOptional()->intType()->between(1, 3);
        if (!isset($content['NetID_Access_level']) || !isset($content['NetID'])) {
            $data = ApiErrors::BadRequest();
        } else {
            try {
                $postValidateNetID->assert($content['NetID']);
                $postValidateNetID_Access_level->assert($content['NetID_Access_level']);

                $user = new users();
                $user->NetID = filter_var($content['NetID'], FILTER_SANITIZE_STRING);
                $user->NetID_Access_level = filter_var($content['NetID_Access_level'], FILTER_VALIDATE_INT);
                $user->save();

                $data = [
                    'type' => "success",
                    'code' => 200,
                    'user' => $user
                ];
            } catch (NestedValidationException $e) {
                $data = ApiErrors::ValidationError($e->getMessages());
            } catch (\Exception $e) {
                $data = ApiErrors::InternalError();
            }
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

}