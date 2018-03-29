<?php

namespace App\Models\DAO\Helper;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;

class TurnoDAO
{	
	
	/**
	 * Consulta Descrição das impressoras
     * 
     * @return array
	 */
	public static function filtrar($filtro) {
        
        return self::filtrarTurno($filtro);
	}

	public static function filtrarTurno($filtro = false){
		$con = new _Conexao();
		$sql = "SELECT T.CODIGO as ID, LPAD(T.CODIGO, 2, 0) as MASK, T.DESCRICAO FROM TBTURNO T";
		return $con->query($sql);
	}
    
}