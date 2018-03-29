<?php

 /**
  * Rotas do objeto _15090 - Transacoes de Consumos de Remessas
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any ('_15090/api/conferencia/itens'          , 'Estoque\_15090\_15090ControllerApi@getConferenciaItens');
        $router->any ('_15090/api/conferencia/pendentes'      , 'Estoque\_15090\_15090ControllerApi@getConferenciaPendentes');
        $router->any ('_15090/api/conferencia/pendenciasLote' , 'Estoque\_15090\_15090ControllerApi@getConferenciaPendentesLote');
        $router->post('_15090/api/conferencia/confirmar'      , 'Estoque\_15090\_15090ControllerApi@postConferenciaConfirmar');
        
        $router->get('_15090', 'Estoque\_15090\_15090Controller@index');
	});
