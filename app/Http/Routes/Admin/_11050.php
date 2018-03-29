<?php

	/**
	* Rotas do objeto _11050
	* @package Admin
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
        
        $router->get('_11050/etiqueta/{id}' , 'Admin\_11050Controller@etiqueta'  ); //Recupera um modelo de etiqueta pelo id
        
//        $router->resource('_11050', 'Admin\_11050Controller'); //Rotas Default
	});