<?php

 /**
  * Rotas do objeto _31050 - Registro de Producao - Div. Bojo Colante
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_31050/api/rateio/tipo'             , 'Custo\_31050\_31050ControllerApi@getRateioTipo'        );
        $router->any ('_31050/api/rateio/tipo/detalhe'     , 'Custo\_31050\_31050ControllerApi@getRateioTipoDetalhe' );
        $router->post('_31050/api/rateio/tipo/post'        , 'Custo\_31050\_31050ControllerApi@postRateioTipo'       );
        
        $router->get('_31050', 'Custo\_31050\_31050Controller@index');
		
	});
