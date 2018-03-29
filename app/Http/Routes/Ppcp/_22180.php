<?php

 /**
  * Rotas do objeto _22180 - Registro de Producao - Div. Bojo Colante
  * @package Ppcp
  * @category Rotas
  */


    Route::get ('_22180/sse/taloes/composicao','Ppcp\_22180\_22180ControllerApi@sse'); //Rota para acessar os itens dos agrupamentos
        //
	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any('_22180/api/taloes/composicao', 'Ppcp\_22180\_22180ControllerApi@getTaloesComposicao');
        
        $router->any('_22180/api/taloes/acao/{tipo}', 'Ppcp\_22180\_22180ControllerApi@postTaloesAcao');
        
        $router->any('_22180/api/talao'           , 'Ppcp\_22180\_22180ControllerApi@getTalao');
        $router->any('_22180/api/talao/composicao', 'Ppcp\_22180\_22180ControllerApi@getTalaoComposicao');
        
        $router->get('_22180', 'Ppcp\_22180\_22180Controller@index');
		
	});
