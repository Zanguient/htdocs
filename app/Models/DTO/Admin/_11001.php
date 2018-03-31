<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11001DAO;

/**
 * Objeto _11001 - Usuarios
 */
class _11001
{
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
    	return _11001DAO::consultar($param, $con);
    }

}