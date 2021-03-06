<?php

namespace App\Models\DAO\#Grupos#;

/**
 * DAO do objeto #TelaNO# - #Titulo#
 */
class #TelaNO#DAO {
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        
        $sql = "
            SELECT 'TELA 100% FUNCIONAL' FRASE 
            FROM RDB\$DATABASE 
            WHERE 0 = :ID
        ";

        $args = [
            ':ID' => $param->ID
        ];

        return $con->query($sql, $args);
    }
	
}