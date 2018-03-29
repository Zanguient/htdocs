<?php
  
	/**
	* Rotas do objeto _14010
	* @package Log�stica
	* @category Rotas
	*/
	
	
	//Rotas protegidas. S� devem ser acessadas ap�s login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_14010/pesquisa', 'Logistica\_14010Controller@pesquisa');
		
	});