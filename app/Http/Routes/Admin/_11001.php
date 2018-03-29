<?php

 /**
  * Rotas do objeto _11001 - Agendador
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11001', 'Admin\_11001Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

        $router->post('/_11001/requestFile', 'Admin\_11001Controller@requestFile');		

	});
