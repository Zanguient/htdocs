<?php

 /**
  * Rotas do objeto #TelaNO# - #Titulo#
  * @package #Grupos#
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('#TelaNO#', '#Grupos#\#TelaNO#Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		
		$router->POST('#TelaNO#/consultar'        , '#Grupos#\#TelaNO#Controller@consultar');
		$router->POST('#TelaNO#/incluir'          , '#Grupos#\#TelaNO#Controller@incluir');
		$router->POST('#TelaNO#/alterar'          , '#Grupos#\#TelaNO#Controller@alterar');
		$router->POST('#TelaNO#/excluir'          , '#Grupos#\#TelaNO#Controller@excluir');

		$router->POST('#TelaNO#/consultar_itens'  , '#Grupos#\#TelaNO#Controller@consultar_itens');
		$router->POST('#TelaNO#/incluir_itens'    , '#Grupos#\#TelaNO#Controller@incluir_itens');
		$router->POST('#TelaNO#/alterar_itens'    , '#Grupos#\#TelaNO#Controller@alterar_itens');
		$router->POST('#TelaNO#/excluir_itens'    , '#Grupos#\#TelaNO#Controller@excluir_itens');
		
	});
