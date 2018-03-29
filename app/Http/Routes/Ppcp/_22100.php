<?php

 /**
  * Rotas do objeto _22100 - Geracao de Remessas de Bojo
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_22100/getNecessidadeItens', 'Ppcp\_22100\Controller@getNecessidadeItens'); //Rota para acessar os itens dos agrupamentos
        $router->get ('_22100/getNecessidadeItens', 'Ppcp\_22100\Controller@getNecessidadeItens'); //Rota para acessar os itens dos agrupamentos
        
//        $router->get('_22100/teste', 'Ppcp\_22100\ControllerStore@teste'); //Rota para acessar os itens dos agrupamentos
        
        $router->post('_22100/gravar', 'Ppcp\_22100\ControllerStore@store'); //Rota para acessar os itens dos agrupamentos
        
        $router->post('_22100/pedidos-desbloqueio/post', 'Ppcp\_22100\ControllerUtils@postPedidoDesbloqueio'); //Rota para acessar os itens dos agrupamentos
        
        $router->any('_22100/modelo-tempo', 'Ppcp\_22100\ControllerUtils@modeloTempo'); //Rota para acessar os itens dos agrupamentos
        $router->any('_22100/sku-defeito-percentual', 'Ppcp\_22100\ControllerUtils@skuDefeitoPercentual'); //Rota para acessar os itens dos agrupamentos
        $router->any('_22100/linha-remessa-historico', 'Ppcp\_22100\ControllerUtils@linhaRemessaHistorico'); //Rota para acessar os itens dos agrupamentos
        $router->any('_22100/pedido-bloqueio-usuario', 'Ppcp\_22100\ControllerUtils@pedidoBloqueioUsuario'); //Rota para acessar os itens dos agrupamentos
        
        $router->resource('_22100', 'Ppcp\_22100\Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
