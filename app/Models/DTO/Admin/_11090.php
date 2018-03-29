<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11090DAO;

/**
 * Objeto _11090 - Teste
 */
class _11090
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11090DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11090DAO::listar($dados);
	}

}