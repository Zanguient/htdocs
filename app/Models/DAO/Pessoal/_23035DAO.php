<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23035 - Cadastro de modelo de avaliação de desempenho.
 */
class _23035DAO {
	
	/**
	 * Consultar modelo.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModelo($con) {
		
		$sql = "
			SELECT
				M.ID,
				M.TITULO,
				M.INSTRUCAO_INICIAL,
				M.META_MEDIA_GERAL

			FROM
				TBAVALIACAO_DES_MODELO M

			WHERE
				M.STATUSEXCLUSAO = '0'
		";

		return $con->query($sql);
	}

	/**
	 * Consultar fatores do modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloFator($param, $con) {
		
		$sql = "
			SELECT
				MF.ID,
				MF.AVALIACAO_DES_FATOR_ID,
				F.TITULO,
				F.DESCRICAO,

				(SELECT FIRST 1 FT.TITULO
					FROM TBAVALIACAO_DES_FATOR_TIPO FT
					WHERE FT.ID = F.AVALIACAO_DES_FATOR_TIPO_ID)
				TIPO_TITULO

			FROM
				TBAVALIACAO_DES_MOD_FATOR MF
				LEFT JOIN TBAVALIACAO_DES_FATOR F
					ON  F.ID = MF.AVALIACAO_DES_FATOR_ID
					AND F.STATUSEXCLUSAO = '0'

			WHERE
				MF.STATUSEXCLUSAO = '0'
			AND MF.AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar formações do modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloFormacao($param, $con) {
		
		$sql = "
			SELECT
				MF.ID,
				MF.AVALIACAO_DES_FORMACAO_ID,

				(SELECT FIRST 1 F.PONTO
					FROM TBAVALIACAO_DES_FORMACAO F
					WHERE F.ID = MF.AVALIACAO_DES_FORMACAO_ID)
				PONTO

			FROM
				TBAVALIACAO_DES_MOD_FORMACAO MF

			WHERE
				MF.STATUSEXCLUSAO = '0'
			AND MF.AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar resumo do modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloResumo($param, $con) {
		
		$sql = "
			SELECT
				MR.ID,
				MR.AVALIACAO_DES_RESUMO_ID,

				(SELECT FIRST 1 R.PESO
                    FROM TBAVALIACAO_DES_RESUMO R
                    WHERE R.ID = MR.AVALIACAO_DES_RESUMO_ID)
                PESO

			FROM
				TBAVALIACAO_DES_MOD_RESUMO MR

			WHERE
				MR.STATUSEXCLUSAO = '0'
			AND MR.AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Gerar id do modelo.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdModelo($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_MODELO, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}
	
	/**
	 * Gravar modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravarModelo($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_MODELO (
				ID,
				TITULO,
				INSTRUCAO_INICIAL,
				META_MEDIA_GERAL
			)
			VALUES (
				:ID,
				:TITULO,
				:INSTRUCAO_INICIAL,
				:META_MEDIA_GERAL
			)
			MATCHING (ID)
		";

		$args = [
			':ID'                   => $param->ID,
			':TITULO'               => $param->TITULO,
			':INSTRUCAO_INICIAL'    => $param->INSTRUCAO_INICIAL,
			':META_MEDIA_GERAL'     => $param->META_MEDIA_GERAL
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar fator do modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravarModeloFator($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_MOD_FATOR (
				ID,
				AVALIACAO_DES_MODELO_ID,
				AVALIACAO_DES_FATOR_ID,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_MODELO_ID,
				:AVALIACAO_DES_FATOR_ID,
				:STATUSEXCLUSAO
			)
			MATCHING (ID)
		";

		$args = [
			':ID'                       => $param->ID,
			':AVALIACAO_DES_MODELO_ID'  => $param->AVALIACAO_DES_MODELO_ID,
			':AVALIACAO_DES_FATOR_ID'   => $param->AVALIACAO_DES_FATOR_ID,
			':STATUSEXCLUSAO'			=> $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar formação do modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravarModeloFormacao($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_MOD_FORMACAO (
				ID,
				AVALIACAO_DES_MODELO_ID,
				AVALIACAO_DES_FORMACAO_ID,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_MODELO_ID,
				:AVALIACAO_DES_FORMACAO_ID,
				:STATUSEXCLUSAO
			)
			MATCHING (ID)
		";

		$args = [
			':ID'                       => $param->ID,
			':AVALIACAO_DES_MODELO_ID'  => $param->AVALIACAO_DES_MODELO_ID,
			':AVALIACAO_DES_FORMACAO_ID'=> $param->AVALIACAO_DES_FORMACAO_ID,
			':STATUSEXCLUSAO'			=> $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar resumo do modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravarModeloResumo($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_MOD_RESUMO (
				ID,
				AVALIACAO_DES_MODELO_ID,
				AVALIACAO_DES_RESUMO_ID,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_MODELO_ID,
				:AVALIACAO_DES_RESUMO_ID,
				:STATUSEXCLUSAO
			)
			MATCHING (ID)
		";

		$args = [
			':ID'                       => $param->ID,
			':AVALIACAO_DES_MODELO_ID'  => $param->AVALIACAO_DES_MODELO_ID,
			':AVALIACAO_DES_RESUMO_ID'	=> $param->AVALIACAO_DES_RESUMO_ID,
			':STATUSEXCLUSAO'			=> $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir modelo.
	 *
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 */
	public static function excluirModelo($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_MODELO
			SET STATUSEXCLUSAO = '1'
			WHERE ID = :ID
		";

		$args = [
			':ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir fatores do modelo.
	 *
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 */
	public static function excluirModeloFator($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_MOD_FATOR
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir formações do modelo.
	 *
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 */
	public static function excluirModeloFormacao($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_MOD_FORMACAO
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir resumo do modelo.
	 *
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 */
	public static function excluirModeloResumo($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_MOD_RESUMO
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}
	
}