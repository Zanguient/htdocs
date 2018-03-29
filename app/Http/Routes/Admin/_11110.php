<?php

 /**
  * Rotas do objeto _11110 - Gerenciar Qlik Sense
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11110', 'Admin\_11110Controller');

        $router->POST('_11110/listUser', 'Admin\_11110Controller@listUser');
        
		
	});
