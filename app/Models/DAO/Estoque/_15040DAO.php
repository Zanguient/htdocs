<?php

namespace App\Models\DAO\Estoque;

use App\Models\DTO\Estoque\_15040;
use App\Models\Conexao\_Conexao;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto 15040 - Baixa de Estoque.
 */
class _15040DAO {
	
	/**
	 * Consulta as baixas realizadas.
     * 
     * @return array
     */
    public static function listar() {
		
		$con = new _Conexao();
			
		$sql = "
            SELECT FIRST 30 DISTINCT
				FN_LPAD(E.ID, 5, 0) ID,
				FN_LPAD(E.TABELA_ID, 5, 0) REQUISICAO_ID,
				E.DATAHORA,

				(SELECT FIRST 1
					C.CCUSTO||' - '||
					(SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO)
				 FROM TBCONSUMO_REQUISICAO C
				 WHERE C.ID = E.TABELA_ID)
				CCUSTO,

				(SELECT FIRST 1 FN_LPAD(C.ESTABELECIMENTO_ID, 2, 0) FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID) 
				ESTABELECIMENTO_ID,
				
				FN_LPAD(E.LOCALIZACAO_ID, 2, 0) LOCALIZACAO_ID,
				E.OPERACAO_CODIGO,

				C.USUARIO_ID REQUERENTE_ID,
                (SELECT IIF(NOME IS NULL, USUARIO, NOME) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) REQUERENTE_DESCRICAO,
				
				FN_LPAD(P.CODIGO, 5, '0') PRODUTO_ID,
                P.DESCRICAO PRODUTO_DESCRICAO,
				P.UNIDADEMEDIDA_SIGLA UM,
				
				C.TAMANHO,
				(SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE (
					(SELECT FIRST 1 P.GRADE_CODIGO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID),
					 C.TAMANHO)
				) TAMANHO_DESCRICAO,

				FN_LPAD(E.QUANTIDADE, 5, 0) QUANTIDADE,
				FN_LPAD(E.USUARIO_ID, 4, 0) USUARIO_ID,
				
				(SELECT IIF(U.NOME IS NULL, U.USUARIO, U.NOME) FROM TBUSUARIO U WHERE U.CODIGO = E.USUARIO_ID)
				USUARIO_DESCRICAO,
				
				FN_LPAD(E.ESTOQUE_ID, 10, 0) ESTOQUE_ID
				
			FROM 
				TBESTOQUE_BAIXA E
				LEFT JOIN TBCONSUMO_REQUISICAO C ON C.ID = E.TABELA_ID
				LEFT JOIN TBPRODUTO P ON P.CODIGO = C.PRODUTO_ID
				LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = P.FAMILIA_CODIGO
				
			WHERE
				E.STATUSEXCLUSAO = '0'
			AND E.DATAHORA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND DATEADD(+1 DAY TO CURRENT_DATE)
			AND	E.TIPO = 'R'
			AND CR.BAIXA = '1'
			AND CR.USUARIO_ID = :USU_ID
			
			ORDER BY 1 DESC
		";
		
		$args = array(
			':USU_ID' => Auth::user()->CODIGO
		);
		
		return $con->query($sql, $args);
    }
	
	/**
     * Listar Requisições de Consumo. Utilizado no processo 'create'.
     * 
	 * @param $estab_perm
     * @return array
     */
    public static function listarRequisicao($estab_perm) {
		
		$con = new _Conexao();
			
		$sql = "
            SELECT FIRST 30 DISTINCT
				FN_LPAD(C.ID, 5, '0') ID,
				C.DATAHORA AS DATA,
				C.USUARIO_ID,

				(SELECT IIF(NOME IS NULL, USUARIO, NOME) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID)
				USUARIO_DESCRICAO,

				FN_LPAD(C.ESTABELECIMENTO_ID, 2, '0') ESTABELECIMENTO_ID,

				(SELECT LIST(FN_LPAD(E.LOCALIZACAO_ID, 2, '0')) FROM TBESTOQUE_BAIXA E WHERE E.TABELA_ID = C.ID AND E.TIPO = 'R')
				LOCALIZACAO_ID,
				
				(SELECT FIRST 1 FN_LPAD(P.LOCALIZACAO_CODIGO, 2, '0') FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID)
				LOCALIZACAO_PADRAO,

				C.DOCUMENTO,
				C.CCUSTO,

				(SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO)
				CCUSTO_DESCRICAO,

				FN_LPAD(C.TURNO_ID, 2, '0') TURNO_ID,

				(SELECT T.DESCRICAO FROM TBTURNO T WHERE T.CODIGO = C.TURNO_ID)
				TURNO_DESCRICAO,

				(SELECT LIST(E.OPERACAO_CODIGO) FROM TBESTOQUE_BAIXA E WHERE E.TABELA_ID = C.ID AND E.TIPO = 'R')
				OPERACAO_CODIGO,
				
				F.OPERACAO_REQUISICAO,
				FN_LPAD(F.CODIGO, 2, '0') FAMILIA_ID,

				FN_LPAD(C.PRODUTO_ID, 5, '0') PRODUTO_ID,
				
				(SELECT FIRST 1 P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID)
				PRODUTO_DESCRICAO,
				
				P.UNIDADEMEDIDA_SIGLA UM,

				FN_LPAD(C.QUANTIDADE, 4, '0') QUANTIDADE,
				C.TAMANHO,
				
				(SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE (
					(SELECT FIRST 1 P.GRADE_CODIGO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID),
					 C.TAMANHO)
				) TAMANHO_DESCRICAO,

				C.OBSERVACAO,
				C.SALDO,
				C.STATUS
			FROM
				TBCONSUMO_REQUISICAO C
				LEFT JOIN TBPRODUTO P ON P.CODIGO = C.PRODUTO_ID
				LEFT JOIN TBFAMILIA F ON F.CODIGO = P.FAMILIA_CODIGO,
				(SELECT DISTINCT
                    PX.USUARIO_ID,
                    PX.FAMILIA
                FROM
                    TBCONSUMO_REQ_PERMISSAO PX
                WHERE
                    PX.USUARIO_ID = :USU_ID

                AND PX.BAIXA = '1')PERM
			WHERE    
				C.STATUSEXCLUSAO = '0'
				AND PERM.FAMILIA = P.FAMILIA_CODIGO
				AND C.DATA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND CURRENT_DATE
				/*@ESTAB*/
				
			ORDER BY 1 DESC
		";
		
		//--AND C.CCUSTO LIKE (SELECT CR.CCUSTO_REQUISICAO FROM TBCONSUMO_REQ_PERMISSAO CR WHERE CR.REQUISICAO = '1' AND CR.USUARIO_ID = :USU_ID)||'%'
		
		$args = array(
			':USU_ID'=> Auth::user()->CODIGO,
			'@ESTAB' => "AND C.ESTABELECIMENTO_ID in($estab_perm)"
		);
		
		return $con->query($sql, $args);
    }
	
	/**
     * Realiza a baixa.
     * 
     * @param _15040 $obj
     */
    public static function gravar(_15040 $obj) {
        
        $con = new _Conexao();

        try {
			
            self::alterarReqConsumo($con, $obj);
			
            $con->commit();

        } catch(Exception $e) {

            $con->rollback();
            throw $e;

        }

    }
	
	/**
	 * Alterar saldo na Requisição de Consumo.
	 * Função complementar à 'gravar'.
	 * 
	 * @param _Conexao $con
	 * @param _15040 $obj
	 */
	public static function alterarReqConsumo(_Conexao $con, _15040 $obj) {
		
		//Obs.: 'Documento' é alterado via trigger.
		
		$i = 0;
		foreach ($obj->getRequisicaoId() as $req_id) {
		
			$sql = "
				UPDATE TBCONSUMO_REQUISICAO
				SET
					SALDO = :SALDO
				WHERE
					ID = :ID
			";

			$args = array(
				':SALDO'	=> $obj->getSaldo()[$i],
				':ID'		=> $req_id
			);

			$con->execute($sql, $args);
			
			self::registrarBaixaEstoque($con, $obj, $req_id, $i);
			
			$i++;
			
		}
		
	}
	
	/**
	 * Registrar baixa no estoque.
	 * Função complementar à 'alterarReqConsumo'.
	 * 
	 * @param _Conexao $con
	 * @param _15040 $obj
	 * @param int $req_id
	 */
	public static function registrarBaixaEstoque(_Conexao $con, _15040 $obj, $req_id, $i) {
		
		$sql = "
			INSERT INTO TBESTOQUE_BAIXA (
				TIPO,
				TABELA_ID, 
				LOCALIZACAO_ID,
				OPERACAO_CODIGO,
				QUANTIDADE,
				USUARIO_ID
			)
			VALUES (
				:TIPO,
				:TAB_ID,
				:LOC_ID,
				:OPER,
				:QTD,
				:USU_ID
			)
		";

		$args = array(
			':TIPO'		=> 'R',
			':TAB_ID'	=> $req_id,
			':LOC_ID'	=> $obj->getLocalizacaoId()[$i],
			':OPER'		=> $obj->getOperacaoCodigo()[$i],
			':QTD'		=> $obj->getQuantidade()[$i],
			':USU_ID'	=> $obj->getUsuarioId()
		);

		$con->execute($sql, $args);
		
	}

	/**
	 * Exibe dados da Baixa.
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		
		$con = new _Conexao();
		
		$sql = "
			SELECT
				FN_LPAD(E.ID, 5, 0) ID,
				FN_LPAD(E.TABELA_ID, 5, 0) REQUISICAO_ID,
				E.DATAHORA,

				(SELECT FIRST 1 C.CCUSTO FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID)
				CCUSTO,

				(SELECT FIRST 1
					(SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO)
				 FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID)
				CCUSTO_DESCRICAO,

				(SELECT FIRST 1 FN_LPAD(C.ESTABELECIMENTO_ID, 2, 0) FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID) 
				ESTABELECIMENTO_ID,

				FN_LPAD(E.LOCALIZACAO_ID, 2, 0) LOCALIZACAO_ID,
				E.OPERACAO_CODIGO,
				
				(SELECT FIRST 1 O.DESCRICAO FROM TBOPERACAO O WHERE O.CODIGO = E.OPERACAO_CODIGO) 
				OPERACAO_DESCRICAO,
				
				E.QUANTIDADE,
				
				(SELECT FIRST 1 C.QUANTIDADE FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID)
				QUANTIDADE_REQUISICAO,

				(SELECT FIRST 1 C.SALDO FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID)
				SALDO_REQUISICAO,

				FN_LPAD(E.USUARIO_ID, 4, 0) USUARIO_ID,

				(SELECT IIF(U.NOME IS NULL, U.USUARIO, U.NOME) FROM TBUSUARIO U WHERE U.CODIGO = E.USUARIO_ID)
				USUARIO_DESCRICAO,

				FN_LPAD(E.ESTOQUE_ID, 10, 0) ESTOQUE_ID
			FROM 
				TBESTOQUE_BAIXA E
				LEFT JOIN TBCONSUMO_REQUISICAO C ON C.ID = E.TABELA_ID
				LEFT JOIN TBPRODUTO P ON P.CODIGO = C.PRODUTO_ID
				LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = P.FAMILIA_CODIGO
				
			WHERE
				E.STATUSEXCLUSAO = '0'
			AND	E.TIPO = 'R'
			AND E.ID = :ID
			AND CR.BAIXA = '1'
			AND CR.USUARIO_ID = :USU_ID
			
			ORDER BY 1 DESC
		";
		
		$args = array(
			':USU_ID'	=> Auth::user()->CODIGO,
			':ID'		=> $id
		);
        
        $ret = $con->query($sql, $args);
        
        if(count($ret) === 0){
            $ret = [];
        }else{
            $ret = $ret[0];
        }
		
		return array('dado' => $ret );
				
	}

	/**
     * Alterar dados do objeto na base de dados.
     * 
     * @param _15040 $obj
     */
    public static function alterar(_15040 $obj) {
        
        $con = new _Conexao();

        try {
			
            $sql = "
				UPDATE TBESTOQUE_BAIXA
				SET
					DATAHORA        = CURRENT_DATE,
					LOCALIZACAO_ID  = :LOC,
					OPERACAO_CODIGO = :OP,
					QUANTIDADE      = :QTD,
					USUARIO_ID      = :USU
				WHERE
					ID = :ID
			";
			
			$args = array(
				':LOC'	=> $obj->getLocalizacaoId()[0],
				':OP'	=> $obj->getOperacaoCodigo()[0],
				':QTD'	=> $obj->getQuantidade()[0],
				':USU'	=> $obj->getUsuarioId(),
				':ID'	=> $obj->getId()
			);

			$con->execute($sql, $args);
            $con->commit();

        } catch(Exception $e) {

            $con->rollback();
            throw $e;

        }

    }
	
	/**
     * Exclui dados do objeto na base de dados.
     * 
     * @param int $id
     */
    public static function excluir($id) {
		
		$con = new _Conexao();

        try {
            
            //licitação
            $sql1 = "
				UPDATE TBESTOQUE_BAIXA
				SET STATUSEXCLUSAO = '1'
				WHERE ID = :ID
			";
			
			$args1 = array(':ID' => $id);

			$con->execute($sql1, $args1);
            $con->commit();

        } catch(Exception $e) {

            $con->rollback();
            throw $e;

        }

    }

	/**
     * Paginação com scroll.
     * Função chamada via Ajax.
     *
     * @param int $qtd_por_pagina
     * @param int $pagina
	 * @param string $filtro
	 * @param string $estab_perm
	 * @param int $status
	 * @param int $estab
	 * @param string $data_ini
	 * @param string $data_fim
     * @return array
     */
    public static function paginacaoScroll($qtd_por_pagina, $pagina, $filtro, $estab_perm, $status, $estab, $data_ini, $data_fim) {

        $con = new _Conexao();

		$sql = "
			SELECT FIRST :QTD SKIP :PAG
				X.ID,
				X.DATA,
				X.USUARIO_ID,
				X.USUARIO_DESCRICAO,
				X.ESTABELECIMENTO_ID,
				X.LOCALIZACAO_ID,
				X.LOCALIZACAO_PADRAO,
				X.DOCUMENTO,
				X.CCUSTO,
				X.CCUSTO_DESCRICAO,
				X.TURNO_ID,
				X.TURNO_DESCRICAO,
				X.OPERACAO_CODIGO,
				X.OPERACAO_REQUISICAO,
				X.FAMILIA_ID,
				X.PRODUTO_ID,
				X.PRODUTO_DESCRICAO,
				X.UM,
				X.QUANTIDADE,
				X.TAMANHO,
				X.TAMANHO_DESCRICAO,
				X.OBSERVACAO,
				X.SALDO,
				X.STATUS

			FROM (
				SELECT DISTINCT
					FN_LPAD(C.ID, 5, '0') ID,
					C.DATAHORA AS DATA,
					C.USUARIO_ID,
					
					(SELECT IIF(NOME IS NULL, USUARIO, NOME) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) 
					USUARIO_DESCRICAO,
					
					FN_LPAD(C.ESTABELECIMENTO_ID, 2, '0') ESTABELECIMENTO_ID,
					
					(SELECT LIST(FN_LPAD(E.LOCALIZACAO_ID, 2, '0')) FROM TBESTOQUE_BAIXA E WHERE E.TABELA_ID = C.ID AND E.TIPO = 'R')
					LOCALIZACAO_ID,
					
					(SELECT FIRST 1 FN_LPAD(P.LOCALIZACAO_CODIGO, 2, '0') FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID)
					LOCALIZACAO_PADRAO,
					
					C.DOCUMENTO,
					C.CCUSTO,
					
					(SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO) 
					CCUSTO_DESCRICAO,
					
					FN_LPAD(C.TURNO_ID, 2, '0') TURNO_ID,
					
					(SELECT T.DESCRICAO FROM TBTURNO T WHERE T.CODIGO = C.TURNO_ID) 
					TURNO_DESCRICAO,
					
					(SELECT LIST(E.OPERACAO_CODIGO) FROM TBESTOQUE_BAIXA E WHERE E.TABELA_ID = C.ID AND E.TIPO = 'R')
					OPERACAO_CODIGO,
					
					F.OPERACAO_REQUISICAO,
					FN_LPAD(F.CODIGO, 2, '0') FAMILIA_ID,
					
					FN_LPAD(C.PRODUTO_ID, 5, '0') PRODUTO_ID,
					
					(SELECT P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID) 
					PRODUTO_DESCRICAO,
					
					P.UNIDADEMEDIDA_SIGLA UM,
					
					FN_LPAD(C.QUANTIDADE, 4, '0') QUANTIDADE,					
					C.TAMANHO,
					
					(SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE (
						(SELECT FIRST 1 P.GRADE_CODIGO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID),
						 C.TAMANHO)
					) TAMANHO_DESCRICAO,
					
					C.OBSERVACAO,
					C.SALDO,
					C.STATUS
				FROM
					TBCONSUMO_REQUISICAO C
					LEFT JOIN TBPRODUTO P ON P.CODIGO = C.PRODUTO_ID
					LEFT JOIN TBFAMILIA F ON F.CODIGO = P.FAMILIA_CODIGO,
					(SELECT DISTINCT
                        PX.USUARIO_ID,
                        PX.FAMILIA
                    FROM
                        TBCONSUMO_REQ_PERMISSAO PX
                    WHERE
                        PX.USUARIO_ID = :USU_ID

                    AND PX.BAIXA = '1')PERM
				WHERE
					C.STATUSEXCLUSAO = '0'
				AND PERM.FAMILIA = P.FAMILIA_CODIGO
			) X

			WHERE 
				(
				(X.ID LIKE :ID)
			OR  (CAST(
                    FN_LPAD(EXTRACT(DAY FROM X.DATA), 2, '0')||'/'||FN_LPAD(EXTRACT(MONTH FROM X.DATA), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATA)
                 AS VARCHAR(100)) LIKE :DATA)
			OR  (X.PRODUTO_ID LIKE :PROD_ID)
			OR  (X.PRODUTO_DESCRICAO LIKE :PROD_DESC)
			OR  (CAST(X.CCUSTO AS VARCHAR(100)) LIKE :CCUSTO)
			OR  (X.CCUSTO_DESCRICAO LIKE :CCUSTO_DESC)
				)
				/*@STATUS*/
				/*@ESTAB*/
				/*@DATA*/

			ORDER BY 1 DESC
		";
		
		//AND X.CCUSTO LIKE (SELECT FIRST 1 UC.CCUSTO FROM TBUSUARIO_CCUSTO UC WHERE UC.USUARIO_ID = :USU_ID)||'%'
		
		//Se nenhum estabelecimento for passado, serão listados todas as requisições
		// de acordo com o estabelecimento permitido pelo usuário.
		if($estab === '') {
			$est = "AND X.ESTABELECIMENTO_ID in($estab_perm)";
		}
		else {
			$est = "AND X.ESTABELECIMENTO_ID = ".$estab;
		}
		
		//Se nenhum período for passado, o período será de 3 meses
		if( empty($data_ini) ) {
			$periodo = "AND X.DATA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND CURRENT_DATE";
		}
		else {
			$periodo = "AND X.DATA BETWEEN '".$data_ini."' AND '".$data_fim."'";
		}
		

		$args = array(
			':QTD'			=> $qtd_por_pagina, 
			':PAG'			=> $pagina,
			':ID'			=> '%'.$filtro.'%',
			':DATA'			=> '%'.$filtro.'%',
			':PROD_ID'		=> '%'.$filtro.'%',
			':PROD_DESC'	=> '%'.$filtro.'%',
			':CCUSTO'		=> '%'.$filtro.'%',
			':CCUSTO_DESC'	=> '%'.$filtro.'%',
			'@STATUS'		=> $status === ''   ? '' : "AND X.STATUS = ".$status,
			'@ESTAB'		=> $est,
			'@DATA'			=> $periodo,
			':USU_ID'		=> Auth::user()->CODIGO
		);
		
		return $con->query($sql, $args);
    }
	
	/**
     * Paginação com scroll (Baixa).
     * Função chamada via Ajax.
     *
     * @param int $qtd_por_pagina
     * @param int $pagina
	 * @param string $filtro
	 * @param string $estab_perm
	 * @param int $estab
	 * @param string $data_ini
	 * @param string $data_fim
     * @return array
     */
    public static function paginacaoScrollBaixa($qtd_por_pagina, $pagina, $filtro, $estab_perm, $estab, $data_ini, $data_fim) {

        $con = new _Conexao();

		$sql = "
			SELECT FIRST :QTD SKIP :PAG
				X.ID,
                X.REQUISICAO_ID,
                X.DATAHORA,
                X.CCUSTO,
                X.ESTABELECIMENTO_ID,
                X.LOCALIZACAO_ID,
                X.OPERACAO_CODIGO,
				X.REQUERENTE_ID,
				X.REQUERENTE_DESCRICAO,
				X.PRODUTO_ID,
				X.PRODUTO_DESCRICAO,
				X.UM,
				X.TAMANHO,
				X.TAMANHO_DESCRICAO,
                X.QUANTIDADE,
                X.USUARIO_ID,
                X.USUARIO_DESCRICAO,
                X.ESTOQUE_ID

            FROM (
                SELECT DISTINCT
                    FN_LPAD(E.ID, 5, 0) ID,
                    FN_LPAD(E.TABELA_ID, 5, 0) REQUISICAO_ID,
                    E.DATAHORA,
    
                    UPPER((SELECT FIRST 1
                        C.CCUSTO||' - '||
                        (SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO)
                     FROM TBCONSUMO_REQUISICAO C
                     WHERE C.ID = E.TABELA_ID))
                    CCUSTO,
    
                    (SELECT FIRST 1 FN_LPAD(C.ESTABELECIMENTO_ID, 2, 0) FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID) 
                    ESTABELECIMENTO_ID,
                    
                    FN_LPAD(E.LOCALIZACAO_ID, 2, 0) LOCALIZACAO_ID,
                    E.OPERACAO_CODIGO,
					
					C.USUARIO_ID REQUERENTE_ID,
					(SELECT IIF(NOME IS NULL, USUARIO, NOME) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) REQUERENTE_DESCRICAO,

					FN_LPAD(P.CODIGO, 5, '0') PRODUTO_ID,
					P.DESCRICAO PRODUTO_DESCRICAO,
					P.UNIDADEMEDIDA_SIGLA UM,
					
					C.TAMANHO,
					(SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE (
						(SELECT FIRST 1 P.GRADE_CODIGO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID),
						 C.TAMANHO)
					) TAMANHO_DESCRICAO,

                    FN_LPAD(E.QUANTIDADE, 5, 0) QUANTIDADE,
                    FN_LPAD(E.USUARIO_ID, 4, 0) USUARIO_ID,
                    
                    (SELECT IIF(U.NOME IS NULL, U.USUARIO, U.NOME) FROM TBUSUARIO U WHERE U.CODIGO = E.USUARIO_ID)
                    USUARIO_DESCRICAO,
                    
                    FN_LPAD(E.ESTOQUE_ID, 10, 0) ESTOQUE_ID
					
					FROM 
						TBESTOQUE_BAIXA E
						LEFT JOIN TBCONSUMO_REQUISICAO C ON C.ID = E.TABELA_ID
						LEFT JOIN TBPRODUTO P ON P.CODIGO = C.PRODUTO_ID
						LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = P.FAMILIA_CODIGO

					WHERE
						E.STATUSEXCLUSAO = '0'
					AND E.TIPO = 'R'
					AND CR.BAIXA = '1'
					AND CR.USUARIO_ID = :USU_ID
            ) X

            WHERE 
                (
                (CAST(X.ID AS VARCHAR(100)) LIKE :ID)
            OR  (CAST(
                    FN_LPAD(EXTRACT(DAY FROM X.DATAHORA), 2, '0')||'/'||FN_LPAD(EXTRACT(MONTH FROM X.DATAHORA), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATAHORA)
                 AS VARCHAR(100)) LIKE :DATAHORA)
            OR  (CAST(X.REQUISICAO_ID AS VARCHAR(100)) LIKE :REQ_ID)
            OR  (CAST(X.CCUSTO AS VARCHAR(100)) LIKE :CCUSTO)
            OR  (CAST(X.OPERACAO_CODIGO AS VARCHAR(100)) LIKE :OPERACAO)
            OR  (CAST(X.ESTOQUE_ID AS VARCHAR(100)) LIKE :ESTOQUE_ID)
            OR  (X.USUARIO_DESCRICAO LIKE :USUARIO)
                )

                /*@ESTAB*/
                /*@DATA*/

            ORDER BY 1 DESC
		";
		
		//Se nenhum estabelecimento for passado, serão listados todas as requisições
		// de acordo com o estabelecimento permitido pelo usuário.
		if($estab === '') {
			$est = "AND X.ESTABELECIMENTO_ID in($estab_perm)";
		}
		else {
			$est = "AND X.ESTABELECIMENTO_ID = ".$estab;
		}
		
		//Se nenhum período for passado, o período será de 3 meses
		if( empty($data_ini) ) {
			$periodo = "AND X.DATAHORA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND CURRENT_DATE";
		}
		else {
			$periodo = "AND X.DATAHORA BETWEEN '".$data_ini."' AND '".$data_fim."'";
		}
		
		$args = array(
			':QTD'			=> $qtd_por_pagina, 
			':PAG'			=> $pagina,
			':ID'			=> '%'.$filtro.'%',
			':DATAHORA'		=> '%'.$filtro.'%',
			':REQ_ID'		=> '%'.$filtro.'%',
			':CCUSTO'		=> '%'.$filtro.'%',
			':OPERACAO'		=> '%'.$filtro.'%',
			':ESTOQUE_ID'	=> '%'.$filtro.'%',
			':USUARIO'		=> '%'.$filtro.'%',
			'@ESTAB'		=> $est,
			'@DATA'			=> $periodo,
			':USU_ID'		=> Auth::user()->CODIGO
		);
		
		return $con->query($sql, $args);
    }
	
	/**
     * Filtrar lista de requisições de consumo.
     * Função chamada via Ajax.
     *
     * @param string $filtro
	 * @param string $estab_perm
	 * @param string $status
	 * @param string $estab
	 * @param string $data_ini
	 * @param string $data_fim
     * @return array
     */
    public static function filtrar($filtro, $estab_perm, $status, $estab, $data_ini, $data_fim) {

		$con = new _Conexao();

		$sql = "
			SELECT FIRST 30 DISTINCT
				X.ID,
				X.DATA,
				X.USUARIO_ID,
				X.USUARIO_DESCRICAO,
				X.ESTABELECIMENTO_ID,
				X.LOCALIZACAO_ID,
				X.LOCALIZACAO_PADRAO,
				X.DOCUMENTO,
				X.CCUSTO,
				X.CCUSTO_DESCRICAO,
				X.TURNO_ID,
				X.TURNO_DESCRICAO,
				X.OPERACAO_CODIGO,
				X.OPERACAO_REQUISICAO,
				X.FAMILIA_ID,
				X.PRODUTO_ID,
				X.PRODUTO_DESCRICAO,
				X.UM,
				X.QUANTIDADE,
				X.TAMANHO,
				X.TAMANHO_DESCRICAO,
				X.OBSERVACAO,
				X.SALDO,
				X.STATUS

			FROM (
				SELECT
					FN_LPAD(C.ID, 5, '0') ID,
                    C.DATAHORA AS DATA,
                    C.USUARIO_ID,
                    
                    (SELECT UPPER(IIF(NOME IS NULL, USUARIO, NOME)) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID)
                    USUARIO_DESCRICAO,
                    
                    FN_LPAD(C.ESTABELECIMENTO_ID, 2, '0') ESTABELECIMENTO_ID,
                    
                    (SELECT LIST(FN_LPAD(E.LOCALIZACAO_ID, 2, '0')) FROM TBESTOQUE_BAIXA E WHERE E.TABELA_ID = C.ID AND E.TIPO = 'R')
                    LOCALIZACAO_ID,
                    
                    (SELECT FIRST 1 FN_LPAD(P.LOCALIZACAO_CODIGO, 2, '0') FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID)
                    LOCALIZACAO_PADRAO,
                    
                    C.DOCUMENTO,
                    C.CCUSTO,
                    
                    (SELECT UPPER(CC.DESCRICAO) FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO)
                    CCUSTO_DESCRICAO,
                    
                    FN_LPAD(C.TURNO_ID, 2, '0') TURNO_ID,
                    
                    (SELECT T.DESCRICAO FROM TBTURNO T WHERE T.CODIGO = C.TURNO_ID) 
                    TURNO_DESCRICAO,
                    
                    (SELECT LIST(E.OPERACAO_CODIGO) FROM TBESTOQUE_BAIXA E WHERE E.TABELA_ID = C.ID AND E.TIPO = 'R')
                    OPERACAO_CODIGO,
                    
                    F.OPERACAO_REQUISICAO,
                    FN_LPAD(F.CODIGO, 2, '0') FAMILIA_ID,
                    
                    FN_LPAD(C.PRODUTO_ID, 5, '0') PRODUTO_ID,
                    
                    (SELECT UPPER(P.DESCRICAO) FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID)
                    PRODUTO_DESCRICAO,
                    P.UNIDADEMEDIDA_SIGLA UM,
                    
                    FN_LPAD(C.QUANTIDADE, 4, '0') QUANTIDADE,                    
                    C.TAMANHO,
                    
                    (SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE (
                        (SELECT FIRST 1 P.GRADE_CODIGO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID),
                         C.TAMANHO)
                    ) TAMANHO_DESCRICAO,
                    
                    C.OBSERVACAO,
                    C.SALDO,
                    C.STATUS
				FROM
					TBCONSUMO_REQUISICAO C
					LEFT JOIN TBPRODUTO P ON P.CODIGO = C.PRODUTO_ID
					LEFT JOIN TBFAMILIA F ON F.CODIGO = P.FAMILIA_CODIGO,
					(SELECT DISTINCT
						PX.USUARIO_ID,
						PX.FAMILIA
					FROM
						TBCONSUMO_REQ_PERMISSAO PX
					WHERE
						PX.USUARIO_ID = :USU_ID

					AND PX.BAIXA = '1')PERM
				WHERE
					C.STATUSEXCLUSAO = '0'
				AND PERM.FAMILIA = P.FAMILIA_CODIGO
					/*@DATA*/
			) X

			WHERE
				1 = 1
				and	(
					(CAST(X.ID AS VARCHAR(100)) LIKE :ID)
				OR  (X.USUARIO_DESCRICAO LIKE :USU_DESC)
                OR  (CAST(FN_LPAD(EXTRACT(DAY FROM X.DATA), 2, '0')||'/'||FN_LPAD(EXTRACT(MONTH FROM X.DATA), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATA)AS VARCHAR(100)) LIKE :DATA)
                OR  (CAST(X.PRODUTO_ID AS VARCHAR(100)) LIKE :PROD_ID)
                OR  (X.PRODUTO_DESCRICAO LIKE :PROD_DESC)
                OR  (CAST(X.CCUSTO AS VARCHAR(100)) LIKE :CCUSTO)
                OR  (X.CCUSTO_DESCRICAO LIKE :CCUSTO_DESC)
				OR  (X.OBSERVACAO LIKE :OBS)
					)
					/*@STATUS*/
					/*@ESTAB*/			
            
			ORDER BY 1 DESC
		";
		
		//--AND X.CCUSTO LIKE (SELECT CR.CCUSTO_REQUISICAO FROM TBCONSUMO_REQ_PERMISSAO CR WHERE CR.REQUISICAO = '1' AND CR.USUARIO_ID = :USU_ID)||'%'

		//Se nenhum estabelecimento for passado, serão listados todas as requisições
		// de acordo com o estabelecimento permitido pelo usuário.
		if($estab === '') {
			$est = "AND X.ESTABELECIMENTO_ID in($estab_perm)";
		}
		else {
			$est = "AND X.ESTABELECIMENTO_ID = ".$estab;
		}
		
		//Se nenhum período for passado, o período será de 3 meses
		if( empty($data_ini) ) {
			$periodo = "AND C.DATAHORA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND CURRENT_DATE";
		}
		else {
			$periodo = "AND C.DATAHORA BETWEEN '".$data_ini."' AND '".$data_fim."'";
		}
        
		$args = array(
			':USU_ID'		=> Auth::user()->CODIGO,
			'@DATA'			=> $periodo,
			':ID'			=> '%'.$filtro.'%',
			':USU_DESC'		=> '%'.$filtro.'%',
			':DATA'			=> '%'.$filtro.'%',
			':PROD_ID'		=> '%'.$filtro.'%',
			':PROD_DESC'	=> '%'.$filtro.'%',
			':CCUSTO'		=> '%'.$filtro.'%',
			':CCUSTO_DESC'	=> '%'.$filtro.'%',
			':OBS'			=> '%'.$filtro.'%',
			'@STATUS'		=> $status === '' ? '' : "AND X.STATUS = ".$status,
			'@ESTAB'		=> $est
		);
        
		$ret = $con->query($sql, $args);
         
         return $ret;
		
    }
	
	/**
     * Filtrar lista de baixas.
     * Função chamada via Ajax.
     *
     * @param string $filtro
	 * @param string $estab_perm
	 * @param string $estab
	 * @param string $data_ini
	 * @param string $data_fim
     * @return array
     */
    public static function filtrarBaixa($filtro, $estab_perm, $estab, $data_ini, $data_fim) {

		$con = new _Conexao();

		$sql = "
			SELECT FIRST 30 DISTINCT
                X.ID,
                X.REQUISICAO_ID,
                X.DATAHORA,
                X.CCUSTO,
                X.ESTABELECIMENTO_ID,
                X.LOCALIZACAO_ID,
                X.OPERACAO_CODIGO,
				X.REQUERENTE_ID,
				X.REQUERENTE_DESCRICAO,
				X.PRODUTO_ID,
				X.PRODUTO_DESCRICAO,
				X.UM,
				X.TAMANHO,
				X.TAMANHO_DESCRICAO,
                X.QUANTIDADE,
                X.USUARIO_ID,
                X.USUARIO_DESCRICAO,
                X.ESTOQUE_ID

            FROM (
                SELECT
                    FN_LPAD(E.ID, 5, 0) ID,
                    FN_LPAD(E.TABELA_ID, 5, 0) REQUISICAO_ID,
                    E.DATAHORA,
                    UPPER((SELECT FIRST 1
                        C.CCUSTO||' - '||
                        (SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO)
                     FROM TBCONSUMO_REQUISICAO C
                     WHERE C.ID = E.TABELA_ID))
                    CCUSTO,
    
                    (SELECT FIRST 1 FN_LPAD(C.ESTABELECIMENTO_ID, 2, 0) FROM TBCONSUMO_REQUISICAO C WHERE C.ID = E.TABELA_ID) 
                    ESTABELECIMENTO_ID,
                    
                    FN_LPAD(E.LOCALIZACAO_ID, 2, 0) LOCALIZACAO_ID,
                    E.OPERACAO_CODIGO,
					
					C.USUARIO_ID REQUERENTE_ID,
					(SELECT UPPER(IIF(NOME IS NULL, USUARIO, NOME)) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) REQUERENTE_DESCRICAO,

					FN_LPAD(P.CODIGO, 5, '0') PRODUTO_ID,
					UPPER(P.DESCRICAO) PRODUTO_DESCRICAO,
					P.UNIDADEMEDIDA_SIGLA UM,
					
					C.TAMANHO,
					(SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE (
						(SELECT FIRST 1 P.GRADE_CODIGO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID),
						 C.TAMANHO)
					) TAMANHO_DESCRICAO,
				
                    FN_LPAD(E.QUANTIDADE, 5, 0) QUANTIDADE,
                    FN_LPAD(E.USUARIO_ID, 4, 0) USUARIO_ID,
                    
                    (SELECT UPPER(IIF(U.NOME IS NULL, U.USUARIO, U.NOME)) FROM TBUSUARIO U WHERE U.CODIGO = E.USUARIO_ID)
                    USUARIO_DESCRICAO,
                    
                    FN_LPAD(E.ESTOQUE_ID, 10, 0) ESTOQUE_ID
                    
                FROM 
					TBESTOQUE_BAIXA E
					LEFT JOIN TBCONSUMO_REQUISICAO C ON C.ID = E.TABELA_ID
					LEFT JOIN TBPRODUTO P ON P.CODIGO = C.PRODUTO_ID
					LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = P.FAMILIA_CODIGO
					
                WHERE
					E.STATUSEXCLUSAO = '0'
                and (E.TIPO = 'R' or E.TIPO = 'C')
				AND CR.BAIXA = '1'
				AND CR.USUARIO_ID = :USU_ID
            ) X
            WHERE
            (
                    (CAST(X.ID AS VARCHAR(100)) LIKE :ID)
                OR  (CAST(
                        FN_LPAD(EXTRACT(DAY FROM X.DATAHORA), 2, '0')||'/'||FN_LPAD(EXTRACT(MONTH FROM X.DATAHORA), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATAHORA)
                     AS VARCHAR(100)) LIKE :DATAHORA)
                OR  (CAST(X.REQUISICAO_ID AS VARCHAR(100)) LIKE :REQ_ID)
                OR  (CAST(X.CCUSTO AS VARCHAR(100)) LIKE :CCUSTO)
                OR  (CAST(X.PRODUTO_ID AS VARCHAR(100)) LIKE :PROD_ID)
                OR  (X.PRODUTO_DESCRICAO LIKE :PROD_DESC)
                OR  (CAST(X.OPERACAO_CODIGO AS VARCHAR(100)) LIKE :OPERACAO)
                OR  (CAST(X.ESTOQUE_ID AS VARCHAR(100)) LIKE :ESTOQUE_ID)
                OR  (X.USUARIO_DESCRICAO LIKE :USUARIO)
				OR  (X.REQUERENTE_DESCRICAO LIKE :REQUERENTE)
            )

            /*@ESTAB*/
            /*@DATA*/

            ORDER BY 1 DESC
		";

		//Se nenhum estabelecimento for passado, serão listados todas as requisições
		// de acordo com o estabelecimento permitido pelo usuário.
		if($estab === '') {
			$est = "AND X.ESTABELECIMENTO_ID in($estab_perm)";
		}
		else {
			$est = "AND X.ESTABELECIMENTO_ID = ".$estab;
		}
		
		//Se nenhum período for passado, o período será de 3 meses
		if( empty($data_ini) ) {
			$periodo = "AND X.DATAHORA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND CURRENT_DATE";
		}
		else {
			$periodo = "AND X.DATAHORA BETWEEN '".$data_ini."' AND '".$data_fim."'";
		}
		

		$args = array(
			':ID'			=> '%'.$filtro.'%',
			':DATAHORA'		=> '%'.$filtro.'%',
			':REQ_ID'		=> '%'.$filtro.'%',
			':CCUSTO'		=> '%'.$filtro.'%',
			':PROD_ID'		=> '%'.$filtro.'%',
			':PROD_DESC'	=> '%'.$filtro.'%',
			':OPERACAO'		=> '%'.$filtro.'%',
			':ESTOQUE_ID'	=> '%'.$filtro.'%',
			':USUARIO'		=> '%'.$filtro.'%',
			':REQUERENTE'	=> '%'.$filtro.'%',
			'@ESTAB'		=> $est,
			'@DATA'			=> $periodo,
			':USU_ID'		=> Auth::user()->CODIGO
		);
        
		return $con->query($sql,$args);
		
    }
	
}
