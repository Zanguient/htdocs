<?php

namespace App\Models\DAO\Estoque;

use App\Models\DTO\Estoque\_15010;
use App\Models\Conexao\_Conexao;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto 'Requisição de Consumo'.
 */
class _15010DAO {
	
	
	/**
     * Select da página inicial.
     * 
	 * @param string $estab_perm
     * @return array
     */
    public static function listar($estab_perm) {
		
		$con = new _Conexao();
			
		$sql = "
            SELECT FIRST 30 DISTINCT
                LPAD(C.ID, 5, '0') ID,
                C.DATAHORA AS DATA,
                C.USUARIO_ID,
                (SELECT IIF(NOME IS NULL, USUARIO, NOME) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) USUARIO_DESCRICAO,
                LPAD(C.ESTABELECIMENTO_ID, 2, '0') ESTABELECIMENTO_ID,
                C.CCUSTO,
                (SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO) CCUSTO_DESCRICAO,
                LPAD(C.TURNO_ID, 2, '0') TURNO_ID,
				(SELECT T.DESCRICAO FROM TBTURNO T WHERE T.CODIGO = C.TURNO_ID) TURNO_DESCRICAO,
                LPAD(C.PRODUTO_ID, 5, '0') PRODUTO_ID,
                (SELECT P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID) PRODUTO_DESCRICAO,
				P.UNIDADEMEDIDA_SIGLA UM,
                LPAD(C.QUANTIDADE, 4, '0') QUANTIDADE,
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
					LEFT JOIN TBFAMILIA F ON F.CODIGO = P.FAMILIA_CODIGO
					LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = F.CODIGO
				WHERE	
					C.STATUSEXCLUSAO = '0'
				AND CR.REQUISICAO = '1'
				AND CR.USUARIO_ID = :USU_ID
				AND C.CCUSTO LIKE CR.CCUSTO_REQUISICAO||'%'
				AND C.DATA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND CURRENT_DATE
				/*@ESTAB*/
				
            ORDER BY 1 DESC
		";
		
		$args = array(
			'@ESTAB'	=> "AND C.ESTABELECIMENTO_ID in($estab_perm)",
			':USU_ID'	=> Auth::user()->CODIGO
		);
		
		return $con->query($sql, $args);
    }
    
	/**
     * Gerar id do objeto.
     * 
     * @return integer
     */
    public static function gerarId() {
        
		$con = new _Conexao();
		
		$sql = 'select gen_id(GTBCONSUMO_REQUISICAO, 1) ID from RDB$DATABASE';
		
		return $con->query($sql)[0];
		
    }
	
	/**
     * Inserir dados do objeto na base de dados.
     * 
     * @param _15010 $obj
	 * @return array
     */
    public static function gravar(_15010 $obj) {

		$con = new _Conexao();
		
		try {
			
			$i = 0;
			foreach($obj->getProdutoId() as $prod_id) {
			
				$sql = "
					INSERT INTO TBCONSUMO_REQUISICAO (
						USUARIO_ID,
						ESTABELECIMENTO_ID,
						CCUSTO,
						TURNO_ID,
						PRODUTO_ID,
						QUANTIDADE,
						TAMANHO,
						OBSERVACAO,
						SALDO,
						FLAG,
						OPERACAO,
						LOCALIZACAO
					)
					VALUES (
						:USU_ID,
						:ESTAB,
						:CCUSTO,
						:TURNO,
						:PROD_ID,
						:QTD,
						:TAMANHO,
						:OBS,
						:SALDO,
						:FLAG,
						:OPERACAO,
						:LOCALIZACAO
					)
				";

				$args = array(
					':USU_ID'       => $obj->getUsuarioId(),
					':ESTAB'        => $obj->getEstabelecimentoId(),
					':CCUSTO'       => $obj->getCcusto(),
					':TURNO'        => $obj->getTurno(),
					':PROD_ID'      => $prod_id,
					':QTD'          => $obj->getQuantidade()[$i],
					':TAMANHO'      => $obj->getTamanho()[$i],
					':OBS'          => $obj->getObservacao()[$i],
					':SALDO'        => $obj->getSaldo()[$i],
					':FLAG'         => $obj->getFlag(),
					':OPERACAO'     => $obj->getOperacao(),
					':LOCALIZACAO'  => $obj->getLocalizacao()
				);

				$con->execute($sql, $args);
				
				$i++;
			}
			
			$con->commit();
			
		} catch(Exception $e) {
			$con->rollback();
			throw $e;
		}
		
    }
	
	/**
     * Retorna dados do objeto na base de dados.
     * 
     * @param int $id
     * @return array
     */
    public static function exibir($id) {
       
		$con = new _Conexao();
		
		$sql1 = "
            SELECT FIRST 1
                LPAD(C.ID, 5, '0') ID,
                C.DATA,
                C.USUARIO_ID,
                (SELECT IIF(NOME IS NULL, USUARIO, NOME) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) USUARIO_DESCRICAO,
                LPAD(C.ESTABELECIMENTO_ID, 2, '0') ESTABELECIMENTO_ID,
                C.CCUSTO,
                (SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO) CCUSTO_DESCRICAO,
                LPAD(C.TURNO_ID, 2, '0') TURNO_ID,
				(SELECT T.DESCRICAO FROM TBTURNO T WHERE T.CODIGO = C.TURNO_ID) TURNO_DESCRICAO,
                LPAD(C.PRODUTO_ID, 5, '0') PRODUTO_ID,
                (SELECT P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID) PRODUTO_DESCRICAO,
				(SELECT FIRST 1 E.SALDO FROM TBESTOQUE_SALDO E WHERE E.PRODUTO_CODIGO = C.PRODUTO_ID) PRODUTO_SALDO,
                LPAD(C.QUANTIDADE, 4, '0') QUANTIDADE,
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
					LEFT JOIN TBFAMILIA F ON F.CODIGO = P.FAMILIA_CODIGO
					LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = F.CODIGO
					
				WHERE
					C.STATUSEXCLUSAO = '0'
				AND	C.ID = :ID
				AND CR.REQUISICAO = '1'
				AND CR.USUARIO_ID = :USU_ID
				AND C.CCUSTO LIKE CR.CCUSTO_REQUISICAO||'%'
		";

		$args1 = array(
			':ID'		=> $id,
			':USU_ID'	=> Auth::user()->CODIGO
		);

		return array(
			'dado'	=> $con->query($sql1, $args1)[0]
		);
	
    }
	
	/**
     * Alterar dados do objeto na base de dados.
     * 
     * @param _15010 $obj
     */
    public static function alterar(_15010 $obj) {
        
        $con = new _Conexao();

        try {
			
            $sql = "
				UPDATE TBCONSUMO_REQUISICAO
				SET
					USUARIO_ID			= :USU_ID,
					ESTABELECIMENTO_ID	= :ESTAB,
					CCUSTO				= :CCUSTO,
					TURNO_ID			= :TURNO,
					PRODUTO_ID			= :PROD_ID,
					QUANTIDADE			= :QTD,
					TAMANHO				= :TAMANHO,
					OBSERVACAO			= :OBS,
					SALDO				= :SALDO
				WHERE
					ID = :ID
			";
			
			$args = array(
				':USU_ID'	=> $obj->getUsuarioId(),
				':ESTAB'	=> $obj->getEstabelecimentoId(),
				':CCUSTO'	=> $obj->getCcusto(),
				':TURNO'	=> $obj->getTurno(),
				':PROD_ID'	=> $obj->getProdutoId()[0],
				':QTD'		=> $obj->getQuantidade()[0],
				':TAMANHO'	=> $obj->getTamanho()[0],
				':OBS'		=> $obj->getObservacao()[0],
				':SALDO'	=> $obj->getSaldo()[0],
				':ID'		=> $obj->getId()
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
				UPDATE TBCONSUMO_REQUISICAO
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
     * Encerrar/desencerrar requisição.
     * 
     * @param _15010 $obj
     */
    public static function encerrar(_15010 $obj) {
        
        $con = new _Conexao();

        try {
			
            $sql = "
				UPDATE TBCONSUMO_REQUISICAO
				SET
					STATUS = :STATUS
				WHERE
					ID = :ID
			";
			
			$args = array(
				':STATUS'	=> $obj->getStatus(),
				':ID'		=> $obj->getId()
			);

			$con->execute($sql, $args);
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
				LPAD(X.ID, 5, '0') ID,
				X.DATA,
				X.USUARIO_ID,
				X.USUARIO_DESCRICAO,
				X.ESTABELECIMENTO_ID,
				X.CCUSTO,
				X.CCUSTO_DESCRICAO,
				X.TURNO_ID,
				X.TURNO_DESCRICAO,
				LPAD(X.PRODUTO_ID, 5, '0') PRODUTO_ID,
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
					C.ID,
					C.DATAHORA AS DATA,
					C.USUARIO_ID,
					(SELECT IIF(NOME IS NULL, USUARIO, NOME) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) USUARIO_DESCRICAO,
					LPAD(C.ESTABELECIMENTO_ID, 2, '0') ESTABELECIMENTO_ID,
					C.CCUSTO,
					(SELECT CC.DESCRICAO FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO) CCUSTO_DESCRICAO,
					LPAD(C.TURNO_ID, 2, '0') TURNO_ID,
					(SELECT T.DESCRICAO FROM TBTURNO T WHERE T.CODIGO = C.TURNO_ID) TURNO_DESCRICAO,
					C.PRODUTO_ID,
					(SELECT P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID) PRODUTO_DESCRICAO,
					P.UNIDADEMEDIDA_SIGLA UM,
					LPAD(C.QUANTIDADE, 4, '0') QUANTIDADE,
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
						LEFT JOIN TBFAMILIA F ON F.CODIGO = P.FAMILIA_CODIGO
						LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = F.CODIGO

					WHERE
						C.STATUSEXCLUSAO = '0'
						AND CR.REQUISICAO = '1'
						AND CR.USUARIO_ID = :USU_ID
						AND C.CCUSTO LIKE CR.CCUSTO_REQUISICAO||'%'
			) X

			WHERE 
				(
				(X.ID LIKE :ID)
			OR  (CAST(
                    LPAD(EXTRACT(DAY FROM X.DATA), 2, '0')||'/'||LPAD(EXTRACT(MONTH FROM X.DATA), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATA)
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
		
		//Se nenhum estabelecimento for passado, serão listados todas as requisições
		// de acordo com o estabelecimento permitido pelo usuário.
		if( $estab === '' ) {
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
			SELECT FIRST 30
				LPAD(X.ID, 5, '0') ID,
				X.DATA,
				X.USUARIO_ID,
				X.USUARIO_DESCRICAO,
				X.ESTABELECIMENTO_ID,
				X.CCUSTO,
				X.CCUSTO_DESCRICAO,
				X.TURNO_ID,
				X.TURNO_DESCRICAO,
				LPAD(X.PRODUTO_ID, 5, '0') PRODUTO_ID,
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
					C.ID,
                    C.DATAHORA AS DATA,
                    C.USUARIO_ID,
                    (SELECT UPPER(IIF(NOME IS NULL, USUARIO, NOME)) FROM TBUSUARIO U WHERE U.CODIGO = C.USUARIO_ID) USUARIO_DESCRICAO,
                    LPAD(C.ESTABELECIMENTO_ID, 2, '0') ESTABELECIMENTO_ID,
                    C.CCUSTO,
                    (SELECT UPPER(CC.DESCRICAO) FROM TBCENTRO_DE_CUSTO CC WHERE CC.CODIGO = C.CCUSTO) CCUSTO_DESCRICAO,
                    LPAD(C.TURNO_ID, 2, '0') TURNO_ID,
                    (SELECT UPPER(T.DESCRICAO) FROM TBTURNO T WHERE T.CODIGO = C.TURNO_ID) TURNO_DESCRICAO,
                    C.PRODUTO_ID,
                    (SELECT UPPER(P.DESCRICAO) FROM TBPRODUTO P WHERE P.CODIGO = C.PRODUTO_ID) PRODUTO_DESCRICAO,
                    P.UNIDADEMEDIDA_SIGLA UM,
                    LPAD(C.QUANTIDADE, 4, '0') QUANTIDADE,
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
						LEFT JOIN TBFAMILIA F ON F.CODIGO = P.FAMILIA_CODIGO
						LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = F.CODIGO

					WHERE
						C.STATUSEXCLUSAO = '0'
					AND CR.REQUISICAO = '1'
					AND CR.USUARIO_ID = :USU_ID
					AND C.CCUSTO LIKE CR.CCUSTO_REQUISICAO||'%'
			) X

			WHERE 
				(
				(X.ID LIKE :ID)
			OR  (X.USUARIO_DESCRICAO LIKE :USU_DESC)
			OR  (CAST(
                    LPAD(EXTRACT(DAY FROM X.DATA), 2, '0')||'/'||LPAD(EXTRACT(MONTH FROM X.DATA), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATA)
                 AS VARCHAR(100)) LIKE :DATA)
			OR  (X.PRODUTO_ID LIKE :PROD_ID)
			OR  (X.PRODUTO_DESCRICAO LIKE :PROD_DESC)
			OR  (CAST(X.CCUSTO AS VARCHAR(100)) LIKE :CCUSTO)
			OR  (X.CCUSTO_DESCRICAO LIKE :CCUSTO_DESC)
			OR  (X.OBSERVACAO LIKE :OBS)
				)
				/*@STATUS*/
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
			$periodo = "AND X.DATA BETWEEN DATEADD(-1 MONTH TO CURRENT_DATE) AND CURRENT_DATE";
		}
		else {
			$periodo = "AND X.DATA BETWEEN '".$data_ini."' AND '".$data_fim."'";
		}
		
		$args = array(
			':ID'			=> '%'.$filtro.'%',
			':USU_DESC'		=> '%'.$filtro.'%',
			':DATA'			=> '%'.$filtro.'%',
			':PROD_ID'		=> '%'.$filtro.'%',
			':PROD_DESC'	=> '%'.$filtro.'%',
			':CCUSTO'		=> '%'.$filtro.'%',
			':CCUSTO_DESC'	=> '%'.$filtro.'%',
			':OBS'			=> '%'.$filtro.'%',
			'@STATUS'		=> $status === '' ? '' : "AND X.STATUS = ".$status,
			'@ESTAB'		=> $est,
			'@DATA'			=> $periodo,
			':USU_ID'		=> Auth::user()->CODIGO
		);

		return $con->query($sql, $args);
		
    }	
    
    public static function selectPermissao($tipo_permissao,$valor,$usuario_id) {
        $con = new _Conexao;
        
        $valor      = $valor      ? $valor      : '1';
        $usuario_id = $usuario_id ? $usuario_id : Auth::user()->CODIGO;
        
        $sql = '
            SELECT
                DISTINCT
                LPAD(F.CODIGO,3,\'0\') FAMILIA_ID,
                F.DESCRICAO FAMILIA_DESCRICAO

            FROM
                TBCONSUMO_REQ_PERMISSAO P,
                TBFAMILIA F,
                (SELECT FIRST 1
                    CAST(:VALOR          AS CHAR(1)) VALOR,
                    CAST(:TIPO_PERMISSAO AS CHAR(1)) TIPO_PERMISSAO
                FROM RDB$DATABASE) V

            WHERE
                P.USUARIO_ID = :USUARIO_ID
            AND F.CODIGO = P.FAMILIA
            AND IIF(V.TIPO_PERMISSAO = \'1\', P.REQUISICAO = V.VALOR,        
                IIF(V.TIPO_PERMISSAO = \'2\', P.BAIXA      = V.VALOR, NULL)) 
        ';
        
		$args = array(
			':TIPO_PERMISSAO'	=> $tipo_permissao,
			':VALOR'			=> $valor,
			':USUARIO_ID'		=> $usuario_id
		);

		return $con->query($sql, $args);
    }
	
}
