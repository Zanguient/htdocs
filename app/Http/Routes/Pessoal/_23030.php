<?php

 /**
  * Rotas do objeto _23030 - Cadastro de níveis dos fatores para avaliação de desempenho.
  * @package Pessoal
  * @category Rotas
  */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->get('_23030', 'Pessoal\_23030Controller@index');
		$router->post('_23030/consultarNivel', 'Pessoal\_23030Controller@consultarNivel');
		$router->post('_23030/gravar', 'Pessoal\_23030Controller@gravar');
		$router->post('_23030/excluir', 'Pessoal\_23030Controller@excluir');
		
	});
