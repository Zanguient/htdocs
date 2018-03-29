<?php

/**
 * Rotas do objeto _23032 - Cadastro de fatores para avaliação de desempenho.
 * @package Pessoal
 * @category Rotas
 */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
				
		$router->get('_23032', 'Pessoal\_23032Controller@index');
		$router->post('_23032/consultarInicial', 'Pessoal\_23032Controller@consultarInicial');
		$router->post('_23032/consultarFator', 'Pessoal\_23032Controller@consultarFator');
		$router->post('_23032/consultarFatorNivelDescritivo', 'Pessoal\_23032Controller@consultarFatorNivelDescritivo');
		$router->post('_23032/gravar', 'Pessoal\_23032Controller@gravar');
		$router->post('_23032/excluir', 'Pessoal\_23032Controller@excluir');
		
	});
