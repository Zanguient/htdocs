<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11040DAO;

/**
 * 11000 - Painel ADM
 */
class _11040
{
	/**
	 * • 
	 */
	public static function getChecList($dados) {
		return _11040DAO::getChecList($dados);
	}

}