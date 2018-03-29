<?php

 /**
  * Rotas do objeto _12050 - RELATORIO DE PEDIDOS X FATURAMENTO X PRODUCAO
  * @package vendas
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->resource('_12050', 'Vendas\_12050Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
		
        $router->post('_12050/relatorio',        'Vendas\_12050Controller@relatorio'       ); //Relatório
        $router->post('_12050/detalharFamilia',  'Vendas\_12050Controller@detalharFamilia' ); //Relatório
        $router->post('_12050/detalharFamilia2', 'Vendas\_12050Controller@detalharFamilia2'); //Relatório

        $router->post('_12050/faturamentoDia',   'Vendas\_12050Controller@faturamentoDia'  );
        $router->post('_12050/pedidosDia',       'Vendas\_12050Controller@pedidosDia'      );
        $router->post('_12050/devolucaoDia',     'Vendas\_12050Controller@devolucaoDia'    );
        $router->post('_12050/defeitoDia',       'Vendas\_12050Controller@defeitoDia'      );
        $router->post('_12050/producaoDia',      'Vendas\_12050Controller@producaoDia'     );
        $router->post('_12050/producaoDia2',     'Vendas\_12050Controller@producaoDia2'    );
		
	});
