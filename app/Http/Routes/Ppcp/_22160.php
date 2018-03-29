<?php

 /**
  * Rotas do objeto _22160 - Transacoes de Consumos de Remessas
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any ('_22160/api/consumo-baixar'     , 'Ppcp\_22160\_22160ControllerApi@getConsumoBaixar' );
        $router->post('_22160/api/consumo-baixar/post', 'Ppcp\_22160\_22160ControllerApi@postConsumoBaixar');
        
        $router->any ('_22160/api/consumo-baixado'     , 'Ppcp\_22160\_22160ControllerApi@getConsumoBaixado' );
        $router->post('_22160/api/consumo-baixado/post', 'Ppcp\_22160\_22160ControllerApi@postConsumoBaixado');
        
        $router->any ('_22160/api/consumo-baixado/transacao'       , 'Ppcp\_22160\_22160ControllerApi@getConsumoBaixadoTransacao'       );
        $router->post('_22160/api/consumo-baixado/transacao/delete', 'Ppcp\_22160\_22160ControllerApi@postConsumoBaixadoTransacaoDelete');
        
        $router->post('_22160/api/etiqueta', 'Ppcp\_22160\_22160ControllerApi@getEtiqueta');
        
        $router->post('_22160/api/operador', 'Ppcp\_22160\_22160ControllerApi@operador');
  
        $router->get('_22160', 'Ppcp\_22160\_22160Controller@index');
	});
