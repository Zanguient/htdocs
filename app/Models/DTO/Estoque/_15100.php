<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15100DAO;

/**
 * Objeto _15100 - Abastecer estoque
 */
class _15100
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _15100DAO::getChecList($dados);
	}

	/**
	 * Consulta uma UP 
	 */
	public static function ConsultarUP($paran) {
		return _15100DAO::ConsultarUP($paran);
	}

	/**
	 * Consulta um Operador
	 */
	public static function ConsultarOperador($paran) {
		return _15100DAO::ConsultarOperador($paran);
	}

	/**
	 * Consulta um Peça
	 */
	public static function ConsultarPeca($paran) {
		return _15100DAO::ConsultarPeca($paran);
	}

	/**
	 * Consulta um Peça
	 */
	public static function Abastercer($paran) {
		return _15100DAO::Abastercer($paran);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _15100DAO::listar($dados);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _15100DAO::Consultar($filtro,$con);
    }

}