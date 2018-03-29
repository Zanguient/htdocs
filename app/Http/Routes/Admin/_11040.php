<?php

 /**
  * Rotas do objeto _11000
  * @package Admin
  * @category Rotas
  */

    Route::group(['middleware' => 'auth'], function($router) {
        $router->get ('/_11040'            , 'Admin\_11040Controller@index'      );	
        $router->post('/_11040/requestFile', 'Admin\_11040Controller@requestFile');		
	});

