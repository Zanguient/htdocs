<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto 11020 - Estabelecimento
 */
class _11020DAO {
	
	/**
	 * Listar estabelecimentos permitidos ao usuário.
	 * 
	 * @return array
	 */
	public static function listarSelect() {
		
		$con = new _Conexao();
			
		$sql = "
            SELECT
				LPAD(E.CODIGO, 2, '0') ID,
				E.NOMEFANTASIA
			FROM
				TBESTABELECIMENTO E
			WHERE
				E.CODIGO in(
					select osplit from split(
						(SELECT LIST(ESTABELECIMENTO_CODIGO)
							 FROM TBUSUARIO_ESTABELECIMENTO U
							 WHERE U.USUARIO_CODIGO = :USU_ID)
						 , ','
					)
				)
			ORDER BY 1
		";
		
		$args = array(
			':USU_ID' => Auth::user()->CODIGO
		);
				
		return $con->query($sql, $args);
		
	}
	
	/**
	 * Listar estabelecimentos.
	 * 
	 * @return array
	 */
	public static function listarTodos() {
		
		$con = new _Conexao();
			
		$sql = "
            SELECT
				LPAD(E.CODIGO, 2, '0') ID,
				E.NOMEFANTASIA
			FROM
				TBESTABELECIMENTO E
			ORDER BY 1
		";
				
		return $con->query($sql);
		
	}
}
