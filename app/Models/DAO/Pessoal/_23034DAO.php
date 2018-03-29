<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23034 - Cadastro de resumo para avaliação de desempenho.
 */
class _23034DAO {
	
	/**
	 * Consultar resumo.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarResumo($con) {
		
		$sql = "
			SELECT 
				R.ID,
				R.DESCRICAO,
				R.PESO

			FROM
				TBAVALIACAO_DES_RESUMO R

			WHERE
				R.STATUSEXCLUSAO = '0'
		";

		return $con->query($sql);
	}

	/**
	 * Consultar tipos de fatores do resumo.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarResumoFatorTipo($con) {
		
		$sql = "
			SELECT 
				T.ID,
				T.AVALIACAO_DES_RESUMO_ID,
				T.AVALIACAO_DES_FATOR_TIPO_ID FATOR_TIPO_ID,

				(SELECT FIRST 1 FT.TITULO
                    FROM TBAVALIACAO_DES_FATOR_TIPO FT
                    WHERE FT.STATUSEXCLUSAO = '0'
                    AND FT.ID = T.AVALIACAO_DES_FATOR_TIPO_ID)
                FATOR_TITULO

			FROM
				TBAVALIACAO_DES_RESUMO_TIPO T

			WHERE
				T.STATUSEXCLUSAO = '0'
		";

		return $con->query($sql);
	}

	/**
     * Consultar tipos de fatores.
     *
     * @access public
     * @param _Conexao $con
     * @return array
     */
    public static function consultarFatorTipo($con) {
        
        $sql = "
            SELECT 
                T.ID FATOR_TIPO_ID,
                T.TITULO FATOR_TIPO_TITULO

            FROM
                TBAVALIACAO_DES_FATOR_TIPO T

            WHERE
                T.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);
    }

	/**
	 * Gerar id do resumo.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdResumo($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_RESUMO, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}
	
	/**
	 * Gravar resumo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravar($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESUMO (
				ID,
				DESCRICAO,
				PESO
			)
			VALUES (
				:ID,
				:DESCRICAO,
				:PESO
			)
			MATCHING (ID)
		";

		$args = [
			':ID'        => $param->ID,
			':DESCRICAO' => $param->DESCRICAO,
			':PESO'      => $param->PESO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar tipos de fatores relacionado ao resumo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 */
	public static function gravarResumoTipo($param, $con) {
		
		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESUMO_TIPO (
				ID,
				AVALIACAO_DES_RESUMO_ID,
				AVALIACAO_DES_FATOR_TIPO_ID,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESUMO_ID,
				:AVALIACAO_DES_FATOR_TIPO_ID,
				:STATUSEXCLUSAO
			)
			MATCHING (ID)
		";

		$args = [
			':ID'                           => $param->ID,
			':AVALIACAO_DES_RESUMO_ID'      => $param->AVALIACAO_DES_RESUMO_ID,
			':AVALIACAO_DES_FATOR_TIPO_ID'  => $param->AVALIACAO_DES_FATOR_TIPO_ID,
			':STATUSEXCLUSAO'  				=> $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir resumo.
	 *
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 */
	public static function excluir($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESUMO
			SET STATUSEXCLUSAO = '1'
			WHERE ID = :ID
		";

		$args = [
			':ID' => $param->ID
		];

		$con->execute($sql, $args);
	}
	
}