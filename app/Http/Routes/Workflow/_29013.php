<?php

 /**
  * Rotas do objeto _29013 - Painel com cronograma das tarefas
  * @package Workflow
  * @category Rotas
  */

    Route::get('/_29013/viewItem', 'Workflow\_29013Controller@viewItem');

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_29013/consultarWorkflowItem', 'Workflow\_29012Controller@consultarWorkflowItem');
        $router->post('_29013/consultarWorkflowItemTarefa', 'Workflow\_29012Controller@consultarWorkflowItemTarefa');

        $router->get('_29013', 'Workflow\_29013Controller@index');
		
	});
