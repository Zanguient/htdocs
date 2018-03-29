<?php
  
	/**
	* Rotas do objeto _14020
	* @package Logística
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
        $router->any ('_14020/api/transportadora'        , 'Logistica\_14020\_14020ControllerApi@getTransportadora');
        $router->any ('_14020/api/transportadora/cidade' , 'Logistica\_14020\_14020ControllerApi@getTransportadoraCidade');
        $router->any ('_14020/api/ctrc/{id?}'            , 'Logistica\_14020\_14020ControllerApi@getCtrc');
        $router->post('_14020/api/frete/calcular'        , 'Logistica\_14020\_14020ControllerApi@postFreteCalcular');
        $router->any('_14020/api/frete/composicao'       , 'Logistica\_14020\_14020ControllerApi@getteComposicao');
        $router->any ('_14020/api/frete/{id}'            , 'Logistica\_14020\_14020ControllerApi@getFrete');
        $router->any ('_14020/api/composicao'            , 'Logistica\_14020\_14020ControllerApi@getComposicao');
        $router->any ('_14020/api/cidade'                , 'Logistica\_14020\_14020ControllerApi@getCidade');
        $router->any ('_14020/api/cliente'               , 'Logistica\_14020\_14020ControllerApi@getCliente');
		
        
        $router->get('_14020/comparar', 'Logistica\_14020\_14020Controller@comparar');
        $router->get('_14020', 'Logistica\_14020\_14020Controller@index');
        $router->get('_14020/{origem}/{origem_id?}/{transportadora_id?}', 'Logistica\_14020\_14020Controller@show');
	});