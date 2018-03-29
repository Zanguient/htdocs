<?php

 /**
  * Rotas do objeto _31020 - Registro de Producao - Div. Bojo Colante
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_31020/api/rateio/ccusto'        , 'Custo\_31020\_31020ControllerApi@getRateioCcusto' );
        $router->post('_31020/api/rateio/ccusto/post'   , 'Custo\_31020\_31020ControllerApi@postRateioCcusto');
        
        $router->any('_31020/api/ccusto/absorcao', 'Custo\_31020\_31020ControllerApi@getCCustoAbsorcao');
        
        $router->get('_31020', 'Custo\_31020\_31020Controller@index');
		
	});
