<?php

namespace App\Models\DTO\Admin;

use App\Http\Controllers\Admin\_11000Controller;
use App\Models\DAO\Admin\_11000DAO;

/**
 * 11000 - Gerenciar sistema
 */
class _11000
{
	/**
	 * Retorna as permissões do sistema de acordo com o parâmetro passado.
	 * @param integer $id Id do parâmetro
	 * @return integer
	 */
	public static function controle($id) {
		return _11000Controller::controle($id);
	}
	
	/**
	 * Retorna permissão do sistema de acordo com o parâmetro passado.
	 * @param integer $id Id do parâmetro
	 * @return array
	 */
	public static function permissao($id) {
		return _11000DAO::permissao($id);
	}
}