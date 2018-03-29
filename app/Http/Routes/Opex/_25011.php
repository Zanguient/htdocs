<?php

 /**
  * Rotas do objeto _25011 - Formulários
  * @package Opex
  * @category Rotas
  */

	//Rotas protegidas.
  Route::group(['middleware' => 'auth'], function($router) {
        
      $router->resource('_25011', 'Opex\_25011Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
      $router->post('/_25011/listar', 'Opex\_25011Controller@listar');   //Listar formulários.
      $router->post('/_25011/autenticarColaborador', 'Opex\_25011Controller@autenticarColaborador');   //Autenticar colaborador.
      $router->post('/_25011/gravarResposta', 'Opex\_25011Controller@gravarResposta');   //Gravar resposta.
    
  });
