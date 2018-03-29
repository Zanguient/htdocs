<?php

	/**
	* Rotas do objeto _22060
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_22060', 'Ppcp\_22060Controller'); //Rotas Default
        $router->post('_22060/filtrarEstabGp', 'Ppcp\_22060Controller@filtrarEstabGp'); //filtrar Estabelecimento e GP
        $router->post('_22060/filtrarUp', 'Ppcp\_22060Controller@filtrarUp'); //filtrar UP
        $router->post('_22060/filtrarEstacao', 'Ppcp\_22060Controller@filtrarEstacao'); //filtrar Estação
        $router->post('_22060/filtrarTalao', 'Ppcp\_22060Controller@filtrarTalao'); //filtrar Talão
        $router->post('_22060/filtrarTalaoDetalhe', 'Ppcp\_22060Controller@filtrarTalaoDetalhe'); //filtrar Talão Detalhe
		
	});