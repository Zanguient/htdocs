<?php

	/**
	* Rotas do objeto _22070
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_22070/filtrar'    , 'Ppcp\_22070Controller@filtrar'    ); //filtrar
        $router->post('_22070/reprogramar', 'Ppcp\_22070Controller@reprogramar'); //reprogramar taloes
        
        $router->resource('_22070', 'Ppcp\_22070Controller'); //Rotas Default
		
	});