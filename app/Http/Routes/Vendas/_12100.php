<?php

/**
* Rotas do objeto _12100 - NOTAS FISCAIS
* @package Vendas
* @category Rotas
*/

//Rotas protegidas.
Route::group(['middleware' => 'auth'], function($router) {

  

  $router->any('_12100/Consultar'              , 'Vendas\_12100Controller@Consultar');
  $router->any('_12100/consultarRepresentante' , 'Vendas\_12100Controller@consultarRepresentante');
  $router->any('_12100/consultarCliente'       , 'Vendas\_12100Controller@consultarClientePorRepresentante');
  $router->any('_12100/consultarNotas'         , 'Vendas\_12100Controller@consultarNotas');
  $router->any('_12100/consultarItens'         , 'Vendas\_12100Controller@consultarItens');
  $router->any('_12100/pdf'                    , 'Vendas\_12100Controller@pdf');
  $router->any('_12100/modeloEtiqueta'         , 'Vendas\_12100Controller@modeloEtiqueta');
  $router->any('_12100/DadosEtiqueta'          , 'Vendas\_12100Controller@DadosEtiqueta');
  
  $router->resource('_12100', 'Vendas\_12100Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

});
