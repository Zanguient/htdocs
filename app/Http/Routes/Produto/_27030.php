<?php

 /**
  * Rotas do objeto _27030 - Cadastro de Cores
  * @package Produto
  * @category Rotas
  */

    Route::get('_27030/viewConsultarCor', 'Produto\_27030Controller@viewConsultarCor');
    Route::get('_27030/viewCorPorModelo', 'Produto\_27030Controller@viewCorPorModelo');

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_27030/consultarCor', 'Produto\_27030Controller@consultarCor');
        $router->post('_27030/consultarCorPorModelo', 'Produto\_27030Controller@consultarCorPorModelo');
        
        // $router->get('_27030/viewConsultarCor', 'Produto\_27030Controller@viewConsultarCor');
        // $router->get('_27030/viewCorPorModelo', 'Produto\_27030Controller@viewCorPorModelo');

        $router->resource('_27030', 'Produto\_27030Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
