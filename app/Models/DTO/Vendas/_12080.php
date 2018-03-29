<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12080DAO;

/**
 * Objeto _12080 - REGISTRO DE CASOS
 */
class _12080
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _12080DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _12080DAO::listar($dados);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _12080DAO::Consultar($filtro,$con);
    }

}