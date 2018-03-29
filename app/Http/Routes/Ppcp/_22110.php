<?php

 /**
  * Rotas do objeto _22110 - Registro de Agrupamento de Pedidos e Reposicoes
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_22110', 'Ppcp\_22110Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
