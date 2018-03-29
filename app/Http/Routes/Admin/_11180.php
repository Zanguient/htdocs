<?php

 /**
  * Rotas do objeto _11180 - Blok
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_11180', 'Admin\_11180Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
		  $router->POST('_11180/Consultar', 'Admin\_11180Controller@Consultar');
      $router->POST('_11180/url'      , 'Admin\_11180Controller@url');
      $router->POST('_11180/janela'   , 'Admin\_11180Controller@janela');
      $router->POST('_11180/excluir'  , 'Admin\_11180Controller@excluir');
      $router->POST('_11180/gravar'   , 'Admin\_11180Controller@gravar');
		
	  });
