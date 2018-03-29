<?php

 /**
  * Rotas do objeto _22170 - Registro de Producao - Div. Bojo Colante
  * @package Ppcp
  * @category Rotas
  */


    Route::get ('_22170/sse/taloes/composicao','Ppcp\_22170\_22170ControllerApi@sse'); //Rota para acessar os itens dos agrupamentos
        //
	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any('_22170/api/taloes/composicao', 'Ppcp\_22170\_22170ControllerApi@getTaloesComposicao');
        
        $router->any('_22170/api/taloes/acao/{tipo}', 'Ppcp\_22170\_22170ControllerApi@postTaloesAcao');
        
        $router->any('_22170/api/talao'           , 'Ppcp\_22170\_22170ControllerApi@getTalao');
        $router->any('_22170/api/talao/composicao', 'Ppcp\_22170\_22170ControllerApi@getTalaoComposicao');
        
        $router->get('_22170', 'Ppcp\_22170\_22170Controller@index');
		
	});
