<?php

 /**
  * Rotas do objeto _23020 - Registro de Producao - Div. Bojo Colante
  * @package Pessoal
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_23020/api/colaboradores'                        , 'Pessoal\_23020\_23020ControllerApi@getColaboradores');
        $router->post('_23020/api/colaborador/centro-de-trabalho/update', 'Pessoal\_23020\_23020ControllerApi@updateColaboradorCentroDeTrabalho');
        
        $router->get('_23020', 'Pessoal\_23020\_23020Controller@index');
		
	});
