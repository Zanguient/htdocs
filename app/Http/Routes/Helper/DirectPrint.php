<?php
  
use App\Models\DTO\Opex\Helper;

 /**
  * Impresão Direta
  * @package Opex
  * @category Rotas
  */
    
    //Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {
        
        $router->post('/print/postprint', 'Helper\DirectPrintController@postprint');
        $router->post('/print/get/{tag}', 'Helper\DirectPrintController@getprint');
    
        $router->get('/print/getprint', 'Helper\DirectPrintController@getprint2');
         
    });