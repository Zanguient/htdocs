<?php

 /**
  * Rotas do objeto _31030 - Registro de Producao - Div. Bojo Colante
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_31030/api/rateio/ccontabil'     , 'Custo\_31030\_31030ControllerApi@getRateioCContabil' );
        $router->post('_31030/api/rateio/ccontabil/post', 'Custo\_31030\_31030ControllerApi@postRateioCContabil');
        
        $router->get('_31030', 'Custo\_31030\_31030Controller@index');
		
	});
