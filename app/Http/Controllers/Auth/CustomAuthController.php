<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Conexao\_Conexao;

/**
 * Customização do login.
 * Utilizada ao invés de AuthController.
 */
class CustomAuthController extends Controller
{
    use AuthenticatesUsers, ThrottlesLogins;

    protected $redirectAfterLogout = '/';
    protected $username = 'login';          //define campo para login
    // protected $maxLoginAttempts = 5;     // máximo de tentativas de login. Padrão: 5.

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        try {

            foreach ($credentials as $key => $value) {
                $user = $request->all();

                if ( Str::contains($key, 'login') ) {
                    $con = new _Conexao();

                    $sql = 'SELECT FIRST 1 * FROM TBUSUARIO E WHERE 
                                (upper(E.USUARIO)  = \''. strtoupper($user['login']).'\'
                                or  upper(E.EMAIL)    = \''. strtoupper($user['login']).'\'
                                or  upper(E.CNPJ)     = \''. strtoupper($user['login']).'\')';

                    $ret = $con->query($sql);

                    $con->commit();
                    
                    if(count($ret) > 0){

                        \Cache::flush($ret[0]->CODIGO . '_OLTLOGIN');
                        
                        if($ret[0]->STATUS == 0){
                            log_erro('Erro: LG0002 - Usuário Inativo');
                        }
                    }
                }
            }
            
        } catch (Exception $e) {
            log_erro('Erro: LG0001 - Erro no login');
        }

        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',       	
        ]);


        // Diferenciar entre usuário ou e-mail para autenticar.
        foreach ($credentials as $key => $value) {

            if ( Str::contains($key, 'login') ) {

                // CNPJ
                if ( is_numeric($value) ) {
                    $newkey = 'cnpj';
                }
                // Usuário
                else if ( strlen($value) <= 10 ) {
                    $newkey = 'usuario';
                    $value = strtoupper($value);
                }
                // E-mail
                else {
                    $newkey = 'email_login';
                    $value = strtolower($value);
                }

                $credentials[$newkey] = $value;
                unset($credentials[$key]);
            }
        }
        
        if (Auth::attempt($credentials, $request->has('remember'))) {

            // Redirecionar para a tela de pedidos quando o usuário for cliente.
            if ( !empty(Auth::user()->CLIENTE_ID) ) {
                $this->redirectTo = '/_12040';
            }

            return $this->handleUserWasAuthenticated($request, $throttles);
        }	

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }
  
        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function socketLogin(Request $request)
    {   
        try {

            $dados = $request->all()['COOKES'];
            
            $dados = json_decode($dados);

            $laravelCookie = $dados->value;
            $idSession     = Crypt::decrypt($laravelCookie);

            $dir = str_replace('app', '', app_path());

            $filename =  $dir."storage/framework/sessions/".$idSession;

            if (file_exists($filename)) {
                return 1;
            } else {
                return 0;
            }
            
        } catch (Exception $e) {
            return 0;   
        }
              
    }

}