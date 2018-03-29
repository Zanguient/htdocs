<?php

 /**
  * Rotas do objeto _15110 - Transacoes de Consumos de Remessas
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any ('_15110/api/estoque'      , 'Estoque\_15110\_15110ControllerApi@getEstoque');       
        
        
        $router->get('_15110', 'Estoque\_15110\_15110Controller@index');
	});
