<?php

	/**
	* Rotas do objeto _22031
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
	
        $router->resource('_22031', 'Ppcp\_22031Controller'); //Rotas Default
	});