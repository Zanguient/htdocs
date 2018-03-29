<?php

namespace App\Models\DAO\Estoque;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto 15020 - Localização
 */
class _15020DAO {
	
	/**
	 * Listar Localização para cadastro.
	 * 
	 * @return array
	 */
	public static function listarSelect() {
		
		$con = new _Conexao();
			
		$sql = "SELECT
				    LPAD(L.CODIGO, 2, '0') ID,
				    L.DESCRICAO
				FROM
				    TBLOCALIZACAO L
				ORDER BY 1
		";
		
		return $con->query($sql);
		
	}
}
