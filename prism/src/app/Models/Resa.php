<?php


Namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Resa extends Model{

    use SoftDeletes;
    protected $table = 'resa';


    public function reservation()
    {
        return $this->hasMany('PrismGestion\Models\Reservation', 'id');
    }
}