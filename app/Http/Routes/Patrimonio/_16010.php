<?php

 /**
  * Rotas do objeto _16010 - Registro de Producao - Div. Bojo Colante
  * @package Patrimonio
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        
        $router->any ('_16010/api/imobilizados'                    , 'Patrimonio\_16010\_16010ControllerApi@getImobilizados');
        $router->any ('_16010/api/imobilizado'                     , 'Patrimonio\_16010\_16010ControllerApi@getImobilizado');
        $router->any ('_16010/api/imobilizado/parcelas'            , 'Patrimonio\_16010\_16010ControllerApi@getImobilizadoParcelas');
        $router->any ('_16010/api/imobilizado/item'                , 'Patrimonio\_16010\_16010ControllerApi@getImobilizadoItem');
        $router->any ('_16010/api/imobilizado/item/parcelas'       , 'Patrimonio\_16010\_16010ControllerApi@getImobilizadoItemParcelas');
        $router->post('_16010/api/imobilizado/item/encerrar'       , 'Patrimonio\_16010\_16010ControllerApi@postImobilizadoItemEncerrar');
        $router->post('_16010/api/imobilizado/excluir'             , 'Patrimonio\_16010\_16010ControllerApi@deleteImobilizado');
        $router->post('_16010/api/imobilizado/gravar'              , 'Patrimonio\_16010\_16010ControllerApi@postImobilizado');
        $router->post('_16010/api/imobilizado/depreciar'           , 'Patrimonio\_16010\_16010ControllerApi@postImobilizadoDepreciar');
        $router->any ('_16010/api/imobilizado/tipo'                , 'Patrimonio\_16010\_16010ControllerApi@getImobilizadoTipo');
        
        $router->any ('_16010/api/demonstratitvo-depreciacao', 'Patrimonio\_16010\_16010ControllerApi@getDemonstratitvoDepreciacao');
        
        
        $router->any ('_16010/api/nfs', 'Patrimonio\_16010\_16010ControllerApi@getNfs');
        
        $router->any ('_16010/api/nf/item', 'Patrimonio\_16010\_16010ControllerApi@getNfItem');
        
        $router->post('_16010/api/talao/liberar', 'Patrimonio\_16010\_16010ControllerApi@postTalaoLiberar');
        
        
        $router->get('_16010/{id?}', 'Patrimonio\_16010\_16010Controller@index');
		
	});
