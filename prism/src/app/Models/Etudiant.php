<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{

    protected $table = 'etudiant';
    public $timestamps = false;


    public function reservationEtudiante()
    {
        return $this->belongsTo('PrismGestion\Mdoels\Reservation', 'id');
    }


}