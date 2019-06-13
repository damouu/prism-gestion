<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departement extends Model
{

    use SoftDeletes;
    protected $table = 'departement';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d';
    protected $primaryKey = 'id';


    public function reservation()
    {
        return $this->hasMany('PrismGestion\Models\Reservation', 'departement');
    }

}