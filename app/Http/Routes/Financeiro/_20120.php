<?php

 /**
  * Rotas do objeto _20120 - Registro de Producao - Div. Bojo Colante
  * @package Financeiro
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_20120/api/unidade-medida'             , 'Financeiro\_20120\_20120ControllerApi@getUnidadeMedida'  );
//        $router->any ('_20120/api/rateio/tipo/detalhe'     , 'Financeiro\_20120\_20120ControllerApi@getRateioTipoDetalhe' );
//        $router->post('_20120/api/rateio/tipo/post', 'Financeiro\_20120\_20120ControllerApi@postRateioTipo');
        
        $router->get('_20120', 'Financeiro\_20120\_20120Controller@index');
		
	});
