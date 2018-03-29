<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;


class _11030DAO {

    /**
    * Retorna um perfil (descricao, id, mask)
    */
	public static function getPerfil($id,$tabela) {

        $con = new _Conexao();
        
		$sql = "
            SELECT P.ID,P.DESCRICAO,LPAD(P.ID,2,'0') AS MASK FROM TBPERFIL P WHERE P.ID = :ID AND P.TABELA = :TAB
		";

		$args = array(
			':TAB'	=> $tabela,
			':ID'	=> $id
		);

		return $con->query($sql, $args);
	}

	/**
	 * Consultar perfil por tabela.
	 *
	 * @param object $param
	 * @return array
	 */
	public static function consultarPerfilPorTabela($param) {

		$con = new _Conexao();

        try {
			
			$sql = "
				SELECT DISTINCT
				    TRIM(P.ID) ID,
				    P.DESCRICAO

				FROM
				    TBPERFIL P
				    LEFT JOIN TBGP_UP GU ON GU.GP_ID = :GP_ID
				    LEFT JOIN TBUP U ON U.ID = GU.UP_ID

				WHERE
				    P.TABELA = :TABELA
				AND P.ID = U.PERFIL
			";

			$args = [
				':GP_ID'	=> $param->GP,
				':TABELA' 	=> $param->TABELA
			];

			$perfil = $con->query($sql, $args);

			$con->commit();

			return $perfil;

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

	}

}