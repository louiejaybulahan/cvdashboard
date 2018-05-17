<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Processlist extends Model
{
   
    protected $table = 'processlist';
    
    protected $primaryKey = 'id';

    public $timestamps = false;
    
    protected $validationError = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scriptname', 'url', 'status'
    ];
    
    
    public function validateForm($request,$validationOption = 'new'){                      
        switch($validationOption){
            case 'new':
                $validate = Validator::make($request->all(), [                                      
                    'scriptname' => 'required|max:45',
                    'url' => 'required|max:100',                    
                ]); 
            break;
            case 'update':
                $validate = Validator::make($request->all(), [                                      
                    'scriptname' => 'required|max:45',
                    'url' => 'required|max:100',                    
                ]);        
            break;
        }                                    
        if (!$validate->fails()) {
            return true;
        }else{
            $this->validationError = $validate->errors()->all();
            return false;
        }
    }
    public function getErrorMessage(){
        return $this->validationError;
    }
    
   
}
