<?php

 /**
  * Rotas do objeto _12080 - REGISTRO DE CASOS
  * @package Vendas
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_12080', 'Vendas\_12080Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		$router->POST('_12080/Consultar', 'Vendas\_12080Controller@Consultar');
		
	});
