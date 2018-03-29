<?php

	/**
	* Rotas do objeto _22020
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
	
        $router->any('_22020', 
          function() { return redirect('/_22010'); 
          
          });
        
//        $router->post('_22020/totalizador'			      , 'Ppcp\_22020Controller@talaototalizador'	       ); //Talões a produzir
//        $router->post('_22020/talaoProduzir'		      , 'Ppcp\_22020Controller@talaoProduzir'		       ); //Talões a produzir
//        $router->post('_22020/talaoProduzido'		      , 'Ppcp\_22020Controller@talaoProduzido'	           ); //Talões produzidos
//        $router->post('_22020/totalizadorDiario'	      , 'Ppcp\_22020Controller@totalizadorDiario'          ); //Totalizadores diários
//        $router->post('_22020/talaoHistorico'		      , 'Ppcp\_22020Controller@talaoHistorico'	           ); //Historico de Ações nos talões
//        $router->post('_22020/talaoDetalhe'			      , 'Ppcp\_22020Controller@talaoDetalhe'		       ); //Historico de Ações nos talões
//        $router->post('_22020/talaoMateriaPrima'	      , 'Ppcp\_22020Controller@talaoMateriaPrima'	       ); //Materia prima dos talões
//        $router->post('_22020/talaoDefeito'			      , 'Ppcp\_22020Controller@talaoDefeito'		       ); //Defeitos dos talões
//        $router->post('_22020/verificarEstacaoAtiva'      , 'Ppcp\_22020Controller@verificarEstacaoAtiva'      ); //Verificar se a estação está ativa
//        $router->post('_22020/talaoValido'                , 'Ppcp\_22020Controller@talaoValido'                ); //Verifica se um talão está valido
//        $router->post('_22020/acao/{tipo}'                , 'Ppcp\_22020Controller@acao'                       ); //Registra a ação do talão
//        $router->post('_22020/registrarMateriaPrima'      , 'Ppcp\_22020Controller@registrarMateriaPrima'      ); //Registra a ação do talão
//        $router->post('_22020/baixarQuantidadeProduzida'  , 'Ppcp\_22020Controller@baixarQuantidadeProduzida'  ); //Baixa a quantidade produzida
//        $router->post('_22020/registrarComponente'        , 'Ppcp\_22020Controller@registrarComponente'        ); //Registra a ação do talão
//        $router->post('_22020/registrarAproveitamento'    , 'Ppcp\_22020Controller@registrarAproveitamento'    ); //Registra o aproveitamento
//        $router->post('_22020/registroPesagem'            , 'Ppcp\_22020Controller@registroPesagem'            ); //Registra a ação do talão
//        $router->post('_22020/alterarQtdAlocada'	      , 'Ppcp\_22020Controller@alterarQtdAlocada'	       ); //Alterar a quantidade alocada
//        $router->post('_22020/alterarQtdTalaoDetalhe'     , 'Ppcp\_22020Controller@alterarQtdTalaoDetalhe'     ); //Alterar a quantidade do detalhe do talão
//        $router->post('_22020/alterarTodasQtdTalaoDetalhe', 'Ppcp\_22020Controller@alterarTodasQtdTalaoDetalhe'); //Alterar todas as quantidades do detalhe do talão
//        $router->post('_22020/etiqueta'                   , 'Ppcp\_22020Controller@etiqueta'                   ); //Alterar a quantidade do detalhe do talão
//        $router->post('_22020/recarregarStatus'		      , 'Ppcp\_22020Controller@recarregarStatus'	       ); //Recarregar o status do talão
//        $router->post('_22020/consultarTalaoComposicao'	  , 'Ppcp\_22020Controller@consultarTalaoComposicao'   ); //Consultar a composição do talão
//        $router->post('_22020/projecaoVinculoExcluir'	  , 'Ppcp\_22020Controller@projecaoVinculoExcluir'     ); //Consultar os dados do talão
//        $router->post('_22020/alterarQtdSobraMaterial'	  , 'Ppcp\_22020Controller@alterarQtdSobraMaterial'    ); //Consultar os dados do talão        
//        $router->post('_22020/autenticarUp'				  , 'Ppcp\_22020Controller@autenticarUp'			   ); //Autenticar UP
//        
//        $router->resource('_22020', 'Ppcp\_22020Controller'); //Rotas Default
	});
