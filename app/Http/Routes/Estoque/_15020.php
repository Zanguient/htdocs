<?php

 /**
  * Rotas do objeto _15020
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		//$router->resource('_15020', 'Estoque\_15020Controller');
		
		//listar localização (ajax)
		$router->post('_15020/listarSelect', 'Estoque\_15020Controller@listarSelect');
		
	});

