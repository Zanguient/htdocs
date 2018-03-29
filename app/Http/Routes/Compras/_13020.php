<?php

 /**
  * Rotas do objeto _13020
  * @package Compras
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
		
		//Geração de orçamento
		$router->resource('_13020', 'Compras\_13020Controller');
		//filtra orçamentos (ajax)
		$router->post('_13020/filtraObj', 'Compras\_13020Controller@filtraObj');
		//paginação por scroll (ajax)
		$router->post('_13020/paginacaoScroll', 'Compras\_13020Controller@paginacaoScroll');
		//Email
		$router->post('_13020/enviaEmail', 'Compras\_13020Controller@enviarEmail');		
		//Editar email do fornecedor (ajax)
		$router->post('_13020/editarDadosFornec', 'Compras\_13020Controller@editarDadosFornec');
		
	});