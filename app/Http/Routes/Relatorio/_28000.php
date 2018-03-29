<?php

 /**
  * Rotas do objeto _28000 - Relatorios Personalizados
  * @package Relatorio
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_28000', 'Relatorio\_28000Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY

        $router->POST('_28000/{$id}', 'Relatorio\_28000Controller@show');

        $router->POST('_28000/exportcsv', 'Relatorio\_28000Controller@exportcsv'); 
		
	});
