<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Exception;
use Session;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		//Valor a ser comparado, no caso 3600, deve ser igual à diretiva 'session.gc_maxlifetime=3600' no php.ini.
		if ( (Session::get('LAST_ACTIVITY') !== null) && ((time() - Session::get('LAST_ACTIVITY')) > 18000)) {
			auth()->logout();
		}
		Session::put('LAST_ACTIVITY', time()); // update last activity time stamp		
		
//		session_start();
//		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 60)) {
//			auth()->logout();
//		}
//		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp		
		
        if ($this->auth->guest()) {
            if ($request->ajax()) {
				log_erro('Sua sessão expirou. Por favor, conecte-se novamente.',401);
//                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}
