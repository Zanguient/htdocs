<?php
  
	/**
	* Rotas do objeto _13010
	* @package Compras
	* @category Rotas
	*/
	
	
	//Rotas protegidas. Só devem ser acessadas após login.
	Route::group(['middleware' => 'auth'], function($router) {
		
        $router->resource('_13010'                  , 'Compras\_13010Controller'                );  //Geracao de requisicao
        $router->post    ('_13010/excluiProduto'    , 'Compras\_13010Controller@excluiProduto'  );  //exclui produto (ajax)
        $router->post    ('_13010/filtraObj'        , 'Compras\_13010Controller@filtraObj'      );  //filtra requisicoes (ajax)
		$router->post    ('_13010/listarTamanho'    , 'Compras\_13010Controller@listarTamanho'  );  //exibe tamanhos
        $router->post    ('_13010/paginacaoScroll'  , 'Compras\_13010Controller@paginacaoScroll');  //paginacao por scroll (ajax)
        $router->post    ('_13010/pesquisaGestor'   , 'Compras\_13010Controller@pesquisaGestor' );  //pesquisa gestor (ajax)
        $router->post    ('_13010/pesquisaProduto'  , 'Compras\_13010Controller@pesquisaProduto');  //pesquisa produto (ajax)
		$router->post    ('_13010/viewarquivo'      , 'Compras\_13010Controller@viewArquivo'    );  //ver arquivo

		//teste ajax
		$router->post('/_13010/enviaArquivo', 'Compras\_13010Controller@enviaArquivo');
		$router->post('/_13010/gravaArquivo', 'Compras\_13010Controller@gravaArquivo');
		$router->post('/_13010/excluiArquivo', 'Compras\_13010Controller@excluiArquivo');
		$router->post('/_13010/ConteudoArquivo', 'Compras\_13010Controller@ConteudoArquivo');
		$router->post('/_13010/DownloadArquivo', 'Compras\_13010Controller@downloadArquivo');		
		
		$router->post('/deletararquivo', 'Compras\_13010Controller@deletararquivo');		
		
	});