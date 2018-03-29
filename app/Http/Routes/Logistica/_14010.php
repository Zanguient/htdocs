<?php
  
	/**
	* Rotas do objeto _14010
	* @package Logística
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_14010/pesquisa', 'Logistica\_14010Controller@pesquisa');
		
	});