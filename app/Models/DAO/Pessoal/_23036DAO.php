<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23036 - Cadastro de avaliação de desempenho.
 */
class _23036DAO {
	
	/**
	 * Consultar base para avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarBaseAvaliacao($param, $con) {
		
		$sql = "
			SELECT
				B.ID,
				B.AVALIACAO_DES_MODELO_ID,
				B.TITULO,
				B.INSTRUCAO_INICIAL,
				B.META_MEDIA_GERAL,
				B.DATA_AVALIACAO,
				TRIM(B.STATUS) STATUS,
				TRIM(B.RESPONDIDA) RESPONDIDA

			FROM 
				TBAVALIACAO_DES_RESP_BASE B

			WHERE
				B.STATUSEXCLUSAO = '0'
			AND IIF(:STATUS_0 IS NULL, TRUE, B.STATUS = :STATUS_1)
            AND B.DATA_AVALIACAO BETWEEN :DATA_INI AND :DATA_FIM
		";

		$args = [
			':STATUS_0' => $param->STATUS,
			':STATUS_1' => $param->STATUS,
			':DATA_INI' => $param->DATA_INI,
			':DATA_FIM' => $param->DATA_FIM
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar centros de custo da base para avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarBaseCCustoAvaliacao($param, $con) {
		
		$sql = "
			SELECT
				BC.ID,
				BC.AVALIACAO_DES_RESP_BASE_ID,
				BC.CCUSTO_CODIGO CODIGO,
                FN_CCUSTO_MASK(BC.CCUSTO_CODIGO) MASK,
                FN_CCUSTO_DESCRICAO(BC.CCUSTO_CODIGO) DESCRICAO

			FROM 
				TBAVALIACAO_DES_RESP_BASE_CCUST BC

			WHERE
				BC.STATUSEXCLUSAO = '0'
			AND BC.AVALIACAO_DES_RESP_BASE_ID = :BASE_ID
		";

		$args = [
			':BASE_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

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
				M.META_MEDIA_GERAL,
				1 DO_MODELO

			FROM
				TBAVALIACAO_DES_MODELO M

			WHERE
				M.STATUSEXCLUSAO = '0'
		";

		return $con->query($sql);
	}

	/**
	 * Gerar id da base da avaliação.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdBase($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_RESP_BASE, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}	

	/**
	 * Gravar base da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarBaseAvaliacao($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESP_BASE (
				ID,
				AVALIACAO_DES_MODELO_ID,
				TITULO,
				INSTRUCAO_INICIAL,
				META_MEDIA_GERAL,
				DATA_AVALIACAO,
				STATUS
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_MODELO_ID,
				:TITULO,
				:INSTRUCAO_INICIAL,
				:META_MEDIA_GERAL,
				:DATA_AVALIACAO,
				:STATUS
			)
		";

		$args = [
			':ID' 						=> $param->ID,
			':AVALIACAO_DES_MODELO_ID' 	=> $param->MODELO->ID,
			':TITULO'					=> $param->TITULO,
			':INSTRUCAO_INICIAL'		=> $param->INSTRUCAO_INICIAL,
			':META_MEDIA_GERAL'			=> $param->META_MEDIA_GERAL,
			':DATA_AVALIACAO'			=> $param->DATA_AVALIACAO,
			':STATUS'					=> $param->STATUS
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar centros de custo da base da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarBaseCCustoAvaliacao($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESP_BASE_CCUST (
				ID,
				AVALIACAO_DES_RESP_BASE_ID,
				CCUSTO_CODIGO,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESP_BASE_ID,
				:CCUSTO_CODIGO,
				:STATUSEXCLUSAO
			)
		";

		$args = [
			':ID' 							=> $param->ID,
			':AVALIACAO_DES_RESP_BASE_ID' 	=> $param->AVALIACAO_DES_RESP_BASE_ID,
			':CCUSTO_CODIGO'				=> $param->CODIGO,
			':STATUSEXCLUSAO'				=> $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	

	/**
	 * Excluir base de avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirBaseAvaliacao($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESP_BASE
			SET STATUSEXCLUSAO = '1'
			WHERE ID = :ID
		";

		$args = [
			':ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir centros de custo da base de avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirBaseCCustoAvaliacao($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESP_BASE_CCUST
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_RESP_BASE_ID = :BASE_ID
		";

		$args = [
			':BASE_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

}