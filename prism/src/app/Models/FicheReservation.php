<?php


namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FicheReservation extends Model
{

    use SoftDeletes;

    protected $table = 'fiche_resa';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d';
    protected $primaryKey = 'id';
    public $incrementing = false;


    public function exemplaire()
    {
        return $this->belongsToMany(
            'PrismGestion\Models\Exemplaire',
            'reservation_exemplaire',
            'fiche_reservation',
            'id_exemplaire')
            ->withPivot(['emprunt', 'rendu', 'incident'])->as('reservation_exemplaire');
    }


}