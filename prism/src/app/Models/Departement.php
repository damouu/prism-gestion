<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{

    protected $table = 'departement';
    public $timestamps = false;


    public function reservation()
    {
        return $this->belongsTo('PrismGestion\Mdoels\Reservation', 'id');
    }

}