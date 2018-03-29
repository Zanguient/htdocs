<?php
  
	/**
	* Rotas do objeto _17010
	* @package Contábil
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->post('_17010/pesquisa', 'Contabil\_17010Controller@pesquisa');
        
		$router->any('_17010/api/ccontabil', 'Contabil\_17010Controller@apiCcontabil');
	});