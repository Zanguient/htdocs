<?php
  
	/**
	* Rotas do objeto _20030
	* @package Financeiro
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_20030/pesquisaCCusto'			, 'Financeiro\_20030Controller@pesquisaCCusto'			);
        $router->post('_20030/pesquisaCCusto2'			, 'Financeiro\_20030Controller@pesquisaCCusto2'			);
		$router->post('_20030/pesquisaCCustoIndicador'	, 'Financeiro\_20030Controller@pesquisaCCustoIndicador'	);
		$router->post('_20030/pesquisaCCustoTodos'		, 'Financeiro\_20030Controller@pesquisaCCustoTodos'	);
		
		$router->any('_20030/api/ccusto', 'Financeiro\_20030Controller@apiCcusto');
	});