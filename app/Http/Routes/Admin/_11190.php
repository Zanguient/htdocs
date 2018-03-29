<?php

 /**
  * Rotas do objeto _11190 - Notificacao
  * @package Admin
  * @category Rotas
  */

	//Rotas protegidas.
  Route::group(['middleware' => 'auth'] , function($router) {

    $router->resource('_11190'             , 'Admin\_11190Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
    $router->any('_11190/sendUserNotfi'       , 'Admin\_11190Controller@sendUserNotfi'      );
    $router->any('_11190/updateTela'          , 'Admin\_11190Controller@updateTela'         );
    $router->any('_11190/updateMenu'          , 'Admin\_11190Controller@updateMenu'         );
		$router->POST('_11190/Consultar'          , 'Admin\_11190Controller@Consultar'          );
    $router->POST('_11190/getUsuarios'        , 'Admin\_11190Controller@getUsuarios'        );
    $router->POST('_11190/getNotificacao'     , 'Admin\_11190Controller@getNotificacao'     );
    $router->POST('_11190/agendamento'        , 'Admin\_11190Controller@agendamento'        );
    $router->POST('_11190/gravarLembrete'     , 'Admin\_11190Controller@gravarLembrete'     );
    $router->POST('_11190/getNotifCasos'      , 'Admin\_11190Controller@getNotifCasos'      );
    $router->POST('_11190/excluirLembrete'    , 'Admin\_11190Controller@excluirLembrete'    );
		
	});
