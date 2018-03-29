<?php

 /**
  * Rotas do objeto _15060 - Consulta de Estoque
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_15060/find', 'Estoque\_15060\ControllerFind@find'); //Rota para acessar os itens dos agrupamentos
        
        $router->resource('_15060', 'Estoque\_15060\Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
