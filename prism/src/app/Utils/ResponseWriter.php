<?php

namespace PrismGestion\Utils;

class ResponseWriter
{
    public function responseWriter($response, $data)
    {
        $resp = $response
            ->withStatus($data['code'])
            ->withHeader('Content-Type', 'application/json; charset=utf8');
        $resp->getBody()
            ->write(json_encode($data));

        return $resp;
    }
}