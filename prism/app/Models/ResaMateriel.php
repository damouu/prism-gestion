<?php


Namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ResaMateriel extends Model{

    use SoftDeletes;
    protected $table = 'resa_materiel';


}