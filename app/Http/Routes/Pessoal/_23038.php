<?php

	/**
	 * Rotas do objeto _23038 - Registro de indicadores por centro de custo.
	 * @package Pessoal
	 * @category Rotas
	 */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
				
		$router->get('_23038', 'Pessoal\_23038Controller@index');
		$router->post('_23038/consultarIndicadorPorCCusto', 'Pessoal\_23038Controller@consultarIndicadorPorCCusto');
		$router->post('_23038/consultarIndicador', 'Pessoal\_23038Controller@consultarIndicador');
		$router->post('_23038/gravar', 'Pessoal\_23038Controller@gravar');
		$router->post('_23038/excluir', 'Pessoal\_23038Controller@excluir');
		
	});
