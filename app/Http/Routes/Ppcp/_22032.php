<?php

	/**
	* Rotas do objeto _22032
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
	
        $router->resource('_22032', 'Ppcp\_22032Controller'); //Rotas Default
	});