<?php

 /**
  * Rotas do objeto _15120 - Transacoes de Consumos de Remessas
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any ('_15120/api/estoque'      , 'Estoque\_15120\_15120ControllerApi@getEstoque');  
        $router->any ('_15120/api/familia'      , 'Estoque\_15120\_15120ControllerApi@getFamilia');       
        $router->get('_15120', 'Estoque\_15120\_15120Controller@index');
	});
