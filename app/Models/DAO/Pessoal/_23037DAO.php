<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23037 - Avaliação de desempenho
 */
class _23037DAO {

	/**
	 * Consultar base da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarBase($param, $con) {
		
		$sql = "
			SELECT DISTINCT
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
				LEFT JOIN TBAVALIACAO_DES_RESP_BASE_CCUST C ON C.AVALIACAO_DES_RESP_BASE_ID = B.ID

			WHERE
				B.STATUSEXCLUSAO = '0'
			AND C.STATUSEXCLUSAO = '0'
            AND IIF(:TODOS_CCUSTO = 1, TRUE, C.CCUSTO_CODIGO STARTING WITH :CCUSTO)
			AND IIF(:STATUS_0 IS NULL, TRUE, B.STATUS = :STATUS_1)
            AND B.DATA_AVALIACAO BETWEEN :DATA_INI AND :DATA_FIM
		";

		$args = [
			':TODOS_CCUSTO'	=> $param->TODOS_CCUSTO,
			':CCUSTO' 		=> $param->CCUSTO,
			':STATUS_0' 	=> $param->STATUS,
			':STATUS_1' 	=> $param->STATUS,
			':DATA_INI' 	=> $param->DATA_INI,
			':DATA_FIM' 	=> $param->DATA_FIM
		];

		return $con->query($sql, $args);
	}
	
	/**
	 * Consultar avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarAvaliacao($param, $con) {
		
		$sql = "
			SELECT DISTINCT
			    R.ID,
			    R.AVALIACAO_DES_RESP_BASE_ID,
			    B.TITULO,
			    B.INSTRUCAO_INICIAL,
			    B.DATA_AVALIACAO,
			    R.COLABORADOR_ID,
			    R.COLABORADOR_NOME,
			    R.COLABORADOR_CARGO_ID,
			    R.COLABORADOR_CARGO,
			    R.COLABORADOR_GESTOR_ID,
			    R.COLABORADOR_GESTOR,
			    R.COLABORADOR_CCUSTO_CODIGO,
			    R.COLABORADOR_CCUSTO_DESCRICAO,
			    FN_CCUSTO_MASK(R.COLABORADOR_CCUSTO_CODIGO) COLABORADOR_CCUSTO_MASK,
			    R.COLABORADOR_ADMISSAO,
			    R.COLABORADOR_ESCOLARIDADE_ID,
				R.COLABORADOR_ESCOLARIDADE_DESC,
			    R.PONTUACAO_TOTAL_FATOR,
			    R.PONTUACAO_MEDIA_FATOR,
			    R.FORMACAO_ESCOLHIDA_ID,
			    R.RESULTADO_FINAL_RESUMO,
			    R.META_MEDIA_GERAL,
			    TRIM(R.ALCANCOU_META_MEDIA_GERAL) ALCANCOU_META_MEDIA_GERAL,
			    R.PONTO_POSITIVO,
			    R.PONTO_MELHORAR,
			    R.OPINIAO_AVALIADO,
			    R.DATAHORA_INSERT

			FROM
			    TBAVALIACAO_DES_RESPOSTA R
			    LEFT JOIN TBAVALIACAO_DES_RESP_BASE B ON B.ID = R.AVALIACAO_DES_RESP_BASE_ID

			WHERE
			    R.STATUSEXCLUSAO = '0'
			AND B.STATUSEXCLUSAO = '0'
			AND IIF(:TODOS_CCUSTO = 1, TRUE, R.COLABORADOR_CCUSTO_CODIGO STARTING WITH :CCUSTO)
			AND B.DATA_AVALIACAO BETWEEN :DATA_INI AND :DATA_FIM
		";

		$args = [
			':TODOS_CCUSTO'	=> $param->TODOS_CCUSTO,
			':CCUSTO' 		=> $param->CCUSTO,
			':DATA_INI' 	=> $param->DATA_INI,
			':DATA_FIM' 	=> $param->DATA_FIM
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar fatores da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarAvaliacaoFator($param, $con) {
		
		$sql = "
			SELECT
				F.ID,
				F.ID FATOR_ID,
				F.TITULO,
				F.DESCRICAO,
				F.PONTO,
				F.AVALIACAO_DES_RESP_FAT_TIPO_ID TIPO_ID,
				TRIM(F.ORDEM_PERC_NIVEL) ORDEM_PERC_NIVEL

			FROM
				TBAVALIACAO_DES_RESP_FATOR F

			WHERE
				F.STATUSEXCLUSAO = '0'
			AND F.AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_ID
		";

		$args = [
			':AVALIACAO_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar tipos dos fatores da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarAvaliacaoFatorTipo($param, $con) {
		
		$sql = "
			SELECT
				FT.ID,
				FT.TITULO

			FROM
				TBAVALIACAO_DES_RESP_FAT_TIPO FT

			WHERE
				FT.STATUSEXCLUSAO = '0'
			AND FT.AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_ID
		";

		$args = [
			':AVALIACAO_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar níveis dos fatores da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarAvaliacaoFatorNivel($param, $con) {
		
		$sql = "
			SELECT
                FN.ID,
                FN.AVALIACAO_DES_RESP_FATOR_ID FATOR_ID,
                FN.TITULO,
                FN.DESCRICAO,
                FN.FAIXA_INICIAL,
                FN.FAIXA_FINAL,
                FN.DESCRITIVO_FAIXA_INICIAL,
                FN.DESCRITIVO_FAIXA_FINAL

            FROM
                TBAVALIACAO_DES_RESP_FAT_NIVEL FN

            WHERE
                FN.STATUSEXCLUSAO = '0'
            AND FN.AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_ID
		";

		$args = [
			':AVALIACAO_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar formação da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarAvaliacaoFormacao($param, $con) {
		
		$sql = "
			SELECT
				F.ID,
				F.DESCRICAO,
				F.PONTO

			FROM
				TBAVALIACAO_DES_RESP_FORMACAO F

			WHERE
				F.STATUSEXCLUSAO = '0'
			AND F.AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_ID
		";

		$args = [
			':AVALIACAO_ID' => $param->ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar resumo da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarAvaliacaoResumo($param, $con) {
		
		$sql = "
			SELECT
				R.ID,
				R.DESCRICAO,
				R.PESO,
				R.PONTUACAO_GERAL,
				R.RESULTADO,
				R.FATOR_TIPO_ID

			FROM
				TBAVALIACAO_DES_RESP_RESUMO R

			WHERE
				R.STATUSEXCLUSAO = '0'
			AND R.AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_ID
		";

		$args = [
			':AVALIACAO_ID' => $param->ID
		];

		return $con->query($sql, $args);
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
				MF.AVALIACAO_DES_FATOR_ID FATOR_ID,
				F.TITULO,
				F.DESCRICAO,

				(SELECT FIRST 1 FT.ID
					FROM TBAVALIACAO_DES_FATOR_TIPO FT
					WHERE FT.STATUSEXCLUSAO = '0'
					AND FT.ID = F.AVALIACAO_DES_FATOR_TIPO_ID)
				TIPO_ID,

				TRIM(F.ORDEM_PERC_NIVEL) ORDEM_PERC_NIVEL,
				1 DO_MODELO

			FROM
				TBAVALIACAO_DES_MOD_FATOR MF
				INNER JOIN TBAVALIACAO_DES_FATOR F ON F.ID = MF.AVALIACAO_DES_FATOR_ID

			WHERE
				MF.STATUSEXCLUSAO = '0'
			AND MF.AVALIACAO_DES_MODELO_ID = :MODELO_ID
			AND F.STATUSEXCLUSAO = '0'
		";

		$args = [
			':MODELO_ID' => $param->AVALIACAO_DES_MODELO_ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar tipos dos fatores do modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloFatorTipo($param, $con) {
		
		$sql = "
			SELECT DISTINCT
				FT.ID,
				FT.TITULO,
				1 DO_MODELO

			FROM
				TBAVALIACAO_DES_MOD_FATOR MF
				LEFT JOIN TBAVALIACAO_DES_FATOR F ON F.ID = MF.AVALIACAO_DES_FATOR_ID
				LEFT JOIN TBAVALIACAO_DES_FATOR_TIPO FT ON FT.ID = F.AVALIACAO_DES_FATOR_TIPO_ID

			WHERE
				MF.STATUSEXCLUSAO = '0'
			AND MF.AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->AVALIACAO_DES_MODELO_ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar níveis dos fatores modelo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloFatorNivel($param, $con) {
		
		$sql = "
			SELECT
				MF.AVALIACAO_DES_FATOR_ID FATOR_ID,
				FN.AVALIACAO_DES_NIVEL_ID,
				FN.DESCRICAO,
				FN.FAIXA_INICIAL DESCRITIVO_FAIXA_INICIAL,
				FN.FAIXA_FINAL DESCRITIVO_FAIXA_FINAL,
				N.TITULO,
				N.FAIXA_INICIAL,
				N.FAIXA_FINAL,
				1 DO_MODELO

			FROM
				TBAVALIACAO_DES_MOD_FATOR MF
				LEFT JOIN TBAVALIACAO_DES_FATOR_NIVEL FN ON FN.AVALIACAO_DES_FATOR_ID = MF.AVALIACAO_DES_FATOR_ID
				LEFT JOIN TBAVALIACAO_DES_NIVEL N ON N.ID = FN.AVALIACAO_DES_NIVEL_ID

			WHERE
				MF.STATUSEXCLUSAO = '0'
			AND FN.STATUSEXCLUSAO = '0'
			AND N.STATUSEXCLUSAO = '0'
			AND MF.AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->AVALIACAO_DES_MODELO_ID
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
				F.DESCRICAO,
				F.PONTO,
				1 DO_MODELO

			FROM
				TBAVALIACAO_DES_MOD_FORMACAO MF
				INNER JOIN TBAVALIACAO_DES_FORMACAO F ON F.ID = MF.AVALIACAO_DES_FORMACAO_ID

			WHERE
				MF.STATUSEXCLUSAO = '0'
			AND F.STATUSEXCLUSAO = '0'
			AND MF.AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->AVALIACAO_DES_MODELO_ID
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
				R.DESCRICAO,
				R.PESO,

				(SELECT LIST(RT.AVALIACAO_DES_FATOR_TIPO_ID)
					FROM TBAVALIACAO_DES_RESUMO_TIPO RT
					WHERE RT.STATUSEXCLUSAO = '0'
					AND RT.AVALIACAO_DES_RESUMO_ID = MR.AVALIACAO_DES_RESUMO_ID)
				FATOR_TIPO_ID,

				1 DO_MODELO

			FROM
				TBAVALIACAO_DES_MOD_RESUMO MR
				INNER JOIN TBAVALIACAO_DES_RESUMO R ON R.ID = MR.AVALIACAO_DES_RESUMO_ID

			WHERE
				MR.STATUSEXCLUSAO = '0'
			AND R.STATUSEXCLUSAO = '0'
			AND MR.AVALIACAO_DES_MODELO_ID = :MODELO_ID
		";

		$args = [
			':MODELO_ID' => $param->AVALIACAO_DES_MODELO_ID
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar centro de custo do colaborador a partir do usuário (gestor).
	 *
	 * @access public
	 * @param Integer $colaboradorId
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarCCustoGestor($colaboradorId, $con) {

		$sql = "
			SELECT
				C.CENTRO_DE_CUSTO_CODIGO
			
			FROM
				TBCOLABORADOR C

			WHERE
				C.SITUACAO = '1'
			AND C.CODIGO = :COLABORADOR_ID
		";

		$args = [
			':COLABORADOR_ID' => $colaboradorId
		];

		return $con->query($sql, $args)[0]->CENTRO_DE_CUSTO_CODIGO;
	}

	/**
	 * Consultar colaboradores.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarColaborador($param, $con) {

		$sql = "
			SELECT
                Z.CODIGO,
                Z.PESSOAL_NOME,
                Z.PESSOAL_ESCOLARIDADE,
                Z.PESSOAL_ESCOLARIDADE_DESCRICAO,
                Z.DATA_ADMISSAO,
                Z.CENTRO_DE_CUSTO_CODIGO,
                Z.CENTRO_DE_CUSTO_DESCRICAO,
                SUBSTRING(Z.CARGO FROM 1 FOR 5) CARGO_ESCOLARIDADE,
                SUBSTRING(Z.CARGO FROM 7 FOR 5) CARGO_CODIGO,
                SUBSTRING(Z.CARGO FROM 13) CARGO_DESCRICAO

            FROM (
                SELECT
                    C.CODIGO,
                    C.PESSOAL_NOME,
                    TRIM(C.PESSOAL_ESCOLARIDADE) PESSOAL_ESCOLARIDADE,

                    (SELECT FIRST 1 E.DESCRICAO
                        FROM TBESCOLARIDADE E
                        WHERE E.STATUSEXCLUSAO = '0'
                        AND E.ID = TRIM(C.PESSOAL_ESCOLARIDADE))
                    PESSOAL_ESCOLARIDADE_DESCRICAO,

                    C.DATA_ADMISSAO,
                    C.CENTRO_DE_CUSTO_CODIGO,

                    (SELECT FIRST 1 CCU.DESCRICAO
                        FROM TBCENTRO_DE_CUSTO CCU
                        WHERE CCU.STATUS = '1'
                        AND CCU.CODIGO = C.CENTRO_DE_CUSTO_CODIGO)
                    CENTRO_DE_CUSTO_DESCRICAO,
                
                    MAX((SELECT FIRST 1 FN_LPAD(H.ESCOLARIDADE_ID, 5, 0) ||'-'|| FN_LPAD(H.CODIGO, 5, 0) ||'-'|| H.DESCRICAO
                            FROM TBCOLABORADOR_CARGO G, TBCARGO H
                            WHERE G.CARGO_CODIGO = H.CODIGO
                            AND G.COLABORADOR_CODIGO = C.CODIGO AND G.DATA_INICIAL <= CURRENT_DATE
                            ORDER BY G.DATA_INICIAL DESC))
                    CARGO
                
                FROM
                    TBCOLABORADOR C
                
                WHERE
                    C.SITUACAO = '1'
                AND C.CENTRO_DE_CUSTO_CODIGO STARTING WITH :CCUSTO
                
                GROUP BY
                    1,2,3,5,6
            ) Z
		";

		$args = [
			':CCUSTO' => $param->CCUSTO
		];

		return $con->query($sql, $args);
	}

	/**
	 * Consultar absenteísmo do colaborador.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarColaboradorAbsenteismo($param, $con) {

		$sql = "
			Select
				cast((Sum(Z.MIN_AUSENTE)*100.0/Sum(Z.MIN_TRABALHO)) as numeric(15,2)) ABSENTEISMO
				
				From (
					Select X.COLABORADOR_CODIGO,
						   IIF(X.Data_Admissao>=Cast( :DATA_INI_0 as Date),1,0) Admitido,
						   IIF(X.Data_Admissao <Cast( :DATA_INI_1 as Date),1,0) Ja_Admitido,
						   IIF(X.Data_Demissao<=Cast( :DATA_FIM_0 as Date),1,0) Demitido,
						   Sum(X.TRABALHO+X.FALTA) MIN_TRABALHO,
						   Sum(X.FALTA) MIN_AUSENTE
					From (
					
					Select P.Colaborador_Codigo, C.Data_Admissao,
						   IIF(C.Data_Demissao <= P.Data,C.Data_Demissao,null) Data_Demissao,
					
					Coalesce((Select Sum(A.Horas) From TbPonto_Cartao_Marcacao A, TbPonto_Situacao B
					  Where A.Colaborador_Codigo = P.Colaborador_Codigo and A.Data = P.Data
						and A.Situacao_Codigo = B.Codigo and B.Absenteismo = '0'),0) Trabalho,
					
					Coalesce((Select Sum(A.Horas) From TbPonto_Cartao_Marcacao A, TbPonto_Situacao B
					  Where A.Colaborador_Codigo = P.Colaborador_Codigo and A.Data = P.Data
						and A.Situacao_Codigo = B.Codigo and B.Absenteismo in ('1','2')),0) Falta
					
					From TbPonto_Cartao P, TBColaborador C
					Where P.data_bsc Between :DATA_INI_2 and :DATA_FIM_1
					  and c.codigo = :COLABORADOR
					  and P.Colaborador_Codigo = C.Codigo
					  and((C.Situacao=1 and C.Data_Admissao <= :DATA_FIM_2) or
						  (C.Situacao=2 and C.Data_Demissao >= :DATA_INI_3 and C.Data_Admissao <= :DATA_FIM_3))
				) X
				Group By X.Colaborador_Codigo,X.Data_Admissao,X.Data_Demissao
			) Z
		";

		$args = [
			':DATA_INI_0'   => $param->DATA_INI,
			':DATA_INI_1'   => $param->DATA_INI,
			':DATA_INI_2'   => $param->DATA_INI,
			':DATA_INI_3'   => $param->DATA_INI,
			':DATA_FIM_0'   => $param->DATA_FIM,
			':DATA_FIM_1'   => $param->DATA_FIM,
			':DATA_FIM_2'   => $param->DATA_FIM,
			':DATA_FIM_3'   => $param->DATA_FIM,
			':COLABORADOR'  => $param->COLABORADOR->CODIGO
		];

		return $con->query($sql, $args);
	}


	/**
	 * Consultar indicadores do colaborador.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function consultarColaboradorIndicador($param, $con) {

		$sql = "
			SELECT 
                I.ID,
                I.INDICADOR_ID,
                I.PERC_INDICADOR

            FROM
                TBINDICADOR_CCUSTO I

            WHERE
                I.STATUSEXCLUSAO = '0'
            AND I.DATA_FIM >= 'NOW'
            AND I.CCUSTO_CODIGO STARTING WITH :CCUSTO_CODIGO
		";

		$args = [
			':CCUSTO_CODIGO' => $param->COLABORADOR->CENTRO_DE_CUSTO_CODIGO
		];

		return $con->query($sql, $args);
	}

	/**
	 * Gerar id da avaliação.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdAvaliacao($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_RESPOSTA, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}

	/**
	 * Gerar id do tipo do fator.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdFatorTipo($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_RESP_FAT_TIPO, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}

	/**
	 * Gerar id do fator.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdFator($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_RESP_FATOR, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}

	/**
	 * Gerar id da formação.
	 *
	 * @access public
	 * @param _Conexao $con
	 * @return Integer ID
	 */
	public static function gerarIdFormacao($con) {

		$sql = 'SELECT GEN_ID(GTBAVALIACAO_DES_RESP_FORMACAO, 1) ID FROM RDB$DATABASE';

		return $con->query($sql)[0]->ID;
	}

	/**
	 * Gravar avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarAvaliacao($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESPOSTA (
				ID,
				AVALIACAO_DES_RESP_BASE_ID,
				COLABORADOR_ID,
				COLABORADOR_NOME,
				COLABORADOR_CARGO_ID,
				COLABORADOR_CARGO,
				COLABORADOR_GESTOR_ID,
				COLABORADOR_GESTOR,
				COLABORADOR_CCUSTO_CODIGO,
				COLABORADOR_CCUSTO_DESCRICAO,
				COLABORADOR_ADMISSAO,
				COLABORADOR_ESCOLARIDADE_ID,
				COLABORADOR_ESCOLARIDADE_DESC,
				PONTUACAO_TOTAL_FATOR,
				PONTUACAO_MEDIA_FATOR,
				FORMACAO_ESCOLHIDA_ID,
				RESULTADO_FINAL_RESUMO,
				META_MEDIA_GERAL,
				ALCANCOU_META_MEDIA_GERAL,
				PONTO_POSITIVO,
				PONTO_MELHORAR,
				OPINIAO_AVALIADO,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESP_BASE_ID,
				:COLABORADOR_ID,
				:COLABORADOR_NOME,
				:COLABORADOR_CARGO_ID,
				:COLABORADOR_CARGO,
				:COLABORADOR_GESTOR_ID,
				:COLABORADOR_GESTOR,
				:COLABORADOR_CCUSTO_CODIGO,
				:COLABORADOR_CCUSTO_DESCRICAO,
				:COLABORADOR_ADMISSAO,
				:COLABORADOR_ESCOLARIDADE_ID,
				:COLABORADOR_ESCOLARIDADE_DESC,
				:PONTUACAO_TOTAL_FATOR,
				:PONTUACAO_MEDIA_FATOR,
				:FORMACAO_ESCOLHIDA_ID,
				:RESULTADO_FINAL_RESUMO,
				:META_MEDIA_GERAL,
				:ALCANCOU_META_MEDIA_GERAL,
				:PONTO_POSITIVO,
				:PONTO_MELHORAR,
				:OPINIAO_AVALIADO,
				:STATUSEXCLUSAO
			)
		";

		$args = [
			':ID'                           => $param->ID,
			':AVALIACAO_DES_RESP_BASE_ID'   => $param->AVALIACAO_DES_RESP_BASE_ID,
			':COLABORADOR_ID'               => $param->COLABORADOR->CODIGO,
			':COLABORADOR_NOME'             => $param->COLABORADOR->PESSOAL_NOME,
			':COLABORADOR_CARGO_ID'         => $param->COLABORADOR->CARGO_CODIGO,
			':COLABORADOR_CARGO'            => $param->COLABORADOR->CARGO_DESCRICAO,
			':COLABORADOR_GESTOR_ID'        => $param->GESTOR->ID,
			':COLABORADOR_GESTOR'           => $param->GESTOR->DESCRICAO,
			':COLABORADOR_CCUSTO_CODIGO'    => $param->COLABORADOR->CENTRO_DE_CUSTO_CODIGO,
			':COLABORADOR_CCUSTO_DESCRICAO' => $param->COLABORADOR->CENTRO_DE_CUSTO_DESCRICAO,
			':COLABORADOR_ADMISSAO'         => $param->COLABORADOR->DATA_ADMISSAO,
			':COLABORADOR_ESCOLARIDADE_ID'  => $param->COLABORADOR->PESSOAL_ESCOLARIDADE,
			':COLABORADOR_ESCOLARIDADE_DESC'=> $param->COLABORADOR->PESSOAL_ESCOLARIDADE_DESCRICAO,
			':PONTUACAO_TOTAL_FATOR'        => $param->PONTUACAO_TOTAL_FATOR,
			':PONTUACAO_MEDIA_FATOR'        => $param->PONTUACAO_MEDIA_FATOR,
			':FORMACAO_ESCOLHIDA_ID'        => $param->FORMACAO_ESCOLHIDA_ID,
			':RESULTADO_FINAL_RESUMO'       => $param->RESULTADO_FINAL_RESUMO,
			':META_MEDIA_GERAL'             => $param->META_MEDIA_GERAL,
			':ALCANCOU_META_MEDIA_GERAL'    => $param->ALCANCOU_META_MEDIA_GERAL,
			':PONTO_POSITIVO'               => $param->PONTO_POSITIVO,
			':PONTO_MELHORAR'               => $param->PONTO_MELHORAR,
			':OPINIAO_AVALIADO'             => $param->OPINIAO_AVALIADO,
			':STATUSEXCLUSAO'               => $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar tipos de fatores.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarFatorTipo($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESP_FAT_TIPO (
				ID,
				AVALIACAO_DES_RESPOSTA_ID,
				TITULO,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESPOSTA_ID,
				:TITULO,
				:STATUSEXCLUSAO
			)
		";

		$args = [
			':ID'                        => $param->ID,
			':AVALIACAO_DES_RESPOSTA_ID' => $param->AVALIACAO_DES_RESPOSTA_ID,
			':TITULO'                    => $param->TITULO,
			':STATUSEXCLUSAO'            => $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar fatores.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarFator($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESP_FATOR (
				ID,
				AVALIACAO_DES_RESPOSTA_ID,
				TITULO,
				DESCRICAO,
				PONTO,
				AVALIACAO_DES_RESP_FAT_TIPO_ID,
				ORDEM_PERC_NIVEL,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESPOSTA_ID,
				:TITULO,
				:DESCRICAO,
				:PONTO,
				:AVALIACAO_DES_RESP_FAT_TIPO_ID,
				:ORDEM_PERC_NIVEL,
				:STATUSEXCLUSAO
			)
		";

		$args = [
			':ID'                               => $param->ID,
			':AVALIACAO_DES_RESPOSTA_ID'        => $param->AVALIACAO_DES_RESPOSTA_ID,
			':TITULO'                           => $param->TITULO,
			':DESCRICAO'                        => $param->DESCRICAO,
			':PONTO'                            => $param->PONTO,
			':AVALIACAO_DES_RESP_FAT_TIPO_ID'   => $param->AVALIACAO_DES_RESP_FAT_TIPO_ID,
			':ORDEM_PERC_NIVEL'   				=> $param->ORDEM_PERC_NIVEL,
			':STATUSEXCLUSAO'                   => $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar descrição dos níveis dos fatores.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarFatorNivel($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESP_FAT_NIVEL (
				ID,
				AVALIACAO_DES_RESPOSTA_ID,
				AVALIACAO_DES_RESP_FATOR_ID,
				TITULO,
				DESCRICAO,
				FAIXA_INICIAL,
				FAIXA_FINAL,
				DESCRITIVO_FAIXA_INICIAL,
				DESCRITIVO_FAIXA_FINAL,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESPOSTA_ID,
				:AVALIACAO_DES_RESP_FATOR_ID,
				:TITULO,
				:DESCRICAO,
				:FAIXA_INICIAL,
				:FAIXA_FINAL,
				:DESCRITIVO_FAIXA_INICIAL,
				:DESCRITIVO_FAIXA_FINAL,
				:STATUSEXCLUSAO
			)
		";

		$args = [
			':ID'                               => $param->ID,
			':AVALIACAO_DES_RESPOSTA_ID'        => $param->AVALIACAO_DES_RESPOSTA_ID,
			':AVALIACAO_DES_RESP_FATOR_ID'      => $param->AVALIACAO_DES_RESP_FATOR_ID,
			':TITULO'                           => $param->TITULO,
			':DESCRICAO'                        => $param->DESCRICAO,
			':FAIXA_INICIAL'                    => $param->FAIXA_INICIAL,
			':FAIXA_FINAL'                      => $param->FAIXA_FINAL,
			':DESCRITIVO_FAIXA_INICIAL'         => $param->DESCRITIVO_FAIXA_INICIAL,
			':DESCRITIVO_FAIXA_FINAL'           => $param->DESCRITIVO_FAIXA_FINAL,
			':STATUSEXCLUSAO'                   => $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar formação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarFormacao($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESP_FORMACAO (
				ID,
				AVALIACAO_DES_RESPOSTA_ID,
				DESCRICAO,
				PONTO,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESPOSTA_ID,
				:DESCRICAO,
				:PONTO,
				:STATUSEXCLUSAO
			)
		";

		$args = [
			':ID'                        => $param->ID,
			':AVALIACAO_DES_RESPOSTA_ID' => $param->AVALIACAO_DES_RESPOSTA_ID,
			':DESCRICAO'                 => $param->DESCRICAO,
			':PONTO'                     => $param->PONTO,
			':STATUSEXCLUSAO'            => $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Gravar resumo.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarResumo($param, $con) {

		$sql = "
			UPDATE OR INSERT INTO TBAVALIACAO_DES_RESP_RESUMO (
				ID,
				AVALIACAO_DES_RESPOSTA_ID,
				DESCRICAO,
				PESO,
				PONTUACAO_GERAL,
				RESULTADO,
				FATOR_TIPO_ID,
				STATUSEXCLUSAO
			)
			VALUES (
				:ID,
				:AVALIACAO_DES_RESPOSTA_ID,
				:DESCRICAO,
				:PESO,
				:PONTUACAO_GERAL,
				:RESULTADO,
				:FATOR_TIPO_ID,
				:STATUSEXCLUSAO
			)
		";

		$args = [
			':ID'                        => $param->ID,
			':AVALIACAO_DES_RESPOSTA_ID' => $param->AVALIACAO_DES_RESPOSTA_ID,
			':DESCRICAO'                 => $param->DESCRICAO,
			':PESO'                      => $param->PESO,
			':PONTUACAO_GERAL'           => $param->PONTUACAO_GERAL,
			':RESULTADO'                 => $param->RESULTADO,
			':FATOR_TIPO_ID'             => $param->FATOR_TIPO_ID,
			':STATUSEXCLUSAO'            => $param->STATUSEXCLUSAO
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirAvaliacao($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESPOSTA
			SET STATUSEXCLUSAO = '1'
			WHERE ID = :ID
		";

		$args = [
			':ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir tipos de fatores da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirAvaliacaoFatorTipo($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESP_FAT_TIPO
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_DES_RESPOSTA_ID
		";

		$args = [
			':AVALIACAO_DES_RESPOSTA_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir fatores da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirAvaliacaoFator($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESP_FATOR
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_DES_RESPOSTA_ID
		";

		$args = [
			':AVALIACAO_DES_RESPOSTA_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir níveis de fatores da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirAvaliacaoFatorNivel($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESP_FAT_NIVEL
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_DES_RESPOSTA_ID
		";

		$args = [
			':AVALIACAO_DES_RESPOSTA_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir formações da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirAvaliacaoFormacao($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESP_FORMACAO
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_DES_RESPOSTA_ID
		";

		$args = [
			':AVALIACAO_DES_RESPOSTA_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}

	/**
	 * Excluir resumo da avaliação.
	 *
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirAvaliacaoResumo($param, $con) {

		$sql = "
			UPDATE TBAVALIACAO_DES_RESP_RESUMO
			SET STATUSEXCLUSAO = '1'
			WHERE AVALIACAO_DES_RESPOSTA_ID = :AVALIACAO_DES_RESPOSTA_ID
		";

		$args = [
			':AVALIACAO_DES_RESPOSTA_ID' => $param->ID
		];

		$con->execute($sql, $args);
	}
}