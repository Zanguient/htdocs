<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11180DAO;

/**
 * Objeto _11180 - Blok
 */
class _11180
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11180DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11180DAO::listar($dados);
	}

	/**
	 * url
	 */
	public static function url($filtro,$con) {
		return _11180DAO::url($filtro,$con);
	}

	/**
	 * excluir
	 */
	public static function excluir($filtro,$con) {
		return _11180DAO::excluir($filtro,$con);
	}

	/**
	 * gravar
	 */
	public static function gravar($filtro,$con) {
		return _11180DAO::gravar($filtro,$con);
	}

	/**
	 * janela
	 */
	public static function janela($filtro,$con) {
		return _11180DAO::janela($filtro,$con);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _11180DAO::Consultar($filtro,$con);
    }

}