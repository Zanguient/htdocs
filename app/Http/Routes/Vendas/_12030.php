<?php
  
	/**
	* Rotas do objeto _12030
	* @package Vendas
	* @category Rotas
	*/
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		//Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
    	$router->resource('_12030', 'Vendas\_12030Controller');
        $router->post('_12030/paginacaoScroll', 'Vendas\_12030Controller@paginacaoScroll');
        $router->post('_12030/filtraObj', 'Vendas\_12030Controller@filtraObj');
        
        $router->post('_12030/store', 'Vendas\_12030Controller@store');
        $router->post('_12030/delete', 'Vendas\_12030Controller@delete');
        $router->get('_12030/{ID}', 'Vendas\_12030Controller@show');
        
	});