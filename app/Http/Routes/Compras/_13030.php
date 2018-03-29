<?php

	/**
	* Rotas do objeto _13030
	* @package Compras
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {

        $router->any('_13030', 
          function() { return redirect('_13030/ng'); 
        });

        $router->any ('_13030/api/cotas'      , 'Compras\_13030\_13030ControllerApi@getCotas'  ); //Autenticar UP
        $router->any ('_13030/api/cota'       , 'Compras\_13030\_13030ControllerApi@getCota'   ); //Autenticar UP
        $router->post('_13030/api/cota/insert', 'Compras\_13030\_13030ControllerApi@insertCota'); //Autenticar UP
        $router->post('_13030/api/cota/delete', 'Compras\_13030\_13030ControllerApi@deleteCota'); //Autenticar UP
        $router->post('_13030/api/cota/update', 'Compras\_13030\_13030ControllerApi@updateCota'); //Autenticar UP
        
        $router->post('_13030/api/cota/extra/insert', 'Compras\_13030\_13030ControllerApi@insertCotaExtra'); //Autenticar UP
        $router->post('_13030/api/cota/extra/delete', 'Compras\_13030\_13030ControllerApi@deleteCotaExtra'); //Autenticar UP
        
        $router->post('_13030/api/cota/reducao/insert', 'Compras\_13030\_13030ControllerApi@insertCotaReducao'); //Autenticar UP
        $router->post('_13030/api/cota/reducao/delete', 'Compras\_13030\_13030ControllerApi@deleteCotaReducao'); //Autenticar UP
        
        $router->any ('_13030/api/cota/ajuste-inventario/detalhe', 'Compras\_13030\_13030ControllerApi@getCotaAjusteInventarioDetalhe'); //Autenticar UP
        $router->any ('_13030/api/cota/ggf/detalhe', 'Compras\_13030\_13030ControllerApi@getCotaGgfDetalhe'); //Autenticar UP

        $router->resource('_13030/ng', 'Compras\_13030\_13030Controller'); //Rotas Default        

        
        
		$router->post('_13030/consultaCota'       , 'Compras\_13030Controller@consultaCota'       ); //Consulta cota (ajax)
        $router->post('_13030/listar'             , 'Compras\_13030Controller@listar'             ); //Filtrar Accordion
		$router->post('_13030/listarCotas'        , 'Compras\_13030Controller@listarCotas'        ); //Conulsta das cotas por centro de custo (ajax)
        $router->post('_13030/DeletaItemAccordion', 'Compras\_13030Controller@DeletaItemAccordion'); //Deleta item direto no accordion
        $router->post('_13030/show'               , 'Compras\_13030Controller@show'               ); //Visualizar o body do show
        
        //Rotas GGF
        $router->post('_13030/ggf'                , 'Compras\_13030Controller@ggf'                ); //Filtra a cota
        $router->post('_13030/ggfDetalhe'         , 'Compras\_13030Controller@ggfDetalhe'         ); //Filtra a cota
        
        //Rotas do DRE
        $router->get ('_13030/dre'                , 'Compras\_13030Controller@dre'                ); //Exibe a listagem de todas Cotas no sentido horizontal
        $router->post('_13030/dre/filter'         , 'Compras\_13030Controller@dreFilter'          ); //Filtra a cota
        $router->post('_13030/dre/pdf'            , 'Compras\_13030Controller@drePdf'             ); //Impressão das cotas horizontais
        
        //Rotas do Gerenciamento de Faturamento nas Cotas
        $router->get   ('_13030/faturamento'       , ['as' => '_13030.faturamento'         , 'uses' => 'Compras\_13030Controller@fatIndex']);
        $router->post  ('_13030/faturamento'       , ['as' => '_13030.faturamento.store'   , 'uses' => 'Compras\_13030Controller@fatStore']);
        $router->put   ('_13030/faturamento'       , ['as' => '_13030.faturamento.update'  , 'uses' => 'Compras\_13030Controller@fatUpdate']);
        $router->delete('_13030/faturamento'       , ['as' => '_13030.faturamento.destroy' , 'uses' => 'Compras\_13030Controller@fatDestroy']);
        $router->get   ('_13030/faturamento/table' , ['as' => '_13030.faturamento.table'   , 'uses' => 'Compras\_13030Controller@fatTable']);

        //Rotas das Replicas de Cotas
        $router->get ('_13030/replicar', ['as' => '_13030.replicar'      , 'uses' => 'Compras\_13030Controller@replicarIndex']);
        $router->post('_13030/replicar', ['as' => '_13030.replicar.store', 'uses' => 'Compras\_13030Controller@replicarStore']);
        
//        //Cotas por Centro de Custo / Rotas Default
//        $router->resource('_13030', 'Compras\_13030Controller');
	});