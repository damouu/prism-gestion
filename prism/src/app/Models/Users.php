<?php


namespace PrismGestion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Users extends Model
{
    protected $table = 'users';
    public $timestamps = false;
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}