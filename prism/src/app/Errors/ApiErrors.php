<?php


namespace PrismGestion\Errors;

class ApiErrors
{
    public function NotFound($uri)
    {
        $data = [
            'type' => 'error',
            'code' => 404,
            'message' => "Ressource " . $uri . " non trouvée."
        ];
        return $data;
    }

    public static function BadRequest()
    {
        $data = [
            'type' => 'error',
            'code' => 405,
            'message' => "La méthode n'est pas correctement utilisée."
        ];
        return $data;
    }

    public static function InternalError()
    {
        $data = [
            'type' => 'error',
            'code' => 500,
            'message' => "Internal Server Error."
        ];
        return $data;
    }

    public static function ValidationError($error)
    {
        $data = [
            'type' => 'error',
            'code' => 405,
            'message' => 'Une ou plusieurs validations n\'ont pas été respectées.',
            'validation' => $error
        ];
        return $data;
    }

    public static function deletedError()
    {
        $data = [
            'type' => 'error',
            'code' => 403,
            'message' => "Impossible de supprimer la ressource: en cours d'utilisation",
        ];
        return $data;
    }
}