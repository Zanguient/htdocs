<?php
  
	/**
	* Rotas do objeto Menu
	* @package Helper
	* @category Rotas
	*/


    Route::get ('current-time-server', function(){ echo date("Y-m-d H:i:s");}); //post registro
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
		$router->get ('/home'           , 'Menu\MenuController@home'            ); //Página inicial
		$router->post('/filtraMenu'     , 'Menu\MenuController@filtraMenu'      );
		$router->post('/filtraMenuGrupo', 'Menu\MenuController@filtraMenuGrupo' );
		$router->post('/listarMenu'     , 'Menu\MenuController@listarMenu'      );
        
        $router->get('/abainativa/{url}' , 'Menu\MenuController@tabInativa'    );
		
	});