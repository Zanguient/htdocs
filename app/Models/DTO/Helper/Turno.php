<?php

namespace App\Models\DTO\Helper;

use App\Models\DAO\Helper\TurnoDAO;

class Turno
{

	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtrar($filtro) {
		return TurnoDAO::filtrar($filtro);
	}
	
}
