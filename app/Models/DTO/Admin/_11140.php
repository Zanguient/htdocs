<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11140DAO;

/**
 * Objeto _11140 - Painel de Casos
 */
class _11140
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11140DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11140DAO::listar($dados);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _11140DAO::Consultar($filtro,$con);
    }

    public static function getClientes($filtro) {
    	return _11140DAO::getClientes($filtro);
    }
}