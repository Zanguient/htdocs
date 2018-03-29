<?php

 /**
  * Rotas do objeto _22021 - Relatório de peças disponíveis para consumo
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_22021', 'Ppcp\_22021Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		$router->post('_22021/relatorioPecaDisponivel', 'Ppcp\_22021Controller@relatorioPecaDisponivel'); //Relatório de peças disponiveis
		
	});
