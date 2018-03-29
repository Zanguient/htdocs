<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22110DAO;

/**
 * Objeto _22110 - Registro de Agrupamento de Pedidos e Reposicoes
 */
class _22110
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _22110DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _22110DAO::listar($dados);
	}
	
	/**
	 * Listar
	 */
	public static function selectAgrupamento($dados) {
		return _22110DAO::selectAgrupamento(obj_case($dados));
	}

}