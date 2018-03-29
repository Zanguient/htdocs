<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12060DAO;

/**
 * Objeto _12060 - Representante
 */
class _12060
{

	/**
	 * Consultar representante.
	 * @param _Conexao $con
	 * @return json
	 */
	public static function consultarRepresentante($con) {
		return _12060DAO::consultarRepresentante($con);
	}

}