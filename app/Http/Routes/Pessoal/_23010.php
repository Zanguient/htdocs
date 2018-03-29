<?php

 /**
  * Rotas do objeto _23010
  * @package Pessoal
  * @category Rotas
  */

    $router->get('/pessoal/colaborador-centro-de-trabalho', function () {return view('pessoal.helper.colaborador-centro-de-trabalho');}); 
	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		//$router->resource('_23010', 'Admin\_23010Controller');
		
		//listar turno (ajax)
		$router->post('/_23010/listarSelect', 'Pessoal\_23010Controller@listarSelect');
        
		
	});

