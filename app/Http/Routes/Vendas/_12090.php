<?php

	/**
	* Rotas do objeto _12090
	* @package Vendas
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {


//        $router->any ('_12090/api/cotas'      , 'Vendas\_12090\_12090ControllerApi@getCotas'  ); //Autenticar UP
//        $router->any ('_12090/api/cota'       , 'Vendas\_12090\_12090ControllerApi@getCota'   ); //Autenticar UP
//        $router->post('_12090/api/cota/insert', 'Vendas\_12090\_12090ControllerApi@insertCota'); //Autenticar UP
//        $router->post('_12090/api/cota/delete', 'Vendas\_12090\_12090ControllerApi@deleteCota'); //Autenticar UP
//        $router->post('_12090/api/cota/update', 'Vendas\_12090\_12090ControllerApi@updateCota'); //Autenticar UP

        $router->any ('_12090/api/empresa'   , 'Vendas\_12090\_12090ControllerApi@getEmpresa'); //Autenticar UP
        $router->any ('_12090/api/empresas'   , 'Vendas\_12090\_12090ControllerApi@getEmpresas'); //Autenticar UP
        $router->any ('_12090/api/modelos/preco'   , 'Vendas\_12090\_12090ControllerApi@getModelosPreco'); //Autenticar UP
//        $router->any ('_12090/api/cota'       , 'Vendas\_12090\_12090ControllerApi@getCota'   ); //Autenticar UP
//        $router->post('_12090/api/cota/insert', 'Vendas\_12090\_12090ControllerApi@insertCota'); //Autenticar UP
//        $router->post('_12090/api/cota/delete', 'Vendas\_12090\_12090ControllerApi@deleteCota'); //Autenticar UP
//        $router->post('_12090/api/cota/update', 'Vendas\_12090\_12090ControllerApi@updateCota'); //Autenticar UP
        
        
        
        //Cotas por Centro de Custo / Rotas Default
        $router->resource('_12090', 'Vendas\_12090Controller');
	});