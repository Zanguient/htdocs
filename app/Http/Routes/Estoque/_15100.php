<?php

/**
* Rotas do objeto _15100 - Abastecer estoque
* @package Estoque
* @category Rotas
*/

//Rotas protegidas.
Route::group(['middleware' => 'auth'], function($router) {

  $router->resource('_15100', 'Estoque\_15100Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

  $router->POST('_15100/Consultar'         , 'Estoque\_15100Controller@Consultar');
  $router->POST('_15100/ConsultarUP'       , 'Estoque\_15100Controller@ConsultarUP');
  $router->POST('_15100/ConsultarOperador' , 'Estoque\_15100Controller@ConsultarOperador');
  $router->POST('_15100/ConsultarPeca'     , 'Estoque\_15100Controller@ConsultarPeca');
  $router->POST('_15100/Abastercer'        , 'Estoque\_15100Controller@Abastercer');   

});
