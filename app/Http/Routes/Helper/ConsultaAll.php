<?php

/**
 * Rotas do objeto Turno
 * @package Engenharia
 * @category Rotas
 */

//Rotas protegidas.
Route::group(['middleware' => 'auth'], function($router) {

    $router->post('/consultaAll', 'Helper\ConsultaAllControler@consulta');
    $router->post('/consultaMais', 'Helper\ConsultaAllControler@consultaMais');
    $router->post('/consultaMaisAll', 'Helper\ConsultaAllControler@consultaMaisAll');
    
    $router->get('/gc-search', function()
    {
        return view('helper.include.view.gc-search');
    });
    
});

