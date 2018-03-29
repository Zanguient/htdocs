<?php

 /**
  * Rotas do objeto _11000
  * @package Admin
  * @category Rotas
  */

    Route::group(['middleware' => 'auth'], function($router) {

		$router->post('/_11000/getChecList', 'Admin\_11000Controller@getChecList');
		$router->post('/_11000/gravarEnv', 'Admin\_11000Controller@gravarEnv');
        
        $router->resource('_11000', 'Admin\_11000Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
	});

