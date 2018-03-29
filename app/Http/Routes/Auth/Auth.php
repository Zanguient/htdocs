<?php
  
	/**
	* Rotas do objeto Auth
	* @package Auth
	* @category Rotas
	*/
    
	Route::get ('/'              , 'Auth\AuthController@getLogin'    		); //página inicial
	Route::get ('auth/login'     , 'Auth\AuthController@getLogin'    		); //get login
	// Route::post('auth/login'     , 'Auth\AuthController@postLogin'   		); //post login
	Route::post('auth/login'     , 'Auth\CustomAuthController@postLogin'	); //post login
    Route::get ('auth/logout'    , 'Auth\AuthController@getLogout'   		); //sair do sistema
	Route::get ('auth/register'  , 'Auth\AuthController@getRegister' 		); //get registro
	Route::post('auth/register'  , 'Auth\AuthController@postRegister'		); //post registro

	// Password reset link request routes...
	Route::get ('password/email' , 'Auth\CustomPasswordController@getEmail'	);
	Route::post('password/email' , 'Auth\CustomPasswordController@postEmail');

	// Password reset routes...
	Route::get ('password/reset/{token}', 'Auth\CustomPasswordController@getReset' );
	Route::post('password/reset'		, 'Auth\CustomPasswordController@postReset');
    
	Route::get ('primeiroAcesso' , 'Auth\ResetController@getPrimeiroAcesso' ); //get primeiro acesso
	Route::post('primeiroAcesso' , 'Auth\ResetController@postPrimeiroAcesso'); //post primeiro acesso
	Route::get ('resetarSenha'   , 'Auth\ResetController@getResetarSenha'   ); //get resetar senha
	Route::post('resetarSenha'   , 'Auth\ResetController@postResetarSenha'  ); //post resetar senha

	Route::post('socket' 	 	 , 'Auth\CustomAuthController@socketLogin'	); //post registro