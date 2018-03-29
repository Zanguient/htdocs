<?php

 	/**
	 * Rotas do objeto #TelaNO# - #Titulo#
	 * @package #Grupos#
	 * @category Rotas
	 */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->resource('#TelaNO#', '#Grupos#\#TelaNO#Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		$router->POST('#TelaNO#/consultar', '#Grupos#\#TelaNO#Controller@consultar');
		
	});
