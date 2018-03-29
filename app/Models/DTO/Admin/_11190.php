<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11190DAO;

/**
 * Objeto _11190 - Notificacao
 */
class _11190
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11190DAO::getChecList($dados);
	}

	/**
	 * Usuarios
	 */
	public static function getUsuarios($filtro,$con) {
		return _11190DAO::getUsuarios($filtro,$con);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _11190DAO::Consultar($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function getUserNotfi($filtro,$con) {
    	return _11190DAO::getUserNotfi($filtro,$con);
    }

    

}