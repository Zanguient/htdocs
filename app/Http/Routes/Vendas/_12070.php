<?php

 /**
  * Rotas do objeto _12070 - Clientes
  * @package Vendas
  * @category Rotas
  */

    Route::get('_12070/modalConsultarClientePorRepresentante', 'Vendas\_12070Controller@viewConsultarClientePorRepresentante');  // View para consultar cliente.

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_12070/consultarClientePorRepresentante', 'Vendas\_12070Controller@consultarClientePorRepresentante');
        // $router->get('_12070/modalConsultarClientePorRepresentante', 'Vendas\_12070Controller@viewConsultarClientePorRepresentante');  // View para consultar cliente.

        $router->resource('_12070', 'Vendas\_12070Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
