<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exemplaire extends Model
{
    use SoftDeletes;
    protected $table = 'exemplaire';
    public $timestamps = true;
    protected $primaryKey = 'id';
    const CREATED_AT = 'date_achat';
    const UPDATED_AT = 'date_modif';
    const DELETED_AT = 'date_sortie';
    protected $dates = ['date_achat','date_modif','date_sortie'];


    public function materiel()
    {
        return $this->belongsTo('PrismGestion\Models\Materiel', 'materiel');
    }


}