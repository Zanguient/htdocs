<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23032 - Cadastro de fatores para avaliação de desempenho.
 */
class _23032DAO {
	
	/**
	 * Consultar fatores.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarFator($con) {
		
		$sql = "
			SELECT 
				F.ID,
				F.TITULO,
				F.DESCRICAO,
				F.AVALIACAO_DES_FATOR_TIPO_ID TIPO_ID,
				TRIM(F.ORDEM_PERC_NIVEL) ORDEM_PERC_NIVEL,

				(SELECT T.TITULO 
					FROM TBAVALIACAO_DES_FATOR_TIPO T 
					WHERE 
						T.STATUSEXCLUSAO = '0'
					AND T.ID = F.AVALIACAO_DES_FATOR_TIPO_ID)
				TIPO_TITULO

			FROM
				TBAVALIACAO_DES_FATOR F

			WHERE
				F.STATUSEXCLUSAO = '0'
		";

		return $con->query($sql);
	}

	/**
	 * Consultar descritivos dos níveis de fatores.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarFatorNivelDescritivo($param, $con) {
		
		$sql = "
			SELECT 
				N.ID,
				N.AVALIACAO_DES_NIVEL_ID NIVEL_ID,
				N.DESCRICAO,
				N.FAIXA_INICIAL,
				N.FAIXA_FINAL

			FROM
				TBAVALIACAO_DES_FATOR_NIVEL N

			WHERE
				N.STATUSEXCLUSAO = '0'
			AND N.AVALIACAO_DES_FATOR_ID = :FATOR_ID
		";

		$args = [
			':FATOR_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}
	
	/**
	 * Gerar id do fator.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdFator($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_FATOR, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}

	/**
	 * Gravar fator.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravarFator($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_FATOR (
				ID,
				TITULO,
				DESCRICAO,
				AVALIACAO_DES_FATOR_TIPO_ID,
				ORDEM_PERC_NIVEL
			)
			VALUES (
				:ID,
				:TITULO,
				:DESCRICAO,
				:AVALIACAO_DES_FATOR_TIPO_ID,
				:ORDEM_PERC_NIVEL
			)
			MATCHING (ID)
		";

		$args = [
			':ID'                           => $param->ID,
			':TITULO'                       => $param->TITULO,
			':DESCRICAO'                    => $param->DESCRICAO,
			':AVALIACAO_DES_FATOR_TIPO_ID'  => $param->TIPO_ID,
			':ORDEM_PERC_NIVEL' 			=> $param->ORDEM_PERC_NIVEL
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar descritivos dos níveis de fatores.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravarFatorNivelDescritivo($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_FATOR_NIVEL (
				ID,
				AVALIACAO_DES_FATOR_ID,
				AVALIACAO_DES_NIVEL_ID,
				DESCRICAO,
				FAIXA_INICIAL,
				FAIXA_FINAL,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_FATOR_ID,
				:AVALIACAO_DES_NIVEL_ID,
				:DESCRICAO,
				:FAIXA_INICIAL,
				:FAIXA_FINAL,
				:STATUSEXCLUSAO
			)
			MATCHING (ID)
		";

		$args = [
			':ID'                       => $param->ID,
			':AVALIACAO_DES_FATOR_ID'   => $param->FATOR_ID,
			':AVALIACAO_DES_NIVEL_ID'   => $param->NIVEL_ID,
			':DESCRICAO'          		=> $param->DESCRICAO,
			':FAIXA_INICIAL' 			=> $param->FAIXA_INICIAL,
			':FAIXA_FINAL' 				=> $param->FAIXA_FINAL,
			':STATUSEXCLUSAO'           => $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir fator.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function excluirFator($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_FATOR
			SET STATUSEXCLUSAO = '1'
			WHERE ID = :ID
		";

		$args = [
			':ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir descritivos dos níveis de fatores.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function excluirFatorNivelDescritivo($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_FATOR_NIVEL
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_FATOR_ID = :FATOR_ID
		";

		$args = [
			':FATOR_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}
	
}