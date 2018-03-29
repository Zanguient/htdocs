<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11100DAO;

/**
 * Objeto _11100 - Qlik Sense
 */
class _11100
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11100DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11100DAO::listar($dados);
	}

	/**
	 * Listar
	 */
	public static function getProjetos($dados,$con) {
		return _11100DAO::getProjetos($dados,$con);
	}

}