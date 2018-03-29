<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15050DAO;

/**
 * Objeto 15050 - Baixa de Estoque
 */
class _15050
{

	/**
	 * Select da página inicial.
	 *
	 * @return array
	 */
	public static function listar() {
		return _15050DAO::listar();
	}
	
}