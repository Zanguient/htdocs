<?php

 /**
  * Rotas do objeto _11020
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

		//$router->resource('_11020', 'Admin\_11020Controller');
		//listar estabelecimentos (ajax)
		$router->post('_11020/listarSelect', 'Admin\_11020Controller@listarSelect');
		//paginação por scroll (ajax)
		//$router->post('_11020/paginacaoScroll', 'Admin\_11020Controller@paginacaoScroll');
		
        $router->any('_11020/api/estabelecimento', 'Admin\_11020\_11020ControllerApi@getEstabelecimento');
	});

