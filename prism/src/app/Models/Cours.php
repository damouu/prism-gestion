<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cours extends Model
{
    use SoftDeletes;

    protected $table = 'cours';
    public $timestamps = false;
    protected $hidden = array('id', 'professeur_id');

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

}