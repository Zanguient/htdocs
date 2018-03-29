<?php

 /**
  * Rotas do objeto _27020 - Cadastro de Modelos
  * @package Produto
  * @category Rotas
  */

    Route::get('_27020/modalModeloPorCliente', 'Produto\_27020Controller@viewModeloPorCliente');  // View para consultar modelo por cliente.

    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any('_27020/api/modelo/tamanho', 'Produto\_27020\_27020ControllerApi@getModeloTamanho');
        $router->any('_27020/api/modelos'       , 'Produto\_27020\_27020ControllerApi@getModelos');
        
        $router->any('_27020/api/consultar-arquivo-conteudo/{id}'       , 'Produto\_27020\_27020ControllerApi@consultarArquivo');
                
        
	    $router->post('_27020/consultarModeloPorCliente', 'Produto\_27020Controller@consultarModeloPorCliente');
        // $router->get('_27020/modalModeloPorCliente', 'Produto\_27020Controller@viewModeloPorCliente');  // View para consultar modelo por cliente.
        $router->post('_27020/verArquivo', 'Produto\_27020Controller@verArquivo');
        $router->post('_27020/excluirArquivo', 'Produto\_27020Controller@excluirArquivo');
        $router->post('_27020/excluirArquivoPorUsuario', 'Produto\_27020Controller@excluirArquivoPorUsuario');

        $router->resource('_27020', 'Produto\_27020Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
