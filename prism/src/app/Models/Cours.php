<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $table = 'cours';
    public $timestamps = false;
     protected $hidden = array('id', 'professeur_id');

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

}