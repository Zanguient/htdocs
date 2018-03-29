<?php

 /**
  * Rotas do objeto _12060 - Representante
  * @package Vendas
  * @category Rotas
  */

    Route::get('_12060/modalConsultarRepresentante', 'Vendas\_12060Controller@viewConsultarRepresentante');  // View para consultar representante.

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_12060/consultarRepresentante', 'Vendas\_12060Controller@consultarRepresentante');
        // $router->get('_12060/modalConsultarRepresentante', 'Vendas\_12060Controller@viewConsultarRepresentante');  // View para consultar representante.

        $router->resource('_12060', 'Vendas\_12060Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
