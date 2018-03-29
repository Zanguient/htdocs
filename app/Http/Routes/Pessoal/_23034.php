<?php

 /**
  * Rotas do objeto _23034 - Cadastro de resumo para avaliação de desempenho.
  * @package Pessoal
  * @category Rotas
  */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->get('_23034', 'Pessoal\_23034Controller@index');
		$router->post('_23034/consultarResumo', 'Pessoal\_23034Controller@consultarResumo');
		$router->post('_23034/gravar', 'Pessoal\_23034Controller@gravar');
		$router->post('_23034/excluir', 'Pessoal\_23034Controller@excluir');
		
	});
