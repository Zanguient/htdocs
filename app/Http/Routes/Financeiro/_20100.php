<?php

 /**
  * Rotas do objeto _20100 - Relatorio de Extrato de Caixa/Bancos
  * @package Financeiro
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
      $router->resource('_20100', 'Financeiro\_20100Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		  $router->POST('_20100/Consultar'        , 'Financeiro\_20100Controller@Consultar');
      $router->POST('_20100/ConsultarBanco'   , 'Financeiro\_20100Controller@ConsultarBanco');
      $router->POST('_20100/ConsultarExtrato' , 'Financeiro\_20100Controller@ConsultarExtrato');     
		
	});
