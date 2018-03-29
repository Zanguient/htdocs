<?php

 /**
  * Rotas do objeto _29010 - Cadastro de Workflow
  * @package Workflow
  * @category Rotas
  */

    Route::get('/_29010/viewWorkflowIndex', 'Workflow\_29010Controller@viewWorkflowIndex');
    Route::get('/_29010/viewWorkflowCreate', 'Workflow\_29010Controller@viewWorkflowCreate');
    Route::get('/_29010/viewInfoGeral', 'Workflow\_29010Controller@viewInfoGeral');
    Route::get('/_29010/viewTarefa', 'Workflow\_29010Controller@viewTarefa');
    Route::get('/_29010/viewConsulta', 'Workflow\_29010Controller@viewConsulta');

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

        $router->post('/_29010/consultarWorkflow', 'Workflow\_29010Controller@consultarWorkflow');
        $router->post('/_29010/consultar', 'Workflow\_29010Controller@consultar');
        $router->post('/_29010/consultarTarefa', 'Workflow\_29010Controller@consultarTarefa');
        $router->post('/_29010/consultarTarefaDestinatario', 'Workflow\_29010Controller@consultarTarefaDestinatario');
        $router->post('/_29010/store', 'Workflow\_29010Controller@store');
        $router->post('/_29010/excluir', 'Workflow\_29010Controller@excluir');
        $router->post('/_29010/gravarEmailUsuario', 'Workflow\_29010Controller@gravarEmailUsuario');

        $router->resource('_29010', 'Workflow\_29010Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
