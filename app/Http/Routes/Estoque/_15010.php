<?php

 /**
  * Rotas do objeto _15010
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		$router->resource('_15010', 'Estoque\_15010Controller');
		//filtra requisições (ajax)
		$router->post('_15010/filtrar', 'Estoque\_15010Controller@filtrar');
		$router->post('_15010/encerrar', 'Estoque\_15010Controller@encerrar');
		//filtra requisições de acordo com as opções escolhidas (ajax)
		//$router->post('_15010/filtrarRefinado', 'Estoque\_15010Controller@filtrarRefinado');
		//paginação por scroll (ajax)
		$router->post('_15010/paginacaoScroll', 'Estoque\_15010Controller@paginacaoScroll');
		
	});

