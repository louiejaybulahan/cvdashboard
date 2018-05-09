<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
//use Redirect;
//use App\Model\UserLevel;
use App\Model\UserPermission;
use App\Helpers\MenuManager;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'site';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('guest')->except('logout','loguser');        
    }

    public function loguser(Request $request) {
        echo '-------------------<br>';
        echo '<pre>';
        print_r(Auth::user());
        echo '</pre>';
        echo '-------------------<br>';
        echo $request->session()->get('userRoles');
        echo Auth::user()->username;
        //parent::showLoginForm();
    }

    public function login(Request $request) {
        $request->remember = false;
        $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required|min:6'
        ]);
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
            /*
              $roles = \App\Roles::where('username',Auth::user()->username)->first();
              if(isset($roles)){
              $request->session()->put('userRoles', $roles->toArray());
              }
              $areaArea = \App\AreaAsign::where('username',Auth::user()->username)->get();
              if(isset($areaArea)){
              $request->session()->put('userArea', $areaArea->toArray());
              }
             */
            /*
              $Menu = MenuManager::Menu();
              if(!in_array(Auth::user()->level_id,['38'])){
              $userPermission = new UserPermission();
              $permission = $userPermission->getById(Auth::user()->level_id);
              if(!empty($permission)){
              }
              }
             * 
             */
            //$request->session()->put('userMenu', array_merge($Menu,MenuManager::accountMenu()));
            return redirect()->intended(route('site.index'));
            //echo Auth::user()->is_admin ;
            //if (Auth::guest()) echo 'yes its a guest';
            //else echo 'not a guest';
        }
        return redirect()->back()->withInput($request->only('username', 'remember'));
    }
    
    /*
    protected function attemptLogin(Request $request) {
        $user = \App\Models\User::where([
                    'email' => $request->email,
                    'password' => md5($request->password)
                ])->first();

        if ($user) {
            $this->guard()->login($user, $request->has('remember'));

            return true;
        }

        return false;
    }
    */

    public function logout() {
        Auth::logout();
        //return Redirect::to('login');
        return redirect(\URL::previous());
    }

    public function username() {
        return 'username';
    }

    protected function guard() {
        return Auth::guard('web');
    }

}
