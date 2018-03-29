<?php

 	/**
	 * Rotas do objeto _23036 - Cadastro de avaliação de desempenho.
	 * @package Pessoal
	 * @category Rotas
	 */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
				
		$router->get('_23036', 'Pessoal\_23036Controller@index');
		$router->post('_23036/consultarBaseAvaliacao', 'Pessoal\_23036Controller@consultarBaseAvaliacao');
		$router->post('_23036/consultarBaseCCustoAvaliacao', 'Pessoal\_23036Controller@consultarBaseCCustoAvaliacao');
		$router->post('_23036/consultarModelo', 'Pessoal\_23036Controller@consultarModelo');
		$router->post('_23036/gravarBase', 'Pessoal\_23036Controller@gravarBase');
		$router->post('_23036/excluirBase', 'Pessoal\_23036Controller@excluirBase');
		
	});
