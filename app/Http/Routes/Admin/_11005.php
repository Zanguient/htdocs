<?php

 /**
  * Rotas do objeto _11005 - Registro de Producao - Div. Bojo Colante
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_11005/api/tabela' , 'Admin\_11005\_11005ControllerApi@getTabela');
        $router->any ('_11005/api/parametro/tabela' , 'Admin\_11005\_11005ControllerApi@getParametroTabela');
        $router->any ('_11005/api/parametro/{tabela}' , 'Admin\_11005\_11005ControllerApi@getParametro');
        $router->any ('_11005/api/parametro/detalhe/{parametro_id}' , 'Admin\_11005\_11005ControllerApi@getParametroDetalhe');
        $router->any ('_11005/api/parametro/detalhe/tabela/{tabela}' , 'Admin\_11005\_11005ControllerApi@getParametroDetalheTabela');
        $router->any ('_11005/api/parametro/detalhe/{tabela}/{tabela_id}' , 'Admin\_11005\_11005ControllerApi@getParametroDetalheItem');
        
        $router->get('_11005', 'Admin\_11005\_11005Controller@index');
		
	});
