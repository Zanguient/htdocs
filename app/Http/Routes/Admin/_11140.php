<?php

 /**
  * Rotas do objeto _11140 - Painel de Casos
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
  Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11140', 'Admin\_11140Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		    $router->POST('_11140/Consultar', 'Admin\_11140Controller@Consultar');
        $router->POST('_11140/getClientes', 'Admin\_11140Controller@getClientes');

        
		
	});
