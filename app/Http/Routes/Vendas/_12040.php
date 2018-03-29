<?php

 /**
  * Rotas do objeto _12040 - Registro de Pedidos
  * @package Vendas
  * @category Rotas
  */

 	Route::get('/_12040/viewPedidoIndex', 'Vendas\_12040Controller@viewPedidoIndex');
	Route::get('/_12040/viewPedidoCreate', 'Vendas\_12040Controller@viewPedidoCreate');
	Route::get('/_12040/viewInfoGeral', 'Vendas\_12040Controller@viewInfoGeral');
	Route::get('/_12040/viewLiberacao', 'Vendas\_12040Controller@viewLiberacao');
	Route::get('/_12040/viewPedidoItemEscolhido', 'Vendas\_12040Controller@viewPedidoItemEscolhido');
	Route::get('/_12040/viewPedidoItem', 'Vendas\_12040Controller@viewPedidoItem');
	
	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
		$router->post('/_12040/verificarUsuarioEhRepresentante', 'Vendas\_12040Controller@verificarUsuarioEhRepresentante');
		$router->post('/_12040/consultarRepresentanteDoCliente', 'Vendas\_12040Controller@consultarRepresentanteDoCliente');
		$router->post('/_12040/consultarPedido', 'Vendas\_12040Controller@consultarPedido');
		$router->post('/_12040/consultarPedidoItem', 'Vendas\_12040Controller@consultarPedidoItem');
		$router->post('/_12040/consultarInfoGeral', 'Vendas\_12040Controller@consultarInfoGeral');
		$router->post('/_12040/consultarTamanhoComPreco', 'Vendas\_12040Controller@consultarTamanhoComPreco');
		$router->post('/_12040/consultarQtdEPrazoPorTamanho', 'Vendas\_12040Controller@consultarQtdEPrazoPorTamanho');
		$router->post('/_12040/consultarQtdLiberada', 'Vendas\_12040Controller@consultarQtdLiberada');
		$router->post('/_12040/gerarChave', 'Vendas\_12040Controller@gerarChave');
		$router->post('/_12040/gravarLiberacao', 'Vendas\_12040Controller@gravarLiberacao');

		$router->post('/_12040/consultarPedido2', 'Vendas\_12040Controller@consultarPedido2');

		// $router->get('/_12040/viewPedidoIndex', 'Vendas\_12040Controller@viewPedidoIndex');
		// $router->get('/_12040/viewPedidoCreate', 'Vendas\_12040Controller@viewPedidoCreate');
		// $router->get('/_12040/viewInfoGeral', 'Vendas\_12040Controller@viewInfoGeral');
		// $router->get('/_12040/viewLiberacao', 'Vendas\_12040Controller@viewLiberacao');
		// $router->get('/_12040/viewPedidoItemEscolhido', 'Vendas\_12040Controller@viewPedidoItemEscolhido');
		// $router->get('/_12040/viewPedidoItem', 'Vendas\_12040Controller@viewPedidoItem');

		$router->post('/_12040/store', 'Vendas\_12040Controller@store');
		$router->post('/_12040/excluir', 'Vendas\_12040Controller@excluir');

		$router->post('/_12040/getPDF', 'Vendas\_12040Controller@getPDF');

		$router->resource('_12040', 'Vendas\_12040Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

	});