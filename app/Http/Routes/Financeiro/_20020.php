<?php
  
 /**
  * Rotas do objeto _20020
  * @package Financeiro
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

    	//Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
    	$router->resource('_20020', 'Financeiro\_20020Controller');
    	
    	//Pesquisa
		$router->post('_20020/pesquisa', 'Financeiro\_20020Controller@search');
    });