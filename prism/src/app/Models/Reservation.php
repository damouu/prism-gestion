<?php


Namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{

    use SoftDeletes;
    protected $table = 'reservation';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d';
    protected $primaryKey = 'id';



    public function professeur()
    {
        return $this->hasMany('PrismGestion\Models\Professeur', 'id');
    }

    public function etudiant()
    {
        return $this->hasMany('PrismGestion\Models\Etudiant', 'id');
    }

    public function departement()
    {
        return $this->hasMany('PrimsGestion\Models\Departement', 'id');
    }

    public function exemplaire()
    {
        return $this->belongsToMany('PrismGestion\Models\Exemplaire', 'reservation_exemplaire', 'id_exemplaire','id_reservation')
            ->withPivot(['emprunt','rendu','incident'])->as('reservation_exemplaire');
    }


}