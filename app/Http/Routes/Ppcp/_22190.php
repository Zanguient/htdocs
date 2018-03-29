<?php

 /**
  * Rotas do objeto _22190 - Registro de Producao - Div. Bojo Colante
  * @package Ppcp
  * @category Rotas
  */


    Route::get ('_22190/sse/taloes/composicao','Ppcp\_22190\_22190ControllerApi@sse'); //Rota para acessar os itens dos agrupamentos
        //
	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any('_22190/api/taloes/composicao', 'Ppcp\_22190\_22190ControllerApi@getTaloesComposicao');
        
        $router->any('_22190/api/taloes/acao/{tipo}', 'Ppcp\_22190\_22190ControllerApi@postTaloesAcao');
        
        $router->any('_22190/api/talao'           , 'Ppcp\_22190\_22190ControllerApi@getTalao');
        $router->any('_22190/api/talao/composicao', 'Ppcp\_22190\_22190ControllerApi@getTalaoComposicao');
        
        $router->post('_22190/api/consumo/componente/alocar'         , 'Ppcp\_22190\_22190ControllerApi@postComponenteAlocar');
        $router->post('_22190/api/consumo/componente/alocado/delete' , 'Ppcp\_22190\_22190ControllerApi@deleteComponenteAlocado');
        
        $router->post('_22190/consumo/alocado/excluir' , 'Ppcp\_22190Controller@consumoAlocadoExcluir');
        
        
        $router->get('_22190', 'Ppcp\_22190\_22190Controller@index');
		
	});
