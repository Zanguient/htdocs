<?php

namespace App\Models\DTO\Fiscal;

use App\Models\DAO\Fiscal\_21010DAO;

/**
 * Objeto 21010 - Operação
 */
class _21010
{

	/**
	 * Pesquisar registro (Requisição via Ajax)
	 * @param string $filtro | alfanumérico
	 * @return array
	 */
	public static function pesquisa($produto_id, $data, $filtro) {
		return _21010DAO::pesquisa($produto_id, $data, $filtro);
	}
}