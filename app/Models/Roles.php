<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';    
    protected $fillable = ['username','per_id'];   
    public $timestamps = false;     
    //protected $hidden = [];
    
    public function permission(){
        return $this->belongsTo('App\Permission','id','per_id');
    }
}
