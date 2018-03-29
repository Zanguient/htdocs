<?php

 /**
  * Rotas do objeto _31060 - Registro de Producao - Div. Bojo Colante
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_31060/api/regra'     , 'Custo\_31060\_31060ControllerApi@getRegra' );
        $router->post('_31060/api/regra/post', 'Custo\_31060\_31060ControllerApi@postRegra');
        
        $router->get('_31060', 'Custo\_31060\_31060Controller@index');
		
	});
