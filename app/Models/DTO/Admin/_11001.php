<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11001DAO;

/**
 * Objeto _11001 - Agendador
 */
class _11001
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11001DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11001DAO::listar($dados);
	}

}