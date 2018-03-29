<?php
  
	/**
	* Rotas do objeto Historico
	* @package Helper
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
	
        $router->post('/historico', 'Helper\HistoricoController@GetHistorico');
        
        $router->any('/api/historico', 'Helper\HistoricoController@getApiHistorico');
		
	});