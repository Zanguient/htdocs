<?php

 /**
  * Rotas do objeto _31070 - Cadastro de Incentivos
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
      $router->resource('_31070', 'Custo\_31070Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		  $router->POST('_31070/consultar', 'Custo\_31070Controller@consultar');
      $router->POST('_31070/incluir', 'Custo\_31070Controller@incluir');
      $router->POST('_31070/alterar', 'Custo\_31070Controller@alterar');
      $router->POST('_31070/excluir', 'Custo\_31070Controller@excluir');
		
	});
