<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materiel extends Model
{

    use SoftDeletes;
    protected $table = 'materiel';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dates = ['created_at','deleted_at'];



    public function type()
    {
        return $this->hasMany('PrismGestion\Models\Type', 'id');
    }

    public function exemplaire()
    {
        return $this->hasMany('PrismGestion\Models\Exemplaire','materiel');
    }

}