<?php

 /**
  * Rotas do objeto _11060
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11060', 'Admin\_11060Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
        $router->POST('_11060/listar', 'Admin\_11060Controller@listar');
        $router->POST('_11060/excluir', 'Admin\_11060Controller@excluir');
        
	});
