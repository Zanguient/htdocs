<?php

namespace App\Models\DTO\Opex;

use App\Models\DAO\Opex\_25200DAO;

/**
 * Indicadores
 */
class _25200
{

	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtrar($filtro) {
		return _25200DAO::filtrar($filtro);
	}
    
    /**
	 * Filtrar lista um indicador peplo controle N
	 *
	 * @param int $id
	 * @return array
	 */
	public static function indicadorcontrole($id) {
		return _25200DAO::indicadorcontrole($id);
	}
	
}
