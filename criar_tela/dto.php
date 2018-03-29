<?php

namespace App\Models\DTO\#Grupos#;

use App\Models\DAO\#Grupos#\#TelaNO#DAO;

/**
 * Objeto #TelaNO# - #Titulo#
 */
class #TelaNO#
{
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
    	return #TelaNO#DAO::consultar($param, $con);
    }

}