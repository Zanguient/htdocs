<?php

 /**
  * Rotas do objeto _22150 - Painel de Ferramentas
  * @package Ppcp
  * @category Rotas
  */


    
	Route::post('correios', 'Ppcp\_22150\Controller@correios'); //post registro
	Route::get ('correios', 'Ppcp\_22150\Controller@correios'); //post registro

    Route::get ('_22150/painel/sse'                   ,'Ppcp\_22150\ControllerPainel@sse'                  ); //Rota para acessar os itens dos agrupamentos
    Route::get ('_22150/painel/ferramenta-programada' ,'Ppcp\_22150\ControllerPainel@ferramentaProgramada' ); //Rota para acessar os itens dos agrupamentos
    Route::post('_22150/painel/ferramenta-programada' ,'Ppcp\_22150\ControllerPainel@ferramentaProgramada' ); //Rota para acessar os itens dos agrupamentos    
    
	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_22150/acao/registrar'               ,'Ppcp\_22150\ControllerAcao@registrar'              ); //Rota para acessar os itens dos agrupamentos
        $router->post('_22150/ferramenta/historico'         ,'Ppcp\_22150\ControllerFerramenta@historico'        ); //Rota para acessar os itens dos agrupamentos
        $router->get ('_22150/ferramenta/historico'         ,'Ppcp\_22150\ControllerFerramenta@historico'        ); //Rota para acessar os itens dos agrupamentos
        $router->post('_22150/ferramenta/listar-disponiveis','Ppcp\_22150\ControllerFerramenta@listarDisponiveis'); //Rota para acessar os itens dos agrupamentos
        $router->get ('_22150/ferramenta/listar-disponiveis','Ppcp\_22150\ControllerFerramenta@listarDisponiveis'); //Rota para acessar os itens dos agrupamentos
        $router->post('_22150/ferramenta/alterar'           ,'Ppcp\_22150\ControllerFerramenta@alterar'          ); //Rota para acessar os itens dos agrupamentos
        
        $router->resource('_22150', 'Ppcp\_22150\Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
