<?php

namespace App\Models\DTO\Relatorio;

use App\Models\DAO\Relatorio\_28000DAO;

/**
 * Objeto _28000 - Relatorios Personalizados
 */
class _28000
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _28000DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _28000DAO::listar($dados);
	}

}