<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{

    protected $table = 'type';
    public $timestamps = false;



    public function materiel()
    {
        return $this->belongsTo('PrismGestion\Models\Materiel', 'id');
    }


}