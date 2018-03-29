<?php

	/**
	* Rotas do objeto _22010
	* @package Ppcp
	* @category Rotas
	*/
	    
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {		
	
        $router->post('_22010/totalizador'			      , 'Ppcp\_22010Controller@talaototalizador'	       ); //Talões a produzir
        $router->post('_22010/talaoProduzir'		      , 'Ppcp\_22010Controller@talaoProduzir'		       ); //Talões a produzir
        $router->post('_22010/talaoProduzido'		      , 'Ppcp\_22010Controller@talaoProduzido'	           ); //Talões produzidos
        $router->post('_22010/totalizadorDiario'	      , 'Ppcp\_22010Controller@totalizadorDiario'          ); //Totalizadores diários
        $router->post('_22010/talaoHistorico'		      , 'Ppcp\_22010Controller@talaoHistorico'	           ); //Historico de Ações nos talões
        $router->post('_22010/talaoDetalhe'			      , 'Ppcp\_22010Controller@talaoDetalhe'		       ); //Historico de Ações nos talões
        $router->post('_22010/talaoMateriaPrima'	      , 'Ppcp\_22010Controller@talaoMateriaPrima'	       ); //Materia prima dos talões
        $router->post('_22010/talaoDefeito'			      , 'Ppcp\_22010Controller@talaoDefeito'		       ); //Defeitos dos talões
        $router->post('_22010/verificarEstacaoAtiva'      , 'Ppcp\_22010Controller@verificarEstacaoAtiva'      ); //Verificar se a estação está ativa
        $router->post('_22010/talaoValido'                , 'Ppcp\_22010Controller@talaoValido'                ); //Verifica se um talão está valido
        $router->post('_22010/acao/{tipo}'                , 'Ppcp\_22010Controller@acao'                       ); //Registra a ação do talão
        $router->post('_22010/registrarMateriaPrima'      , 'Ppcp\_22010Controller@registrarMateriaPrima'      ); //Registra a ação do talão
        $router->post('_22010/baixarQuantidadeProduzida'  , 'Ppcp\_22010Controller@baixarQuantidadeProduzida'  ); //Baixa a quantidade produzida
        $router->post('_22010/registrarComponente'        , 'Ppcp\_22010Controller@registrarComponente'        ); //Registra a ação do talão
        $router->post('_22010/registrarAproveitamento'    , 'Ppcp\_22010Controller@registrarAproveitamento'    ); //Registra o aproveitamento
        $router->post('_22010/registroPesagem'            , 'Ppcp\_22010Controller@registroPesagem'            ); //Registra a ação do talão
        $router->post('_22010/alterarQtdAlocada'	      , 'Ppcp\_22010Controller@alterarQtdAlocada'	       ); //Alterar a quantidade alocada
        $router->post('_22010/alterarQtdTalaoDetalhe'     , 'Ppcp\_22010Controller@alterarQtdTalaoDetalhe'     ); //Alterar a quantidade do detalhe do talão
        $router->post('_22010/alterarTodasQtdTalaoDetalhe', 'Ppcp\_22010Controller@alterarTodasQtdTalaoDetalhe'); //Alterar todas as quantidades do detalhe do talão
        $router->post('_22010/etiqueta'                   , 'Ppcp\_22010Controller@etiqueta'                   ); //Alterar a quantidade do detalhe do talão
        $router->post('_22010/recarregarStatus'		      , 'Ppcp\_22010Controller@recarregarStatus'	       ); //Recarregar o status do talão
        $router->post('_22010/consultarTalaoComposicao'	  , 'Ppcp\_22010Controller@consultarTalaoComposicao'   ); //Consultar a composição do talão
        $router->post('_22010/projecaoVinculoExcluir'	  , 'Ppcp\_22010Controller@projecaoVinculoExcluir'     ); //Consultar os dados do talão
        $router->post('_22010/alterarQtdSobraMaterial'	  , 'Ppcp\_22010Controller@alterarQtdSobraMaterial'    ); //Consultar os dados do talão        
        $router->post('_22010/autenticarUp'				  , 'Ppcp\_22010Controller@autenticarUp'			   ); //Autenticar UP
        
        

        $router->post('_22010/consultaJustificativa', 'Ppcp\_22010Controller@consultaJustificativa'); //Autenticar Operador
        $router->post('_22010/api/problema/operador', 'Ppcp\_22010Controller@getOperadorProblema'); //Autenticar Operador
        
        
        $router->post('_22010/api/talao/produzir/all', 'Ppcp\_22010\ControllerApi@getTalaoProduzirAll'); //Autenticar UP
        $router->get ('_22010/api/talao/produzir/all', 'Ppcp\_22010\ControllerApi@getTalaoProduzirAll'); //Autenticar UP
        
        $router->post('_22010/api/talao/produzido/all', 'Ppcp\_22010\ControllerApi@getTalaoProduzidoAll'); //Autenticar UP
        $router->get ('_22010/api/talao/produzido/all', 'Ppcp\_22010\ControllerApi@getTalaoProduzidoAll'); //Autenticar UP
        
        $router->post('_22010/api/talao/composicao', 'Ppcp\_22010\ControllerApi@getTalaoComposicao'); //Autenticar UP
        $router->get ('_22010/api/talao/composicao', 'Ppcp\_22010\ControllerApi@getTalaoComposicao'); //Autenticar UP
        
        $router->post('_22010/api/talao/consumo/pecas-disponiveis', 'Ppcp\_22010\ControllerApi@getTalaoConsumoPecasDisponiveis'); //Autenticar UP
        $router->get ('_22010/api/talao/consumo/pecas-disponiveis', 'Ppcp\_22010\ControllerApi@getTalaoConsumoPecasDisponiveis'); //Autenticar UP
        
        $router->get ('_22010/api/defeitos', 'Ppcp\_22010\ControllerApi@getDefeitos'); //Autenticar UP
        $router->post('_22010/api/defeitos', 'Ppcp\_22010\ControllerApi@getDefeitos'); //Autenticar UP
        
        $router->get ('_22010/api/defeitos/post', 'Ppcp\_22010\ControllerApi@postDefeitos'); //Autenticar UP
        $router->post('_22010/api/defeitos/post', 'Ppcp\_22010\ControllerApi@postDefeitos'); //Autenticar UP
                
        $router->get ('_22010/api/defeitos/exclude', 'Ppcp\_22010\ControllerApi@excludeDefeitos'); //Autenticar UP
        $router->post('_22010/api/defeitos/exclude', 'Ppcp\_22010\ControllerApi@excludeDefeitos'); //Autenticar UP
                        
        $router->get ('_22010/api/ficha/post', 'Ppcp\_22010\ControllerApi@postFicha'); //Autenticar UP
        $router->post('_22010/api/ficha/post', 'Ppcp\_22010\ControllerApi@postFicha'); //Autenticar UP
                        
        $router->get ('_22010/api/justificativa', 'Ppcp\_22010\ControllerApi@getJustificativa'); //Autenticar UP
        $router->post('_22010/api/justificativa', 'Ppcp\_22010\ControllerApi@getJustificativa'); //Autenticar UP
                        
        $router->get ('_22010/api/totalizador-diario', 'Ppcp\_22010\ControllerApi@getTotalizadorDiario'); //Autenticar UP
        $router->post('_22010/api/totalizador-diario', 'Ppcp\_22010\ControllerApi@getTotalizadorDiario'); //Autenticar UP
                        
        $router->get ('_22010/api/talao-vinculo-modelos', 'Ppcp\_22010\ControllerApi@getTalaoVinculoModelos'); //Autenticar UP
        $router->post('_22010/api/talao-vinculo-modelos', 'Ppcp\_22010\ControllerApi@getTalaoVinculoModelos'); //Autenticar UP
        
        $router->resource('_22010', 'Ppcp\_22010Controller'); //Rotas Default
	});
