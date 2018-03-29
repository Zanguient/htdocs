<?php

 /**
  * Rotas do objeto _31040 - Registro de Producao - Div. Bojo Colante
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_31040/api/rateio/tipo'             , 'Custo\_31040\_31040ControllerApi@getRateioTipo'        );
        $router->any ('_31040/api/rateio/tipo/detalhe'     , 'Custo\_31040\_31040ControllerApi@getRateioTipoDetalhe' );
        $router->post('_31040/api/rateio/tipo/post', 'Custo\_31040\_31040ControllerApi@postRateioTipo');
        
        $router->get('_31040', 'Custo\_31040\_31040Controller@index');
		
	});
