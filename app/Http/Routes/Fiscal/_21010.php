<?php
  
 /**
  * Rotas do objeto _21010
  * @package Fiscal
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

    	//Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
    	$router->resource('_21010', 'Fiscal\_21010Controller');
    	
    	//Pesquisa
		$router->post('_21010/pesquisa', 'Fiscal\_21010Controller@find');
	
    });