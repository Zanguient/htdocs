<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;

class _11000DAO {

    /**
     * Retorna as permissões do sistema de acordo com o parâmetro passado.
     * @param integer Id do parâmetro
     * @return array
     */
    public static function permissao($id) {
		
		$con = new _Conexao();
		
		$sql = "
			SELECT
				N.ID,
				N.VALOR_EXT
			FROM
				TBCONTROLE_N N
			WHERE
				N.ID = :ID
		";	

		$args = [
			':ID' => $id
		];
		
		return $con->query($sql, $args);
    }

}