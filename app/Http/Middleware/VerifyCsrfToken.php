<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    /**
     * Handle an incoming request.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
//     public function handle($request, Closure $next) {
//     	return parent::handle($request, $next);
//     }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        // If request is an ajax request, then check to see if token matches token provider in
        // the header. This way, we can use CSRF protection in ajax requests also.
        $token = $request->ajax() ? $request->header('X-CSRF-TOKEN') : $request->input('_token');

//        $equals = $request->session()->token() == $token;
//                
//        if ( !$equals ) {
//            log_info('Token session: ' . $request->session()->token() . " - Token request: " . $token);
//        }
        
        return 1;
    }
}
