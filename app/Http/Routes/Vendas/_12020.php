<?php
  
	/**
	* Rotas do objeto _12020
	* @package Vendas
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_12020/pesquisa', 'Vendas\_12020Controller@pesquisa');
		
	});