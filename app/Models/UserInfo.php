<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class UserInfo extends Authenticatable
{
    use Notifiable;
    
    protected $guard = 'users';
    
    protected $table = 'user_infos';
    
     protected $primaryKey = 'user_id';
    
    protected $validationError = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'firstname', 'lastname', 'middlename', 'is_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function validateuserInfo($request,$validationOption = 'new'){                      
        switch($validationOption){
            case 'new':
                $validate = Validator::make($request->all(), [
                    'username' => 'required|unique:'.$this->getTable().',username|max:100',
                    'password' => 'required|min:6|max:45',
                    'retype' => 'required|min:6|max:45|same:password',
                    'firstname' => 'required|max:45',
                    'lastname' => 'required|max:45',
                    'email' => 'required|email|max:45',
                    'contact' => 'required|max:45',
                    'userlevel' => 'required|numeric',
                    'region' => 'required|numeric',
                ],[
                    'firstname.required' => ' The firstname field is required',
                    'lastname.required' => ' The lastname field is required',                
                ]); 
            break;
            case 'update':
                $validate = Validator::make($request->all(), [ 
                    'password' => 'required|min:6|max:45',
                    'retype' => 'required|min:6|max:45|same:password',                                                       
                    'firstname' => 'required|max:45',
                    'lastname' => 'required|max:45',
                    'email' => 'required|email|max:45',
                    'contact' => 'required|max:45',
                    'userlevel' => 'required|numeric',
                    'region' => 'required|numeric',
                ],[
                    'firstname.required' => ' The firstname field is required',
                    'lastname.required' => ' The lastname field is required',                
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
    
    public function getUserList(){
        return $this->select('user_infos.*','lib_regions.REGION_NAME','user_level.level_name')
        ->leftJoin('lib_regions', 'lib_regions.REGION_ID', '=', 'user_infos.REGION_ID')
        ->leftJoin('user_level', 'user_level.level_id', '=', 'user_infos.level_id')
        ->orderBy('user_infos.lastname')
        ->get();        
    }
    public function getAuthPassword()
    {  return $this->password; }
    
    // if you want to change the encryotion matching
    //public function setPasswordAttribute($value)
    // { $this->attributes['password'] = bcrypt($value); }
}


/**
  $user = User::with([
'roles'=>function($q){
$q->select('user_id','status');
},
'roles.permission'=>function($q){
$q->select('permission_id','permission_name');
},
])
->orderBy('username ','ASC')
->get();
 */