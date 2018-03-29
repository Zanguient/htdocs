<?php

 /**
  * Rotas do objeto _11150 - Registro de Casos
  * @package admin
  * @category Rotas
  */

	//Rotas protegidas.
  Route::group(['middleware' => 'auth'], function($router) {

        $router->get('_11150/{painel_id}'    , 'Admin\_11150Controller@index'         );
        $router->get('_11150'                , 'Admin\_11150Controller@paineisCasos'  );
        $router->post('_11150/getPainel'     , 'Admin\_11150Controller@getPainel'     );
		$router->post('_11150/Consultar'     , 'Admin\_11150Controller@Consultar'     );
        $router->post('_11150/Motivos'       , 'Admin\_11150Controller@Motivos'       );
        $router->post('_11150/Tipos'         , 'Admin\_11150Controller@Tipos'         );
        $router->post('_11150/Origens'       , 'Admin\_11150Controller@Origens'       );
        $router->post('_11150/Responsavel'   , 'Admin\_11150Controller@Responsavel'   );
        $router->post('_11150/Contatos'      , 'Admin\_11150Controller@Contatos'      );
        $router->post('_11150/confContato'   , 'Admin\_11150Controller@confContato'   );
        $router->post('_11150/Status'        , 'Admin\_11150Controller@Status'        );
        $router->post('_11150/gravarContato' , 'Admin\_11150Controller@gravarContato' );
        $router->post('_11150/confPainel'    , 'Admin\_11150Controller@confPainel'    );
        $router->post('_11150/Consultas'     , 'Admin\_11150Controller@Consultas'     );
        $router->post('_11150/gravarCaso'    , 'Admin\_11150Controller@gravarCaso'    );
        $router->post('_11150/getCasos'      , 'Admin\_11150Controller@getCasos'      );
        $router->post('_11150/historico'     , 'Admin\_11150Controller@historico'     );
        $router->post('_11150/comentario'    , 'Admin\_11150Controller@comentario'    );
        $router->post('_11150/excluirCaso'   , 'Admin\_11150Controller@excluirCaso'   );
        $router->post('_11150/gravarFeed'    , 'Admin\_11150Controller@gravarFeed'    );
        $router->post('_11150/excluirFeed'   , 'Admin\_11150Controller@excluirFeed'   );
        $router->post('_11150/gostei'        , 'Admin\_11150Controller@gostei'        );
        $router->post('_11150/finalizar'     , 'Admin\_11150Controller@finalizar'     );
        $router->post('_11150/getEnvolvidos' , 'Admin\_11150Controller@getEnvolvidos' );
        $router->post('_11150/rmvEnvolvidos' , 'Admin\_11150Controller@rmvEnvolvidos' );
        $router->post('_11150/grvEnvolvidos' , 'Admin\_11150Controller@grvEnvolvidos' );
        $router->post('_11150/listEnvolvidos', 'Admin\_11150Controller@listEnvolvidos');
          
        $router->get('_11150/{painel_id}/{caso_id}' , 'Admin\_11150Controller@show'   );

	});
