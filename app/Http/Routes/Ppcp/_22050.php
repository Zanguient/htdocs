<?php

	/**
	* Rotas do objeto _22050
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
	
        $router->post('_22050/autenticacao', 'Ppcp\_22050Controller@autenticacao'); //Autenticacao de usuário
        
        $router->resource('_22050', 'Ppcp\_22050Controller'); //Rotas Default
	});