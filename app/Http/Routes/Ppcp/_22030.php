<?php

	/**
	* Rotas do objeto _22030
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
	


        $router->any('_22030/api/gp'              , 'Ppcp\_22030\_22030ControllerApi@getGp'            );
        $router->any('_22030/api/gp/autenticacao' , 'Ppcp\_22030\_22030ControllerApi@getGpAutenticacao');
        $router->any('_22030/api/up'              , 'Ppcp\_22030\_22030ControllerApi@getUp'            );
        $router->any('_22030/api/estacao'         , 'Ppcp\_22030\_22030ControllerApi@getEstacao'       );
        
        
        $router->resource('_22030', 'Ppcp\_22030Controller'); //Rotas Default
                
	});