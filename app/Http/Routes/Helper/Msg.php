<?php

/*
|
| Rotas com as mensagens de sucesso ao Gravar ou Alterar.
|
*/

	/**
	 * Mensagem de sucesso após gravar.
	 */
	Route::get('/sucessoGravar/{id_obj}/{id?}', function ($id_obj, $id = null) {

		switch ($id_obj) {
			case '_13010':	$msg = 'Requisição de Compra gravada com sucesso.';	break;
			case '_13020':	$msg = 'Orçamento gravado com sucesso.';			break;
			case '_13030':	
                switch ($id) {
                    case 'replicar': $msg = 'Cotas replicadas com sucesso.';	break;
                    default:         $msg = 'Cota gravada com sucesso.';	
                }                
            break;
			case '_13040':	$msg = 'Ordem de Compra gerada com sucesso.';		break;
			case '_15010':	$msg = 'Requisição de Consumo gerada com sucesso.';	break;
			case '_15040':	$msg = 'Baixa de estoque efetuada com sucesso.';	break;
			default:		$msg = 'Gravado com sucesso.';
		}

		Session::flash('flash_message', $msg);

		if(empty($id)) {
			return Redirect::to('/' . $id_obj);
		}
		else {
			return Redirect::to('/' . $id_obj . '/' . $id);
		}
		
	});



	/**
	 * Mensagem de sucesso após alterar.
	 */
	Route::get('/sucessoAlterar/{id_obj}/{id?}', function ($id_obj, $id = null) {

		switch ($id_obj) {
			case '_13010':	$msg = 'Requisição de Compra alterada com sucesso.';	break;
			case '_13020':	$msg = 'Orçamento alterado com sucesso.';				break;
			case '0'	 :	$msg = 'Proposta enviada com sucesso.';					break; //_13021
			case '_13030':	$msg = 'Cota alterada com sucesso.';					break;
			case '_13050':	$msg = 'OC autorizada com sucesso';						break;
			case '_15010':	$msg = 'Requisição de Consumo alterada com sucesso.';	break;
			case '_15040':	$msg = 'Baixa de estoque alterada com sucesso.';		break;
			default:		$msg = 'Alterado com sucesso.';
		}

		Session::flash('flash_message', $msg);

		if(empty($id)) {
			return Redirect::to('/' . $id_obj);
		}
		else {
			return Redirect::to('/' . $id_obj . '/' . $id);
		}
		
	});