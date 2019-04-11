<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materiel extends Model
{

    use SoftDeletes;
    protected $table = 'materiel';



    public function type()
    {
        return $this->hasMany('PrismGestion\Models\Type', 'id');
    }

}