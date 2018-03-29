<?php

 /**
  * Rotas do objeto _15050
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		$router->resource('_15050', 'Estoque\_15050Controller');
		
	});

