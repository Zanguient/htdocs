<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22021DAO;

/**
 * Objeto _22021 - Relatório de peças disponíveis para consumo
 */
class _22021
{
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _22021DAO::listar($dados);
	}
	
	public static function relatorioPecaDisponivelTalao($param = []) {
		return _22021DAO::relatorioPecaDisponivelTalao(obj_case($param));
	}

}