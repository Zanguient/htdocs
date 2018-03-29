<?php

 /**
  * Rotas do objeto _31080 - Cadastro de Mercados
  * @package Custo
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
      $router->resource('_31080', 'Custo\_31080Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		  $router->POST('_31080/consultar'        , 'Custo\_31080Controller@consultar');
      $router->POST('_31080/incluir'          , 'Custo\_31080Controller@incluir');
      $router->POST('_31080/alterar'          , 'Custo\_31080Controller@alterar');
      $router->POST('_31080/excluir'          , 'Custo\_31080Controller@excluir');
      
      $router->POST('_31080/consultarFamilia' , 'Custo\_31080Controller@consultarFamilia');
      $router->POST('_31080/consultarConta'   , 'Custo\_31080Controller@consultarConta');

      $router->POST('_31080/consultar_itens'  , 'Custo\_31080Controller@consultar_itens');
      $router->POST('_31080/incluir_itens'    , 'Custo\_31080Controller@incluir_itens');
      $router->POST('_31080/alterar_itens'    , 'Custo\_31080Controller@alterar_itens');
      $router->POST('_31080/excluir_itens'    , 'Custo\_31080Controller@excluir_itens');

      $router->POST('_31080/consultar_itens_conta'  , 'Custo\_31080Controller@consultar_itens_conta');
      $router->POST('_31080/incluir_itens_conta'    , 'Custo\_31080Controller@incluir_itens_conta');
      $router->POST('_31080/alterar_itens_conta'    , 'Custo\_31080Controller@alterar_itens_conta');
      $router->POST('_31080/excluir_itens_conta'    , 'Custo\_31080Controller@excluir_itens_conta');

	});
