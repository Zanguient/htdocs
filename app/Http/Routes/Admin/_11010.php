<?php

 /**
  * Rotas do objeto _11010
  * @package Admin
  * @category Rotas
  */

  //Rotas protegidas.
  Route::group(['middleware' => 'auth'], function($router) {

    $router->post('_11010/index'    , 'Admin\_11010Controller@indexBody');
    $router->post('_11010/show/{id}', 'Admin\_11010Controller@showBody' );
      
    $router->post('_11010/ResetarPass'      , 'Admin\_11010Controller@ResetarPass'           );
    $router->post('_11010/getMenusUser'     , 'Admin\_11010Controller@getMenusUser'          );
    $router->post('_11010/setMenusUser'     , 'Admin\_11010Controller@setMenusUser'          );
    $router->post('_11010/getCcustoUser'    , 'Admin\_11010Controller@getCcustoUser'         );
    $router->post('_11010/getPermicoesUser' , 'Admin\_11010Controller@getPermicoesUser'      );
    $router->post('_11010/getPerfilUser'    , 'Admin\_11010Controller@getPerfilUser'         );
    $router->post('_11010/getPerfil'        , 'Admin\_11010Controller@getPerfil'             );
    $router->post('_11010/setPerfilUser'    , 'Admin\_11010Controller@setPerfilUser'         );
    $router->post('_11010/setRelatorioUser' , 'Admin\_11010Controller@setRelatorioUser'      );
    $router->post('_11010/listarTodos'      , 'Admin\_11010Controller@listarTodos'           );
    $router->post('_11010/getMenus'         , 'Admin\_11010Controller@getMenus'              );
    $router->post('_11010/getRelatorios'    , 'Admin\_11010Controller@getRelatorios'         );
    $router->post('_11010/getRelatorioUser' , 'Admin\_11010Controller@getRelatorioUser'      );

    $router->post('_11010/loginUser'        , 'Admin\_11010Controller@loginUser'             );
    $router->post('_11010/voltarUser'       , 'Admin\_11010Controller@voltarUser'            );
    

    $router->post('_11010/CriarUsuarioDB'   , 'Admin\_11010Controller@CriarUsuarioDB'        );

      
    $router->resource('_11010', 'Admin\_11010Controller');

  });

