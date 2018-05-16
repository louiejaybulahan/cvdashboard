<?php

namespace App\Http\Controllers;

//use Auth;
use Illuminate\Http\Request;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\DB;
use App\Models\UserInfo;
//use App\Models\Roles;
//use App\Models\CityMuni;

class UsersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {                            
        $user = new UserInfo();        
        $region = \App\Models\LibRegions::all();        
        $userlevel = \App\Models\UserLevel::orderBy('level_name','asc')->get();        
        return view('users.index', [
            'users' => $user->getUserList(),
            'userlevel' => $userlevel,
            'region' => $region,
        ]);
    }
    
    public function save(Request $request) {
        $status = '';        
        if($request->id!=''){            
            // $areaAssign = new \App\AreaAsign();
            // $areaAssign->where('username',$request->oldusername)->update(['username' => $request->username]);
            // $roles = \App\Roles::where('username',$request->oldusername)->update(['username' => $request->username]);
            // $user = UserInfo::find($request->id);
            // $status = 'update';
        }else{
            $user = new UserInfo;
            $user->is_status = 1;
            $status = 'new';            
        }    
        if ($user->validateuserInfo($request)) {       
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->lastname = $request->lastname;
            $user->firstname = $request->firstname;
            $user->middlename = '';
            $user->email = $request->email;
            $user->access = '';
            $user->contact = $request->contact;
            $user->REGION_ID = $request->region;
            $user->level_id = $request->userlevel;            
            $user->save();
            return response()->json(['flag' => 1,'msg' => 'Successfully Save']);
        } else return response()->json(['flag' => 0,'status' => $status, 'msg' => 'Invalid Saving!. Pls. fill-up the form correctly',  'errorlist' => $user->getErrorMessage()]);                
    }        
    public function remove(Request $request){ 
        $flag = 0;
        if(isset($request->id)){
            $user = new UserInfo();
            $result = $user->find(intval($request->id));
            if($result->delete()){ $msg = 'Successfully Remove'; }
            else{ $msg = 'Invalid Action'; }
        }else{ $msg = 'Invalid ID'; }                
        return response()->json(['flag' => $flag,'msg' => $msg]);
    }
/*
    public function edit(Request $request){
        $flag = 0;
        if(isset($request->id)){
            $user = new UserInfo();
            $result = $user->find(intval($request->id));
            if(isset($result)){
                $msg = [
                    'id' => $result->id,
                    'username' => $result->username,                    
                    'lname' => $result->lname,
                    'fname' => $result->fname,
                    'mname' => $result->mname,
                ];
                $flag = 1;
            }else{ $msg = 'Invalid Username'; }
        }else{ $msg = 'Invalid ID'; }                
        return response()->json(['flag' => $flag,'msg' => $msg]);
    }
    
    public function permission(Request $request){
        $flag = 0;
        $msg = '';        
        $province = $listArea = $dataRoles = [];
        if(isset($request->id)){
            $roles = new Roles();
            $result = $roles->where('username',$request->username)->first();                                               
            if(!empty($result)){
                $flag = 1;
                $msg = 'Found';
                $dataRoles = [
                    'id' => $result->id,
                    'username' => $result->username,
                    'permission' => $result->per_id,
                ];                
            }else{ $msg = 'Invalid Username'; }                        
            $area = new \App\AreaAsign();
            $listArea = $area->select('province','mun')->where('username',$request->username)->get();                                     
            foreach($listArea->toArray() AS $r):
                if(!in_array($r['province'],$province)):
                    $province[] = $r['province'];            
                endif;                
            endforeach;                                   
            $muni = new \App\CityMuni();                   
            $m = $muni->whereIn('prov_id',$province)->orderBy('name')->get();                                
        }else{ $msg = 'Invalid ID'; }                        
        return response()->json([
            'flag' => $flag,
            'msg' => $msg,
            'roles' => $dataRoles, 
            'listProvince' => $province,
            'area' => $listArea->toArray(),
            'municipal' => $m->toArray()
        ]);        
    }    
    public function municipal(Request $request){  
        $list = null;
        if($request->id!='null'){
            $muni = new \App\CityMuni();        
            $l = $muni->whereIn('prov_id',$request->id)->orderBy('name')->get();        
            $list = $l->toArray();
        }
        return response()->json(['list' => $list]);
    }
    
    public function savepermission(Request $request){        
        $flag = 0;
        DB::beginTransaction();
        try{
            $roles = new \App\Roles();
            $rles = $roles->where('username',$request->permissionUsername)->first();
            if(isset($rles)){
                $rles->per_id = $request->permission;
                $rles->save();
            }else{            
                $roles->username = $request->permissionUsername;
                $roles->per_id = $request->permission;
                $roles->save();
            }                
            
            $user = UserInfo::find($request->permissionId);
            $user->status = $request->user_status;
            $user->save();              
                        
            $AreaAssign = new \App\AreaAsign();
            $AreaAssign->where('username', $request->permissionUsername)->delete();
            if(isset($request->municipal)){                           
                foreach($request->municipal AS $m){            
                    $assign = explode('-',$m);
                    $insert[] = [
                        'username' => $request->permissionUsername,
                        'province' => $assign[0],
                        'mun' => $assign[1],
                    ];
                }                
                $AreaAssign->insert($insert);            
            }             
            $flag = 1;
            $msg = 'Successfully Save Changes';
            DB::commit();            
        }catch(\Exception $e){
            DB::rollback();
            $msg = 'Error : Something went wrong!. Please check your permission before saving. '.$e->getMessage();
        }                                      
        return response()->json(['msg' => $msg,'flag' => $flag]);
    }
     * 
     */
}
