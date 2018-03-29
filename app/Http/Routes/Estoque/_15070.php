<?php

 /**
  * Rotas do objeto _15070 - Transacoes de Consumos de Remessas
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any('_15070/api/consumo', 'Estoque\_15070\_15070ControllerApi@getConsumo');
        $router->any('_15070/api/remessa', 'Estoque\_15070\_15070ControllerApi@getRemessa');
        $router->any('_15070/api/familia', 'Estoque\_15070\_15070ControllerApi@getFamilia');
        
        $router->any ('_15070/api/transacao'       , 'Estoque\_15070\_15070ControllerApi@getTransacao');
        $router->post('_15070/api/transacao/delete', 'Estoque\_15070\_15070ControllerApi@deleteTransacao');
        
        $router->post('_15070/api/avulso', 'Estoque\_15070\_15070ControllerApi@postAvulso');
        
        $router->post('_15070/api/peca', 'Estoque\_15070\_15070ControllerApi@postPeca');
        
        $router->any('_15070/api/etiqueta', 'Estoque\_15070\_15070ControllerApi@getEtiqueta');
        
        
        $router->get('_15070', 'Estoque\_15070\_15070Controller@index');
	});
