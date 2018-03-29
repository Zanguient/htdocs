<?php

 /**
  * Rotas do objeto _11200 - Registro de Producao - Div. Bojo Colante
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_11200/api/perfil' , 'Admin\_11200\_11200ControllerApi@getPerfil');
        
        $router->get('_11200', 'Admin\_11200\_11200Controller@index');
		
	});
