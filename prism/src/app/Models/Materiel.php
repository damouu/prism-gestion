<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materiel extends Model
{

    use SoftDeletes;
    protected $table = 'materiel';
    protected $primaryKey = 'id';
    public $timestamps = true;
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';
    const DELETED_AT = 'date_suppression';
    protected $dates = ['date_modification','date_creation','date_suppression'];



    public function type()
    {
        return $this->belongsTo('PrismGestion\Models\Type', 'type');
    }

    public function exemplaire()
    {
        return $this->hasMany('PrismGestion\Models\Exemplaire','materiel');
    }

}