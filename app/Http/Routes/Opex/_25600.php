<?php

/**
 * Rotas do objeto 18800 (BSC-TV)
 * @package Engenharia
 * @category Rotas
 */
//Rotas protegidas.
Route::group(['middleware' => 'auth'], function($router) {
    
    //$router->resource('_25600', 'Compras\_25600Controller');
    
    $router->get('/_25600', 'Opex\_25600Controller@show');
    $router->get('/_25600/create', 'Opex\_25600Controller@create');
    $router->get('/_25600/store', 'Opex\_25600Controller@store');
    $router->post('/_25600/filtrar', 'Opex\_25600Controller@consultaAlditorias');
    $router->post('/_25600/consultarRegistro', 'Opex\_25600Controller@consultaAlditoria');
    $router->post('/_25600/alterarNota', 'Opex\_25600Controller@alterarNota');
    $router->post('/_25600/listaFaixas', 'Opex\_25600Controller@listaFaixas');
    $router->post('/_25600/descfaixa', 'Opex\_25600Controller@consultaDescricaoFaixa');
    $router->post('/_25600/descfaixas', 'Opex\_25600Controller@consultaDescricaoFaixas');
    
    $router->post('/_25600/store', 'Opex\_25600Controller@store');
    $router->post('/_25600/sucessoGravar', 'Opex\_25600Controller@sucessoGravar');
    
    
    
});

