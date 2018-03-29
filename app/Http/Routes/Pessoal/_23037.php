<?php

	/**
	 * Rotas do objeto _23037 - Avaliação de desempenho.
	 * @package Pessoal
	 * @category Rotas
	 */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
				
		$router->get('_23037', 'Pessoal\_23037Controller@index');
		$router->post('_23037/consultarBase', 'Pessoal\_23037Controller@consultarBase');
		$router->post('_23037/consultarAvaliacao', 'Pessoal\_23037Controller@consultarAvaliacao');
		$router->post('_23037/consultarAvaliacaoItem', 'Pessoal\_23037Controller@consultarAvaliacaoItem');
		$router->post('_23037/consultarModeloItem', 'Pessoal\_23037Controller@consultarModeloItem');
		$router->post('_23037/consultarColaborador', 'Pessoal\_23037Controller@consultarColaborador');
		$router->post('_23037/consultarColaboradorIndicador', 'Pessoal\_23037Controller@consultarColaboradorIndicador');
		$router->post('_23037/gravarAvaliacao', 'Pessoal\_23037Controller@gravarAvaliacao');
		$router->post('_23037/excluirAvaliacao', 'Pessoal\_23037Controller@excluirAvaliacao');
		
	});
