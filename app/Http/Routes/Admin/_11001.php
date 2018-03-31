<?php

 	/**
	 * Rotas do objeto _11001 - Usuarios
	 * @package Admin
	 * @category Rotas
	 */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->resource('_11001', 'Admin\_11001Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		$router->POST('_11001/consultar', 'Admin\_11001Controller@consultar');
		
	});
