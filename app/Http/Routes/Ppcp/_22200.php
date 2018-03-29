<?php

 /**
  * Rotas do objeto _22200 - Registro de Producao - Div. Bojo Colante
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_22200/api/talao'        , 'Ppcp\_22200\_22200ControllerApi@getTalao'        );
        $router->post('_22200/api/talao/liberar', 'Ppcp\_22200\_22200ControllerApi@postTalaoLiberar');
        
        
        $router->get('_22200', 'Ppcp\_22200\_22200Controller@index');
		
	});
