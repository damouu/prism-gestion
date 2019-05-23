<?php

namespace PrismGestion\Controllers;

use DateTime;
use PrismGestion\Errors\ApiErrors;
use PrismGestion\Models\Exemplaire;
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
                $reservation = Reservation::orderBy('created_at','desc')->get();
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
                $reservation = Reservation::where('date_retour','<=',$dateT)->orderBy('created_at','desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation,
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else if($params['select'] === 'ongoing')
        {
            $dateT = new Datetime();
            try{
                $reservation = Reservation::where('date_retour','>=',$dateT)->where('date_depart','<=',$dateT)->orderBy('created_at','desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation,
                ];
            }
            catch(\Exception $e)
            {
                $data = ApiErrors::InternalError();
            }
        }
        else if($params['select'] === 'future')
        {
            $dateT = new Datetime();
            try{
                $reservation = Reservation::where('date_depart','>',$dateT)->orderBy('created_at','desc')->get();
                $data = [
                    'type' => "success",
                    'code' => 200,
                    'reservations' => $reservation,
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
        $id = $args['id'];
        try {
            $reservation = Reservation::with('exemplaire')->has;
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
        return ResponseWriter::ResponseWriter($response, $data);
    }


}