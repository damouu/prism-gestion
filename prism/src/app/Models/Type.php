<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use softDeletes;
    protected $table = 'type';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';
    const DELETED_AT = 'date_suppression';
    protected $dates = ['date_suppression'];



    public function materiels()
    {
        return $this->hasMany('PrismGestion\Models\Materiel','type');
    }


}