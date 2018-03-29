<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11070DAO;

/**
 * Objeto _11070 - Tela de Teste
 */
class _11070
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11070DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11070DAO::listar($dados);
	}

}