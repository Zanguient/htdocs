<?php

 /**
  * Rotas do objeto _25010 - Cadastro de Formulários
  * @package Opex
  * @category Rotas
  */

	//Rotas protegidas.
    Route::group(['middleware' => 'auth'], function($router) {

        $router->resource('_25010', 'Opex\_25010Controller'); //Rotas nativas | INDEX | CREATE | STORE | SHOW | EDIT | UPDATE | DESTROY
        $router->post('/_25010/listar', 'Opex\_25010Controller@listar');   //Listar formulários.
        $router->post('/_25010/listarTipoFormulario', 'Opex\_25010Controller@listarTipoFormulario');   //Listar tipos de formulário.
        $router->post('/_25010/listarTipoResposta', 'Opex\_25010Controller@listarTipoResposta');   //Listar tipos de resposta.
        $router->post('/_25010/listarNivelSatisfacao', 'Opex\_25010Controller@listarNivelSatisfacao');   //Listar níveis de satisfação.
        $router->post('/_25010/listarUsuario', 'Opex\_25010Controller@listarUsuario');   //Listar usuários.
        $router->post('/_25010/listarPainel', 'Opex\_25010Controller@listarPainel');   //Listar painel.
        $router->post('/_25010/listarPainelCliente', 'Opex\_25010Controller@listarPainelCliente');   //Listar painel para a pesquisa de clientes.
        $router->post('/_25010/consultarUF', 'Opex\_25010Controller@consultarUF');   //Consultar UF's
        $router->post('/_25010/painelResposta', 'Opex\_25010Controller@painelResposta');   //Respostas por usuário para o painel.
        $router->post('/_25010/gravar', 'Opex\_25010Controller@gravar');    //Gravar Formulário.
        $router->post('/_25010/alterar', 'Opex\_25010Controller@alterar');		//Alterar Formulário.
        $router->post('/_25010/excluirFormulario', 'Opex\_25010Controller@excluirFormulario');   //Excluir formulários.

        $router->post('/_25010/csv', 'Opex\_25010Controller@csv');
	});
