<?php
  
 /**
  * Rotas do objeto _13050
  * @package Fiscal
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		$router->post('_13050/autorizacao'    , 'Compras\_13050Controller@autorizacao'  ); //Autorizar/Negar OC
    $router->post('_13050/autorizacao2'   , 'Compras\_13050Controller@autorizacao2' ); //Autorizar/Negar OC
		$router->post('_13050/enviarPdfOc'	  , 'Compras\_13050Controller@enviarPdfOc'  ); //Gerar pdf com OC autorizada
		$router->post('_13050/excluirPdfOc'	  , 'Compras\_13050Controller@excluirPdfOc' ); //excluir pdf da OC	
		$router->post('_13050/imprimirPdfOc'  , 'Compras\_13050Controller@imprimirPdfOc'); //Imprimir pdf com OC autorizada
        $router->post('_13050/filtraObj'      , 'Compras\_13050Controller@createList'   ); //Filtras itens		
		$router->post('_13050/paginacaoScroll', 'Compras\_13050Controller@createList'   ); //Paginação por scroll
        $router->get ('_13050/show/{id}'      , 'Compras\_13050Controller@show'         ); //Visualizar um item

    	$router->resource   ('_13050', 'Compras\_13050Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

    $router->post('_13050/pendencias'    , 'Compras\_13050Controller@pendencias'  );       
    });