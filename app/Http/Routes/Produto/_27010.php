<?php

 /**
  * Rotas do objeto _27010 - Cadastro de Familias de Produto
  * @package Produto
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any('_27010/api/familia', 'Produto\_27010\_27010ControllerApi@getFamilia');        
        
        $router->get('_27010/familia-modelo-alocacao', 'Produto\_27010Controller@familiaModeloAlocacao');
        $router->post('_27010/familia-modelo-alocacao', 'Produto\_27010Controller@familiaModeloAlocacao');
        
        $router->resource('_27010', 'Produto\_27010Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
