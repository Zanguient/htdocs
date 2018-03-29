<?php

 /**
  * Rotas do objeto _31010 - Custos Gerenciais
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
      $router->resource('_31010', 'Custo\_31010Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		  $router->POST('_31010/Consultar'               , 'Custo\_31010Controller@Consultar');
      $router->POST('_31010/ConsultarCor'            , 'Custo\_31010Controller@ConsultarCor');
      $router->POST('_31010/ConsultarTamanho'        , 'Custo\_31010Controller@ConsultarTamanho');
      $router->POST('_31010/ConsultarTamanho2'       , 'Custo\_31010Controller@ConsultarTamanho2');
      $router->POST('_31010/ConsultarFicha'          , 'Custo\_31010Controller@ConsultarFicha');
      $router->POST('_31010/ConsultarFichaTempo'     , 'Custo\_31010Controller@ConsultarFichaTempo');
      $router->POST('_31010/ConsultarPerfil'         , 'Custo\_31010Controller@ConsultarPerfil');
      $router->POST('_31010/ConsultarAbsorcao'       , 'Custo\_31010Controller@ConsultarAbsorcao');
      $router->POST('_31010/ConsultarProprio'        , 'Custo\_31010Controller@ConsultarProprio');
      $router->POST('_31010/ConsultarEstacoes'       , 'Custo\_31010Controller@ConsultarEstacoes');
      $router->POST('_31010/ConsultarTempo'          , 'Custo\_31010Controller@ConsultarTempo');
      $router->POST('_31010/ConsultarMaoDeObra'      , 'Custo\_31010Controller@ConsultarMaoDeObra');
      $router->POST('_31010/ConsultarConfiguracao'   , 'Custo\_31010Controller@ConsultarConfiguracao');
      $router->POST('_31010/ConsultarDespesas'       , 'Custo\_31010Controller@ConsultarDespesas');
      $router->POST('_31010/DetalharDespesa'         , 'Custo\_31010Controller@DetalharDespesa');
      $router->POST('_31010/ConsultarDetalheDespesa' , 'Custo\_31010Controller@ConsultarDetalheDespesa');
      $router->POST('_31010/custoPadrao'             , 'Custo\_31010Controller@custoPadrao');
      $router->POST('_31010/custoPadraoItem'         , 'Custo\_31010Controller@custoPadraoItem');
      $router->POST('_31010/consultarIncentivo'      , 'Custo\_31010Controller@consultarIncentivo');
      $router->POST('_31010/FaturamentoFamilia'      , 'Custo\_31010Controller@FaturamentoFamilia');
      $router->POST('_31010/consultarProduto'        , 'Custo\_31010Controller@consultarProduto');  
      $router->POST('_31010/consultarDensidade'      , 'Custo\_31010Controller@consultarDensidade');

      $router->POST('_31010/ConsultarSimulacao'      , 'Custo\_31010Controller@ConsultarSimulacao');
      $router->POST('_31010/ConsultarPrecoVenda'     , 'Custo\_31010Controller@ConsultarPrecoVenda'); 
      $router->POST('_31010/gravarSimulacao'         , 'Custo\_31010Controller@gravarSimulacao');
      $router->POST('_31010/excluirSimulacao'         , 'Custo\_31010Controller@excluirSimulacao');
      $router->POST('_31010/Simulacao'               , 'Custo\_31010Controller@Simulacao');
            

	  });
