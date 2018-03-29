<?php

/**
 * Rotas do objeto 25200
 * @package Engenharia
 * @category Rotas
 */

//Rotas protegidas.
Route::group(['middleware' => 'auth'], function($router) {

    $router->post('/_25200/filtrar', 'Opex\_25200Controller@filtrar');
    
});

