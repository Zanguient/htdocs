<?php

 /**
  * Rotas do objeto _15080 - Transacoes de Consumos de Remessas
  * @package Estoque
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->any('_15080/api/localizacoes', 'Estoque\_15080\_15080ControllerApi@getLocalizacoes');
        
        $router->any('_15080/api/produto-estoque-minimo', 'Estoque\_15080\_15080ControllerApi@getProdutoEstoqueMinimo');
        
        $router->any ('_15080/api/transacao'         , 'Estoque\_15080\_15080ControllerApi@getTransacao'   );
        $router->post('_15080/api/transacao/post'    , 'Estoque\_15080\_15080ControllerApi@postTransacao'  );
        $router->post('_15080/api/transacao/delete'  , 'Estoque\_15080\_15080ControllerApi@deleteTransacao');
        
        $router->post('_15080/api/avulso'            , 'Estoque\_15080\_15080ControllerApi@postAvulso');
        
        $router->post('_15080/api/peca'              , 'Estoque\_15080\_15080ControllerApi@postPeca');
        
        $router->any ('_15080/api/lotes'             , 'Estoque\_15080\_15080ControllerApi@getLotes');
        $router->any ('_15080/api/lotes_gerados'     , 'Estoque\_15080\_15080ControllerApi@lotes_gerados');
        $router->any ('_15080/api/lote'              , 'Estoque\_15080\_15080ControllerApi@getLote');
        $router->post('_15080/api/lote/iniciar'      , 'Estoque\_15080\_15080ControllerApi@postLoteIniciar');
        $router->post('_15080/api/lote/finalizar'    , 'Estoque\_15080\_15080ControllerApi@postLoteFinalizar');
        $router->post('_15080/api/lote/continuar'    , 'Estoque\_15080\_15080ControllerApi@postLoteContinuar');
        $router->post('_15080/api/lote/cancelar'     , 'Estoque\_15080\_15080ControllerApi@postLoteCancelar');
        $router->post('_15080/api/lote/excluirItem'  , 'Estoque\_15080\_15080ControllerApi@excluirItem');
        $router->post('_15080/api/lote/imprimirLote' , 'Estoque\_15080\_15080ControllerApi@imprimirLote');
        
        
        $router->get('_15080', 'Estoque\_15080\_15080Controller@index');
	});
