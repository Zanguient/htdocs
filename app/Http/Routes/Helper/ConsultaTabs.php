<?php

/**
  * Rotas do metodo que cpmta uma consulta apartir de parametros (tabulado)
  * 
  * @package Rolter
  * @deprecated 
  */
Route::group(['middleware' => 'auth'], function($router) {

    $router->post('/consultaAllTab'        , 'Helper\ConsultaTabsControler@consulta'        );
    $router->post('/consultaMaisTab'       , 'Helper\ConsultaTabsControler@consultaMais'    );
    $router->post('/consultaMaisAllTab'    , 'Helper\ConsultaTabsControler@consultaMaisAll' );
    
});

