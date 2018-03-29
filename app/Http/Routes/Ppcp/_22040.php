<?php

	/**
	* Rotas do objeto _22040
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
        
        $router->post('_22040/remessaConsumo' 			, 'Ppcp\_22040Controller@remessaConsumo'  			); //Consulta remessas de consumo		
		$router->post('_22040/showNecessidade'			, 'Ppcp\_22040Controller@showNecessidade' 			); //Consulta necessidade da remessa
		$router->post('_22040/pesqRemessa'    			, 'Ppcp\_22040Controller@pesqRemessa'     			); //Consulta remessa
        $router->post('_22040/gerarAuto'      			, 'Ppcp\_22040Controller@remessaGerarAuto'			); //Geração automática da remessa
        $router->post('_22040/filtrar'        			, 'Ppcp\_22040Controller@filtrar'         			); //Filtrar remessas
        $router->post('_22040/tempo'          			, 'Ppcp\_22040Controller@tempo'           			); //Calcular o tempo dos produtos
        $router->get ('_22040/show/{id}'      			, 'Ppcp\_22040Controller@show'            			); //Visualizar um item body
        $router->post('_22040/store'          			, 'Ppcp\_22040Controller@store'           			); //Visualizar um item body
        $router->post('_22040/reabrirTalao'   			, 'Ppcp\_22040Controller@reabrirTalao'    			); //reabrir talao finalizado        
		$router->post('_22040/getPdfConsumo'			, 'Ppcp\_22040Controller@getPdfConsumo'				); //Imprimir relatório de consumo
        $router->post('_22040/verificarRemessaExiste'   , 'Ppcp\_22040Controller@verificarRemessaExiste'    ); //verificar se a remessa já existe
        $router->get ('_22040/getPdfConsumo'            , 'Ppcp\_22040Controller@getPdfConsumo'             ); //Imprimir relatório de consumo
        $router->post('_22040/atualizarCotaCliente'     , 'Ppcp\_22040Controller@atualizarCotaCliente'      ); //Imprimir relatório de consumo
        
        
        $router->any ('_22040/api/reposicao'          , 'Ppcp\_22040\_22040ControllerApi@getReposicao');        
        $router->any ('_22040/api/reposicao/pedido'   , 'Ppcp\_22040\_22040ControllerApi@getPedido');        
        $router->any ('_22040/api/reposicao/producao' , 'Ppcp\_22040\_22040ControllerApi@getProducao');        
        $router->any ('_22040/api/reposicao/empenhado', 'Ppcp\_22040\_22040ControllerApi@getEmpenhado');
        
        $router->resource('_22040', 'Ppcp\_22040Controller'); //Rotas Default
		
	});
