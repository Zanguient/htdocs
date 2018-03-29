<?php
  
 /**
  * Rotas do objeto _13040
  * @package Fiscal
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

        $router->get  ('_13040/{licitacao_id}' , 'Compras\_13040Controller@create');
        $router->patch('_13040/{licitacao_id}' , ['as' => '_13040.store', 'uses' => 'Compras\_13040Controller@store']);
        $router->get  ('_13041/{requisicao_id}', 'Compras\_13040Controller@ocDireta');
    });