<?php

namespace PrismGestion\Controllers;

use DateTime;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Reservation;
use PrismGestion\Utils\ResponseWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class ReservationController extends Controller
{

    public function getAll(Request $request, Response $response, $args)
    {

        $params = [
            'select' => $request->getQueryParam('select','all')
        ];

        if($params['select'] === 'all')
        {
            try{
                $reservation = Reservation::get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }

        }
        else if($params['select'] === 'past')
        {
            $dateT = new Datetime();
            try{
                $reservation = Reservation::whereDate('date_retour',$dateT['date'])->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }

    public function getOne(Request $request, Response $response, $args) {
        $id = intval($args['id']);
        if(is_int($id))
        {
            try {
                $reservation = Reservation::find($id);
                if(empty($reservation))
                {
                    $data = ApiErrors::NotFound($request->getUri());
                }
                else
                {
                    $data = [
                        'type' => "success",
                        'code' => 200,
                        'reservation' => $reservation
                    ];
                }
            }
            catch (\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else
        {
            $data = ApiErrors::BadRequest();
        }
        return ResponseWriter::ResponseWriter($response, $data);
    }
}