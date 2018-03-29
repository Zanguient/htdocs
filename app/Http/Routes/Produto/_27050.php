<?php

 /**
  * Rotas do objeto _27050
  * @package Produto
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_27050/filtrar', 'Produto\_27050Controller@filtrar');
		$router->post('_27050/consultarPorModeloECor', 'Produto\_27050Controller@consultarPorModeloECor');

                
        $router->any('_27050/api/produto', 'Produto\_27050\_27050ControllerApi@getProduto');
        
		$router->any('_27050/consulta/json', 'Produto\_27050Controller@consultaJson');
		//$router->resource('_27050', 'Produto\_27050Controller');
		
	});

