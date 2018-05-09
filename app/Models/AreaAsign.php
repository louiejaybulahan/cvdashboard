<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaAsign extends Model
{
    protected $table = 'area_asign';
    protected $fillable = ['username','province','mun'];   
    public $timestamps = false;
}
