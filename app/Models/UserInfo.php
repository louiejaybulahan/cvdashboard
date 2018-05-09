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
    
    public function validateuserInfo($request){
        $validate = Validator::make($request->all(), [
                    'username' => 'required|unique:'.$this->getTable().',username,'.$request->id.'|max:100',
                    'password' => 'required|min:6|max:45',
                    'retype' => 'required|min:6|max:45|same:password',
                    'fname' => 'required|max:45',
                    'lname' => 'required|max:45',
                    'mname' => 'required|max:45',
        ],[
            'fname.required' => ' The firstname field is required',
            'lastname.required' => ' The lastname field is required',
            'middlename.required' => ' The middlename field is required',
        ]); 
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
        return $this->select('user_infos.*')->orderBy('user_infos.lastname')->get();        
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