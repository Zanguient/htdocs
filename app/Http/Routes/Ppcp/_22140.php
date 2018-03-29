<?php

 /**
  * Rotas do objeto _22140 - Painel de Programacao
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->get ('_22140/find', 'Ppcp\_22140\ControllerFind@find'); //Rota para acessar os itens dos agrupamentos
        $router->post('_22140/find', 'Ppcp\_22140\ControllerFind@find'); //Rota para acessar os itens dos agrupamentos
        
        $router->any ('_22140/api/programacao-estacao'             , 'Ppcp\_22140\_22140ControllerApi@getProgramacaoEstacao'); //Rota para acessar os itens dos agrupamentos
        $router->any ('_22140/api/programacao/gp'                  , 'Ppcp\_22140\_22140ControllerApi@getProgramacaoGp'); //Rota para acessar os itens dos agrupamentos
        $router->any ('_22140/api/programacao/gp/calendario/update', 'Ppcp\_22140\_22140ControllerApi@updateProgramacaoGpCalendario'); //Rota para acessar os itens dos agrupamentos
        
        $router->post('_22140/api/programacao-estacao/post', 'Ppcp\_22140\_22140ControllerApi@postProgramacaoEstacao'); //Rota para acessar os itens dos agrupamentos
        
        $router->resource('_22140', 'Ppcp\_22140\Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
