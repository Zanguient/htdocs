<?php

 /**
  * Rotas do objeto _20110 - Relatorio de Extrato de Caixa/Bancos
  * @package Financeiro
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
      $router->resource('_20110', 'Financeiro\_20110Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		  $router->POST('_20110/Consultar'        , 'Financeiro\_20110Controller@Consultar');
      $router->POST('_20110/ConsultarBanco'   , 'Financeiro\_20110Controller@ConsultarBanco');
      $router->POST('_20110/ConsultarFluxo' , 'Financeiro\_20110Controller@ConsultarFluxo');     
		
	});
