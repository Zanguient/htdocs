<?php

/**
 * Rotas do objeto _29012 - Workflow
 * @package Workflow
 * @category Rotas
 */

	Route::get('/_29012/viewItem', 'Workflow\_29012Controller@viewItem');
	Route::get('/_29012/viewCreate', 'Workflow\_29012Controller@viewCreate');
 	Route::get('/_29012/viewInfoGeral', 'Workflow\_29012Controller@viewInfoGeral');
 	Route::get('/_29012/viewTarefa', 'Workflow\_29012Controller@viewTarefa');

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_29012/consultarWorkflowItem', 'Workflow\_29012Controller@consultarWorkflowItem');
        $router->post('_29012/consultarWorkflowItemTarefa', 'Workflow\_29012Controller@consultarWorkflowItemTarefa');
        $router->post('_29012/alterarSituacaoWorkflowItemTarefa', 'Workflow\_29012Controller@alterarSituacaoWorkflowItemTarefa');
        $router->post('_29012/gravarWorkflowItemArquivoDoDestinatario', 'Workflow\_29012Controller@gravarWorkflowItemArquivoDoDestinatario');
        $router->post('_29012/gravarWorkflowItemTarefaComentario', 'Workflow\_29012Controller@gravarWorkflowItemTarefaComentario');
        $router->post('_29012/gravarWorkflowItemTarefaCampo', 'Workflow\_29012Controller@gravarWorkflowItemTarefaCampo');

        $router->get('_29012', 'Workflow\_29012Controller@index');
        // $router->post('_29012/gravar', 'Workflow\_29012Controller@gravar');
        // $router->post('_29012/excluir', 'Workflow\_29012Controller@excluir');
        //$router->resource('_29012', 'Workflow\_29012Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
