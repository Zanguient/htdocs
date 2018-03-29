<?php

 /**
  * Rotas do objeto _11100 - Qlik Sense
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

        $router->resource('_11100', 'Admin\_11100Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
