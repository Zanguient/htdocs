<?php
  
	/**
	* Rotas do objeto _12010
	* @package Vendas
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_12010/pesquisa', 'Vendas\_12010Controller@pesquisa');
		
	});