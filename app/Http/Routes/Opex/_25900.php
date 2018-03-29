<?php

 /**
  * Rotas do objeto _25900
  * @package Opex
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_25900', 'Opex\_25900Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
        $router->POST('_25900/Areas'            , 'Opex\_25900Controller@consultarArea');
        $router->POST('_25900/Setores'          , 'Opex\_25900Controller@consultarSetor');
        $router->POST('_25900/Perspectivas'     , 'Opex\_25900Controller@consultarPerspectiva');
        
        $router->POST('_25900/filtarIndicador'  , 'Opex\_25900Controller@filtarIndicador');
        
	});
