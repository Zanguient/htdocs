<?php

 /**
  * Rotas do objeto _22120 - Estrutura Analítica de Remessas
  * @package Ppcp
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('_22120/find', 'Ppcp\_22120Controller@find'); //Rota para acessar as remessas vinculadas
        $router->get ('_22120/find', 'Ppcp\_22120Controller@find'); //Rota para acessar as remessas vinculadas
        $router->get ('_22120/arquivo', function(){
//            header("content-type: image/jpeg");
//            return file_get_contents('//Srv-Files/Arquivos/Arquivos/Ini/anuncio.jpg');

            $img = '//Srv-Files/Arquivos/Arquivos/Ini/anuncio.jpg';
            $getInfo = getimagesize($img);
            header('Content-type: ' . $getInfo['mime']);
            readfile($img);
            
        }); //Rota para acessar as remessas vinculadas
        
        
        $router->get('_22120/remessa-componente', 'Ppcp\_22120\_22120Controller@remessaComponente');
        
        $router->post('_22120/api/talao/liberacao/cancelar', 'Ppcp\_22120\_22120ControllerApi@postTalaoLiberacaoCancelar');
        $router->post('_22120/api/consumo/alterar', 'Ppcp\_22120\_22120ControllerApi@postConsumoAlterar');
        
        $router->any('_22120/api/origem-dados', 'Ppcp\_22120\_22120ControllerApi@getOrigemDados');
        $router->any('_22120/api/origem-necessidade', 'Ppcp\_22120\_22120ControllerApi@getOrigemNecessidade');
        
        $router->get('_22120/remessa-intermediaria', 'Ppcp\_22120\_22120Controller@remessaIntermediaria');
        
        $router->any('_22120/api/remessas-vinculo', 'Ppcp\_22120\_22120ControllerApi@getRemessasVinculo');
        $router->any('_22120/api/taloes-vinculo', 'Ppcp\_22120\_22120ControllerApi@getTaloesVinculo');
        
        $router->post('_22120/api/remessa/intermediaria', 'Ppcp\_22120\_22120ControllerApi@postRemessaIntermediaria');
        
        
        $router->any('_22120/remessas', 'Ppcp\_22120Controller@remessas'); //Rota para excluir talão acumulados / talões detalhado / consumos
        
        $router->any('_22120/remover/{tipo}', 'Ppcp\_22120Controller@remover'); //Rota para excluir talão acumulados / talões detalhado / consumos
        
        $router->any('_22120/reabrir/{tipo}', 'Ppcp\_22120Controller@reabrir'); //Rota reabrir talões detalhados
        
        $router->any('_22120/reabrir-detalhe/{tipo}', 'Ppcp\_22120Controller@reabrirDetalhe'); //Rota reabrir talões detalhados
        
        $router->any('_22120/desmembrar/{tipo}', 'Ppcp\_22120Controller@desmembrar'); //Rota desmembrar talões detalhados

        $router->any('_22120/encerrar/{tipo}', 'Ppcp\_22120Controller@encerrar'); //Rota encerrar talões detalhados

        $router->any('_22120/gerar-consumo', 'Ppcp\_22120Controller@gerarConsumo'); //Rota encerrar talões detalhados
       
        $router->any('_22120/get-taloes-extras', 'Ppcp\_22120Controller@getTaloesExtras'); //Rota encerrar talões detalhados
       
        $router->post('_22120/post-taloes-extras', 'Ppcp\_22120Controller@postTaloesExtras'); //Rota encerrar talões detalhados
        
        $router->post('_22120/post-aproveitamento-sobra', 'Ppcp\_22120Controller@postAproveitamentoSobra'); //Rota encerrar talões detalhados
        
        $router->resource('_22120', 'Ppcp\_22120Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
	});
