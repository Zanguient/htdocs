<?php

 /**
  * Rotas do objeto _26021 - Pesquisa de satisfação do cliente
  * @package Chamados
  * @category Rotas
  */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->get('_26021', 'Chamados\_26021Controller@index');
		$router->post('_26021/gravar', 'Chamados\_26021Controller@gravar');
		$router->post('_26021/excluir', 'Chamados\_26021Controller@excluir');

		$router->post('_26021/consultarPesquisa', 'Chamados\_26021Controller@consultarPesquisa');
		$router->post('_26021/consultarPergunta', 'Chamados\_26021Controller@consultarPergunta');
		$router->post('_26021/consultarResposta', 'Chamados\_26021Controller@consultarResposta');
		$router->post('_26021/consultarModeloPesquisa', 'Chamados\_26021Controller@consultarModeloPesquisa');
		$router->post('_26021/consultarModeloPesquisaPergunta', 'Chamados\_26021Controller@consultarModeloPesquisaPergunta');
		$router->post('_26021/consultarCliente', 'Chamados\_26021Controller@consultarCliente');
		
	});