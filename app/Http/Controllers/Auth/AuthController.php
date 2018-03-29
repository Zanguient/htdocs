<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use App;
use Auth;
use Config;
use Crypt;

use Illuminate\Support\Facades\Cookie;

use Illuminate\Session\SessionManager;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    
    //private $redirectTo = '/';
    //protected $redirectPath = '/';
    //protected $loginPath = 'auth/login';
    // protected $redirectAfterLogout = '/';
    // protected $username = 'USUARIO';	//define campo para login

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		//OBS.: Para adicionar mais um campo para validação, é necessário, além de adicioná-lo aqui,
		//adicionar em vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers,
		//método getCredentials().
		
        return Validator::make($data, [
            'USUARIO'	=> 'required|max:10',
            //'email' => 'required|email|max:255|unique:TBUSUARIO',
            'password'	=> 'required|confirmed|min:6',
            'status'	=> 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'usuario'   => $data['usuario'],
            //'name' => $data['name'],
            //'email' => $data['email'],
            'password'  => bcrypt($data['password']),
        ]);
    }
	
}
