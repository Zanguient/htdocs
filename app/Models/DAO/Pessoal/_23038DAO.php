<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23038 - Registro de indicadores por centro de custo.
 */
class _23038DAO {
	
	/**
	 * Consultar indicadores por centro de custo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarIndicadorPorCCusto($param, $con) {
		
		$sql = "
			SELECT 
                I.ID,
                I.INDICADOR_ID,

                (SELECT FIRST 1 F.TITULO
                	FROM TBAVALIACAO_DES_FATOR F
                	WHERE F.STATUSEXCLUSAO = '0'
                	AND F.ID = I.INDICADOR_ID)
                INDICADOR_TITULO,

                I.CCUSTO_CODIGO,
                FN_CCUSTO_MASK(I.CCUSTO_CODIGO) CCUSTO_MASK,
                FN_CCUSTO_DESCRICAO(I.CCUSTO_CODIGO) CCUSTO_DESCRICAO,

                I.PERC_INDICADOR,
                I.DATA_INI,
                I.DATA_FIM

            FROM
                TBINDICADOR_CCUSTO I

            WHERE
                I.STATUSEXCLUSAO = '0'
            AND IIF(:DATA_INI_0 IS NULL, TRUE, I.DATA_INI >= :DATA_INI_1 AND I.DATA_FIM <= :DATA_FIM)
		";

		$args = [
			':DATA_INI_0' => $param->DATA_INI,
			':DATA_INI_1' => $param->DATA_INI,
			':DATA_FIM'   => $param->DATA_FIM
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar indicadores.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarIndicador($con) {
		
		$sql = "
			SELECT
			    F.ID,
			    F.TITULO,
			    F.DESCRICAO

			FROM
			    TBAVALIACAO_DES_FATOR F

			WHERE
				F.STATUSEXCLUSAO = '0'
			AND F.AVALIACAO_DES_FATOR_TIPO_ID = 2
		";

		return $con->query($sql);
	}
	
	/**
	 * Gravar indicadores.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravar($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBINDICADOR_CCUSTO (
				ID,
				INDICADOR_ID,
				CCUSTO_CODIGO,
				PERC_INDICADOR,
				DATA_INI,
				DATA_FIM
			)
			VALUES (
				:ID,
				:INDICADOR_ID,
				:CCUSTO_CODIGO,
				:PERC_INDICADOR,
				:DATA_INI,
				:DATA_FIM
			)
			MATCHING (ID)
		";

		$args = [
			':ID' 				=> $param->ID,
			':INDICADOR_ID'		=> $param->INDICADOR->ID,
			':CCUSTO_CODIGO' 	=> $param->CCUSTO->CODIGO,
			':PERC_INDICADOR' 	=> $param->PERC_INDICADOR,
			':DATA_INI' 		=> $param->DATA_INI,
			':DATA_FIM' 		=> $param->DATA_FIM
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir indicador.
	 *
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 */
	public static function excluir($param, $con) {

		$sql = "
			UPDATE TBINDICADOR_CCUSTO
			SET STATUSEXCLUSAO = '1'
			WHERE ID = :ID
		";

		$args = [
			':ID' => $param->ID
		];

		$con->execute($sql, $args);
	}
	
}