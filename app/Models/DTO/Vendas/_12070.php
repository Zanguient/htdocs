<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12070DAO;

/**
 * Objeto _12070 - Clientes
 */
class _12070
{
	/**
	 * Consultar cliente por representante.
	 * @param json $param
	 * @param _Conexao $con
	 * @return json
	 */
	public static function consultarClientePorRepresentante($param, $con) {
		return _12070DAO::consultarClientePorRepresentante($param, $con);
	}
}