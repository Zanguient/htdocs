<?php

 /**
  * Rotas do objeto _29011 - Cadastro de item de workflow
  * @package Workflow
  * @category Rotas
  */

 	Route::get('/_29011/viewItem', 'Workflow\_29011Controller@viewItem');
 	Route::get('/_29011/viewCreate', 'Workflow\_29011Controller@viewCreate');
 	Route::get('/_29011/viewInfoGeral', 'Workflow\_29011Controller@viewInfoGeral');
 	Route::get('/_29011/viewTarefa', 'Workflow\_29011Controller@viewTarefa');

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_29011/consultarItem', 'Workflow\_29011Controller@consultarItem');
        $router->post('_29011/consultarItemTarefa', 'Workflow\_29011Controller@consultarItemTarefa');
        $router->post('_29011/consultarWorkflowTarefa', 'Workflow\_29011Controller@consultarWorkflowTarefa');
        $router->get('_29011', 'Workflow\_29011Controller@index');
        $router->post('_29011/gravar', 'Workflow\_29011Controller@gravar');
        $router->post('_29011/encerrar', 'Workflow\_29011Controller@encerrar');
        $router->post('_29011/excluir', 'Workflow\_29011Controller@excluir');
        
        // $router->resource('_29011', 'Workflow\_29011Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
