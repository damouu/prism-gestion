<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{

    protected $table = 'professeur';
    public $timestamps = false;
    protected $hidden = array('id');

    public function reservationProfesseur()
    {
        return $this->belongsTo('PrismGestion\Models\Reservation', 'responsable_projet');
    }

}