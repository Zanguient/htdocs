<?php

namespace App\Models\DAO\Pessoal;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto 23010 - Turno
 */
class _23010DAO {
	
	/**
	 * Listar Turnos para cadastro.
	 * 
	 * @return array
	 */
	public static function listarSelect($param = []) {
		
		$con = new _Conexao();
			
        $first          = array_key_exists('FIRST'         , $param) ? "FIRST " . $param->FIRST : '';
        $skip           = array_key_exists('SKIP'          , $param) ? "SKIP  " . $param->SKIP  : '';
        $turno_corrente = array_key_exists('TURNO_CORRENTE', $param) ? "AND TURNO_CORRENTE IN (" . arrayToList($param->TURNO_CORRENTE, "'#'","'") . ")" : '';
        
		$sql = "
            SELECT /*@FIRST*/ /*@SKIP*/
                LPAD(T.CODIGO, 2, '0') ID,
                T.DESCRICAO,
                T.TURNO_CORRENTE AS FLAG,
                T.DATA_PRODUCAO,
				T.HORA_INICIO,
				T.HORA_FIM
            FROM
                TBTURNO T
            WHERE
                1=1
            /*@TURNO_CORRENTE*/

            ORDER BY 1
		";
		
        $args = [
            '@FIRST'          => $first,
            '@SKIP'           => $skip,
            '@TURNO_CORRENTE' => $turno_corrente
        ];
        
		return $con->query($sql,$args);
		
	}
}
