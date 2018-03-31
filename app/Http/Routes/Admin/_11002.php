<?php

 /**
  * Rotas do objeto _11002 - Usuarios
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11002', 'Admin\_11002Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		
		$router->POST('_11002/consultar'        , 'Admin\_11002Controller@consultar');
		$router->POST('_11002/incluir'          , 'Admin\_11002Controller@incluir');
		$router->POST('_11002/alterar'          , 'Admin\_11002Controller@alterar');
		$router->POST('_11002/excluir'          , 'Admin\_11002Controller@excluir');

		$router->POST('_11002/consultar_itens'  , 'Admin\_11002Controller@consultar_itens');
		$router->POST('_11002/incluir_itens'    , 'Admin\_11002Controller@incluir_itens');
		$router->POST('_11002/alterar_itens'    , 'Admin\_11002Controller@alterar_itens');
		$router->POST('_11002/excluir_itens'    , 'Admin\_11002Controller@excluir_itens');
		$router->POST('_11002/resetarSenhaSuper', 'Admin\_11002Controller@resetarSenhaSuper');

		$router->POST('_11002/atualizarMenusUser', 'Admin\_11002Controller@atualizarMenusUser');
		
	});
