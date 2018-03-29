<?php

 /**
  * Rotas do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
  * @package Pessoal
  * @category Rotas
  */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->get('_23035', 'Pessoal\_23035Controller@index');
		$router->post('_23035/consultarModelo', 'Pessoal\_23035Controller@consultarModelo');
		$router->post('_23035/consultarModeloItem', 'Pessoal\_23035Controller@consultarModeloItem');
		$router->post('_23035/gravar', 'Pessoal\_23035Controller@gravar');
		$router->post('_23035/excluir', 'Pessoal\_23035Controller@excluir');
		
	});
