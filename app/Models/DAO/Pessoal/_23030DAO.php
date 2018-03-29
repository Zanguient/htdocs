<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23030 - Cadastro de níveis dos fatores para avaliação de desempenho.
 */
class _23030DAO {
	
	/**
	 * Consultar níveis.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarNivel($con) {
		
		$sql = "
			SELECT 
				N.ID,
				N.TITULO,
				N.FAIXA_INICIAL,
				N.FAIXA_FINAL

			FROM
				TBAVALIACAO_DES_NIVEL N

			WHERE
				N.STATUSEXCLUSAO = '0'
		";

		return $con->query($sql);
	}
	
	/**
	 * Gravar nível.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravar($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_NIVEL (
				ID,
				TITULO,
				FAIXA_INICIAL,
				FAIXA_FINAL
			)
			VALUES (
				:ID,
				:TITULO,
				:FAIXA_INICIAL,
				:FAIXA_FINAL
			)
			MATCHING (ID)
		";

		$args = [
			':ID'            => $param->ID,
			':TITULO'        => $param->TITULO,
			':FAIXA_INICIAL' => $param->FAIXA_INICIAL,
			':FAIXA_FINAL'   => $param->FAIXA_FINAL
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir nível.
	 *
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 */
	public static function excluir($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_NIVEL
			SET STATUSEXCLUSAO = '1'
			WHERE ID = :ID
		";

		$args = [
			':ID' => $param->ID
		];

		$con->execute($sql, $args);
	}
}