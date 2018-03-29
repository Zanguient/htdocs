<?php

 /**
  * Rotas do objeto _16020 - Registro de Producao - Div. Bojo Colante
  * @package Patrimonio
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_16020/api/tipos'                    , 'Patrimonio\_16020\_16020ControllerApi@getTipos');
        $router->any ('_16020/api/tipo/post'                , 'Patrimonio\_16020\_16020ControllerApi@postTipo');
        
        
        $router->get('_16020', 'Patrimonio\_16020\_16020Controller@index');
		
	});
