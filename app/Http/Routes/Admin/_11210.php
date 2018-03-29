<?php

 /**
  * Rotas do objeto _11210 - Cadastro de Perfil de Usuario
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11210', 'Admin\_11210Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		$router->POST('_11210/consultar'        , 'Admin\_11210Controller@consultar');
		$router->POST('_11210/incluir'          , 'Admin\_11210Controller@incluir');
		$router->POST('_11210/alterar'          , 'Admin\_11210Controller@alterar');
		$router->POST('_11210/excluir'          , 'Admin\_11210Controller@excluir');

		$router->POST('_11210/consultar_itens'  , 'Admin\_11210Controller@consultar_itens');
		$router->POST('_11210/incluir_itens'    , 'Admin\_11210Controller@incluir_itens');
		$router->POST('_11210/alterar_itens'    , 'Admin\_11210Controller@alterar_itens');
		$router->POST('_11210/excluir_itens'    , 'Admin\_11210Controller@excluir_itens');
		$router->POST('_11210/ConsultaUsuario'  , 'Admin\_11210Controller@ConsultaUsuario');

		$router->POST('_11210/consulta_menu'          , 'Admin\_11210Controller@consulta_menu');
		$router->POST('_11210/incluir_menu'           , 'Admin\_11210Controller@incluir_menu');
		$router->POST('_11210/excluir_menu'           , 'Admin\_11210Controller@excluir_menu');
		$router->POST('_11210/consultar_perfil_menu'  , 'Admin\_11210Controller@consultar_perfil_menu');

		$router->POST('_11210/consulta_grupo'         , 'Admin\_11210Controller@consulta_grupo');
		$router->POST('_11210/incluir_grupo'          , 'Admin\_11210Controller@incluir_grupo');
		$router->POST('_11210/excluir_grupo'          , 'Admin\_11210Controller@excluir_grupo');
		$router->POST('_11210/consultar_perfil_grupo' , 'Admin\_11210Controller@consultar_perfil_grupo');

	});
