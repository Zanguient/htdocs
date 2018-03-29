<?php
  
	/**
	* Rotas do objeto _13060
	* @package Vendas
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_13060/pesquisa', 'Compras\_13060Controller@pesquisa');
		
	});