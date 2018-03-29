<?php
  
 /**
  * Rotas do objeto _20010
  * @package Financeiro
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

    	//Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
    	$router->resource('_20010', 'Financeiro\_20010Controller');
    	
    	//Pesquisa
		$router->post('_20010/pesquisa', 'Financeiro\_20010Controller@search');
    });