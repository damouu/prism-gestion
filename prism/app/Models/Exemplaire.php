<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;

class Exemplaire extends Model
{

    protected $table = 'type';
    public $timestamps = false;



    public function materiel()
    {
        return $this->hasMany('PrismGestion\Models\Materiel', 'id');
    }


}