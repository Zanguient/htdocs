<?php
  
 /**
  * Rotas do objeto _26010
  * @package Fiscal
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->get ('_26010/show/{id}', 'Chamados\_26010Controller@show'); //Visualizar o body do show
        
    	$router->resource   ('_26010', 'Chamados\_26010Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
    });