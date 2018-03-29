<?php

 /**
  * Rotas do objeto _23031 - Cadastro de tipos de fatores para avaliação de desempenho.
  * @package Pessoal
  * @category Rotas
  */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->get('_23031', 'Pessoal\_23031Controller@index');
		$router->post('_23031/consultarTipo', 'Pessoal\_23031Controller@consultarTipo');
		$router->post('_23031/gravar', 'Pessoal\_23031Controller@gravar');
		$router->post('_23031/excluir', 'Pessoal\_23031Controller@excluir');
		
	});
