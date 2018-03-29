<?php

 /**
  * Rotas do objeto _11080 - Criar Relatorio
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11080', 'Admin\_11080Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

        $router->POST('_11080/getRetornoSql', 'Admin\_11080Controller@getRetornoSql');
        $router->POST('_11080/getRetorno', 'Admin\_11080Controller@getRetorno');

        $router->POST('_11080/Gravar', 'Admin\_11080Controller@Gravar');

        $router->POST('_11080/{$id}', 'Admin\_11080Controller@show');

        $router->POST('_11080/Excluir', 'Admin\_11080Controller@Excluir');
        $router->POST('_11080/getRelatorios', 'Admin\_11080Controller@getRelatorios');
        $router->POST('_11080/edit/{$id}', 'Admin\_11080Controller@edit');

        $router->POST('_11080/Consultar', 'Admin\_11080Controller@Consultar');

        
        
		
	});
