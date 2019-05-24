<?php

namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exemplaire extends Model
{
    use SoftDeletes;
    protected $table = 'exemplaire';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d';
    protected $primaryKey = 'id';
    const CREATED_AT = 'date_achat';
    const UPDATED_AT = 'date_modif';
    const DELETED_AT = 'date_sortie';
    protected $dates = ['date_achat','date_modif','date_sortie'];


    public function materiel()
    {
        return $this->belongsTo('PrismGestion\Models\Materiel', 'materiel');
    }

    public function fournisseur()
    {
        return $this->belongsTo('PrismGestion\Models\Fournisseur', 'fournisseur');
    }

    public function reservation()
    {
        return $this->belongsToMany('PrismGestion\Models\Reservation', 'reservation_exemplaire','id_exemplaire', 'id_reservation' )
            ->withPivot(['emprunt','rendu','incident'])->as('reservation_exemplaire');
    }


}