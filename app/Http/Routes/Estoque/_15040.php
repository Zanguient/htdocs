<?php

 /**
  * Rotas do objeto _15040
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		$router->resource('_15040', 'Estoque\_15040Controller');
		//filtra requisições (ajax)
		$router->post('_15040/filtrar', 'Estoque\_15040Controller@filtrar');
		//filtra baixas (ajax)
		$router->post('_15040/filtrarBaixa', 'Estoque\_15040Controller@filtrarBaixa');
		//filtra requisições de acordo com as opções escolhidas (ajax)
//		$router->post('_15040/filtrarRefinado', 'Estoque\_15040Controller@filtrarRefinado');
		//paginação por scroll (ajax)
		$router->post('_15040/paginacaoScroll', 'Estoque\_15040Controller@paginacaoScroll');
		//paginação por scroll (baixa) (ajax)
		$router->post('_15040/paginacaoScrollBaixa', 'Estoque\_15040Controller@paginacaoScrollBaixa');
		
	});

