<?php

/**
 * Rotas do objeto Chat.
 * @package Helper
 * @category Rotas
 */

//Rotas protegidas.
Route::group(['middleware' => 'auth'], function($router) {

    $router->get('/chat/viewIndex', 'Helper\ChatController@viewIndex');
    $router->post('/chat/gravar', 'Helper\ChatController@gravar');
    $router->post('/chat/consultarHistoricoConversa', 'Helper\ChatController@consultarHistoricoConversa');
    
});