<?php

 /**
  * Rotas do objeto _11220 - Registro de Producao - Div. Bojo Colante
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_11220/api/dados' , 'Admin\_11220\_11220ControllerApi@getDados');
        $router->any ('_11220/api/modulo/post' , 'Admin\_11220\_11220ControllerApi@postModulo');
        $router->any ('_11220/api/periodo/post' , 'Admin\_11220\_11220ControllerApi@postPeriodo');
        
        $router->get('_11220', 'Admin\_11220\_11220Controller@index');
		
	});
