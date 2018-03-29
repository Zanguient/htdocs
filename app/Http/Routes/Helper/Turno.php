<?php

/**
 * Rotas do objeto Turno
 * @package Engenharia
 * @category Rotas
 */

//Rotas protegidas.
Route::group(['middleware' => 'auth'], function($router) {

    $router->post('/turno/filtrar', 'Helper\TurnoController@filtrar');
    
});

