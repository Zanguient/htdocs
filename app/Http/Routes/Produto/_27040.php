<?php

 /**
  * Rotas do objeto _27040
  * @package Produto
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
		
		//listar tamanho (ajax)
		$router->post('_27040/listarTamanho', 'Produto\_27040Controller@listarTamanho');

		//$router->resource('_27040', 'Produto\_27040Controller');
		
	});

