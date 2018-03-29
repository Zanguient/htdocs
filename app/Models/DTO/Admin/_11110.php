<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11110DAO;

/**
 * Objeto _11110 - Gerenciar Qlik Sense
 */
class _11110
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11110DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11110DAO::listar($dados);
	}

	/**
	 * Listar Usuários
	 */
	public static function listUser() {
		return _11110DAO::listUser();
	}

}