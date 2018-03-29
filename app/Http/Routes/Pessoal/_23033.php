<?php

 /**
  * Rotas do objeto _23033 - Cadastro de formação do avaliado para avaliação de desempenho.
  * @package Pessoal
  * @category Rotas
  */

	//Rotas protegidas.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->get('_23033', 'Pessoal\_23033Controller@index');
		$router->post('_23033/consultarFormacao', 'Pessoal\_23033Controller@consultarFormacao');
		$router->post('_23033/gravar', 'Pessoal\_23033Controller@gravar');
		$router->post('_23033/excluir', 'Pessoal\_23033Controller@excluir');
		
	});
