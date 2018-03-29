<?php
  
use App\Models\DTO\Opex\_25700;

 /**
  * Rotas do objeto 25700
  * @package Opex
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('/_25700/planoacao', 'Opex\_25700Controller@listar');
        $router->post('/_25700/incluir', 'Opex\_25700Controller@getTela');
        $router->post('/_25700/store', 'Opex\_25700Controller@store');
        $router->post('/_25700/showitem', 'Opex\_25700Controller@showitem');
        $router->post('/_25700/excluir', 'Opex\_25700Controller@excluir');
        $router->post('/_25700/alteritem', 'Opex\_25700Controller@alteritem');
        $router->post('/_25700/alterar', 'Opex\_25700Controller@alterar');
        
        $router->get('/_25700/', 'Opex\_25700Controller@show');
        
        $router->post('/_25700/prodgp', 'Opex\_25700Controller@prodgp');
         
    });
    
    ///////////////////////////////- inv tecido -/////////////////////////////////
    //pendente normal
    $router->get('/INVTECIDO/1/4/1', 'Opex\_25700Controller@show1');
    //coletado normal
    $router->get('/INVTECIDO/1/4/2', 'Opex\_25700Controller@show2');
    
    //pendente defeito
    $router->get('/INVTECIDO/2/4/1', 'Opex\_25700Controller@show3');
    //coletado defeito
    $router->get('/INVTECIDO/2/4/2', 'Opex\_25700Controller@show4');
    //////////////////////////////////////////////////////////////////////////////