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
        return $this->belongsTo('PrismGestion\Models\Professeur', 'responsable_projet');
    }

    public function etudiant()
    {
        return $this->hasMany('PrismGestion\Models\Etudiant', 'id');
    }

    public function departement()
    {
        return $this->belongsTo('PrismGestion\Models\Departement', 'departement');
    }

    public function groupe()
    {
        return $this->belongsToMany(
            'PrismGestion\Models\Etudiant',
            'groupe',
            'reservation',
            'etudiant')
            ->withPivot(['referent'])->as('groupe');
    }

    public function fiche_resa(){
        return $this->hasMany(
            'PrismGestion\Models\FicheReservation',
            'reservation'
        );
    }


}