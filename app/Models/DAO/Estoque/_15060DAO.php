<?php

namespace App\Models\DAO\Estoque;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _15060 - Consulta de Estoque
 */
class _15060DAO {

    /**
     * Função generica
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getChecList($dados) {
        return $dados;
    }

	/**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function listar($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = '';

            $args = array(
                ':id' => $dados->getId(),
            );

            $ret = $con->query($sql, $args);

            $con->commit();
			
			return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
    
    public static function selectEstoqueLocalizacao($param = [], _Conexao $con = null) {
        
        $sql = "
            SELECT
                ESTABELECIMENTO_ID,
                (SELECT FIRST 1 NOMEFANTASIA FROM TBESTABELECIMENTO WHERE CODIGO = ESTABELECIMENTO_ID) ESTABELECIMENTO_NOMEFANTASIA,
                LOCALIZACAO_ID,
                (SELECT FIRST 1 DESCRICAO FROM TBLOCALIZACAO WHERE CODIGO = X.LOCALIZACAO_ID) LOCALIZACAO_DESCRICAO,
                FAMILIA_ID,
                (SELECT FIRST 1 DESCRICAO FROM TBFAMILIA WHERE CODIGO = X.FAMILIA_ID) FAMILIA_DESCRICAO,      
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
                GRADE_ID,
                SALDO,  
                SALDO_ALOCADO,
                SALDO_DETERCEIRO,
                SALDO_EMTERCEIRO,
                SALDO - SALDO_ALOCADO SALDO_DISPONIVEL,
                UM

            FROM (
                SELECT

                    LPAD(E.ESTABELECIMENTO_CODIGO,3,'0') ESTABELECIMENTO_ID,
                    LPAD(E.LOCALIZACAO_CODIGO,2,'0') LOCALIZACAO_ID,
                    LPAD(P.FAMILIA_CODIGO,4,'0') FAMILIA_ID,      
                    LPAD(P.CODIGO,6,'0') PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
                    LPAD(P.GRADE_CODIGO,2,'0') GRADE_ID,
                    E.SALDO,
                    E.SALDO_DETERCEIRO,
                    E.SALDO_EMTERCEIRO,
                    COALESCE(
                    IIF(P.GRADE_CODIGO>0,
                        (SELECT SUM(EA.QUANTIDADE)
                           FROM TBESTOQUE_SALDO_ALOCACAO EA
                          WHERE EA.ESTABELECIMENTO_ID = E.ESTABELECIMENTO_CODIGO
                            AND EA.LOCALIZACAO_ID     = E.LOCALIZACAO_CODIGO
                            AND EA.PRODUTO_ID         = E.PRODUTO_CODIGO),
                        (SELECT EA.QUANTIDADE
                           FROM TBESTOQUE_SALDO_ALOCACAO EA
                          WHERE EA.ESTABELECIMENTO_ID = E.ESTABELECIMENTO_CODIGO
                            AND EA.LOCALIZACAO_ID     = E.LOCALIZACAO_CODIGO
                            AND EA.PRODUTO_ID         = E.PRODUTO_CODIGO
                            AND EA.TAMANHO            = 0)),0) SALDO_ALOCADO,
                    P.UNIDADEMEDIDA_SIGLA UM

                FROM
                    TBESTOQUE_SALDO E,
                    TBPRODUTO P

                WHERE
                    P.CODIGO = E.PRODUTO_CODIGO
                AND P.CODIGO = :PRODUTO_ID
                )X

            ORDER BY ESTABELECIMENTO_ID, LOCALIZACAO_ID
        ";
        
        $args = [
            'PRODUTO_ID' => $param->PRODUTO_ID
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectEstoqueGrade($param = [], _Conexao $con = null) {
        
        $sql = "
            SELECT
                GRADE_ID,
                TAMANHO,
                (SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE(GRADE_ID,X.TAMANHO)) TAMANHO_DESCRICAO,
                SALDO,
                SALDO_ALOCADO,
                UM,

                x.*

            FROM (
                SELECT
                    P.GRADE_CODIGO GRADE_ID,
                    S.TAMANHO,

                    CASE S.TAMANHO
                        WHEN 01 THEN E.T01_SALDO
                        WHEN 02 THEN E.T02_SALDO
                        WHEN 03 THEN E.T03_SALDO
                        WHEN 04 THEN E.T04_SALDO
                        WHEN 05 THEN E.T05_SALDO
                        WHEN 06 THEN E.T06_SALDO
                        WHEN 07 THEN E.T07_SALDO
                        WHEN 08 THEN E.T08_SALDO
                        WHEN 09 THEN E.T09_SALDO
                        WHEN 10 THEN E.T10_SALDO
                        WHEN 11 THEN E.T11_SALDO
                        WHEN 12 THEN E.T12_SALDO
                        WHEN 13 THEN E.T13_SALDO
                        WHEN 14 THEN E.T14_SALDO
                        WHEN 15 THEN E.T15_SALDO
                        WHEN 16 THEN E.T16_SALDO
                        WHEN 17 THEN E.T17_SALDO
                        WHEN 18 THEN E.T18_SALDO
                        WHEN 19 THEN E.T19_SALDO
                        WHEN 20 THEN E.T20_SALDO
                    ELSE 0 END SALDO,

                    COALESCE(
                    IIF(P.GRADE_CODIGO>0,
                        (SELECT FIRST 1 EA.QUANTIDADE
                           FROM TBESTOQUE_SALDO_ALOCACAO EA
                          WHERE EA.ESTABELECIMENTO_ID = E.ESTABELECIMENTO_CODIGO
                            AND EA.LOCALIZACAO_ID     = E.LOCALIZACAO_CODIGO
                            AND EA.PRODUTO_ID         = E.PRODUTO_CODIGO
                            AND EA.TAMANHO            = S.TAMANHO),0),0) SALDO_ALOCADO,

                    P.UNIDADEMEDIDA_SIGLA UM

                FROM
                    TBGRADE_SEQ S,
                    TBESTOQUE_SALDO E,
                    TBPRODUTO P

                WHERE
                    S.TAMANHO                > 0
                AND P.CODIGO                 = E.PRODUTO_CODIGO
                AND P.GRADE_CODIGO           > 0
                AND E.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO_ID
                AND P.CODIGO                 = :PRODUTO_ID
                AND E.LOCALIZACAO_CODIGO     = :LOCALIZACAO_ID
                )X

            WHERE
                SALDO         > 0
            OR  SALDO_ALOCADO > 0

            ORDER BY TAMANHO_DESCRICAO
        ";
        
        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'PRODUTO_ID'         => $param->PRODUTO_ID,
            'LOCALIZACAO_ID'     => $param->LOCALIZACAO_ID,
        ];
        
        return $con->query($sql,$args);
    }
		
    public static function selectEstoqueTransacao($param = [], _Conexao $con = null) {
        
        $sql = "
            SELECT
                ID,
                ESTABELECIMENTO_ID,
                LOCALIZACAO_ID,
                DOCUMENTO,
                TALAO_ID,
                DATA,
                DATAHORA,
                PRODUTO_ID,
                GRADE_ID,
                TAMANHO,
                (SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE(GRADE_ID,TAMANHO)) TAMANHO_DESCRICAO,
                IIF(QUANTIDADE_TAMANHO > 0, QUANTIDADE_TAMANHO, QUANTIDADE) QUANTIDADE,
                UM,
                OBSERVACAO,
                TIPO,
                TIPO_2,
                OPERACAO,
                OPERACAO_DESCRICAO,
                USUARIO_ID,
                USUARIO_DESCRICAO,
                CONFERENCIA,
                CONFERENCIA_DESCRICAO,
                TABELA_ORIGEM,
                TABELA,
                TABELA_NIVEL,
                TABELA_ID,
                TABELA_DESCRICAO,
                CCUSTO,
                CCUSTO_MASK,
                CCUSTO_DESCRICAO

            FROM (
                SELECT          
                    A.CONTROLE ID,
                    A.ESTABELECIMENTO_CODIGO ESTABELECIMENTO_ID,
                    A.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                    A.DOCUMENTO,
                    A.REMESSA_ITEM_CONTROLE TALAO_ID,
                    A.DATA,
                    A.DATAHORA,
                    A.PRODUTO_CODIGO PRODUTO_ID,
                    P.GRADE_CODIGO GRADE_ID,
                    S.TAMANHO,
                    CASE S.TAMANHO
                        WHEN 01 THEN A.T01
                        WHEN 02 THEN A.T02
                        WHEN 03 THEN A.T03
                        WHEN 04 THEN A.T04
                        WHEN 05 THEN A.T05
                        WHEN 06 THEN A.T06
                        WHEN 07 THEN A.T07
                        WHEN 08 THEN A.T08
                        WHEN 09 THEN A.T09
                        WHEN 10 THEN A.T10
                        WHEN 11 THEN A.T11
                        WHEN 12 THEN A.T12
                        WHEN 13 THEN A.T13
                        WHEN 14 THEN A.T14
                        WHEN 15 THEN A.T15
                        WHEN 16 THEN A.T16
                        WHEN 17 THEN A.T17
                        WHEN 18 THEN A.T18
                        WHEN 19 THEN A.T19
                        WHEN 20 THEN A.T20
                    ELSE 0 END QUANTIDADE_TAMANHO,

                    A.QUANTIDADE,
                    P.UNIDADEMEDIDA_SIGLA UM,
                    (CASE WHEN A.NFS_ITEM_CONTROLE > 0 THEN
                         (SELECT FIRST 1 NI.CFOP_CODIGO||'-'||N.EMPRESA_RAZAOSOCIAL
                            FROM TBNFS N, TBNFS_ITEM NI
                           WHERE N.CONTROLE = NI.NFS_CONTROLE
                             AND N.NUMERO_NOTAFISCAL = A.DOCUMENTO
                             AND NI.CONTROLE = A.NFS_ITEM_CONTROLE) ELSE

                     CASE WHEN A.NFE_ITEM_CONTROLE > 0 THEN
                         (SELECT FIRST 1 NI.CFOP_CODIGO||'-'||N.EMPRESA_RAZAOSOCIAL
                            FROM TBNFE N, TBNFE_ITEM NI
                           WHERE N.CONTROLE = NI.NFE_CONTROLE
                             AND N.NUMERO_NOTAFISCAL = A.DOCUMENTO
                             AND NI.CONTROLE = A.NFE_ITEM_CONTROLE) ELSE A.OBSERVACAO END END) OBSERVACAO,

                    A.TIPO,

                    (CASE WHEN A.TIPO <> 'B' THEN A.TIPO ELSE
                        COALESCE((SELECT FIRST 1 O.TIPO
                                    FROM TBOPERACAO O
                                   WHERE A.OPERACAO_CODIGO = O.CODIGO),'') END)
                     ||'/'||
                    (CASE WHEN A.TIPO  = 'B' THEN A.TIPO ELSE
                        COALESCE((SELECT FIRST 1 (CASE WHEN O.NOTAFISCAL = '1' THEN 'N' ELSE 'I' END)
                                    FROM TBOPERACAO O
                                   WHERE A.OPERACAO_CODIGO = O.CODIGO),'') END) TIPO_2,


                    A.OPERACAO_CODIGO OPERACAO,

                    (SELECT FIRST 1 O.DESCRICAO
                       FROM TBOPERACAO O
                      WHERE O.CODIGO = A.OPERACAO_CODIGO) OPERACAO_DESCRICAO,

                    LPAD(A.USUARIO_CODIGO,4,'0') USUARIO_ID,
                    UPPER((SELECT FIRST 1 IIF(COALESCE(U.NOME,'') = '', U.USUARIO, U.NOME)
                             FROM TBUSUARIO U
                            WHERE U.CODIGO = A.USUARIO_CODIGO)) USUARIO_DESCRICAO,

                    IIF(A.TIPO = 'E',TRIM(A.CONFERENCIA),NULL) CONFERENCIA,
                    IIF(A.TIPO = 'E',TRIM(CASE A.CONFERENCIA
                    WHEN '1' THEN 'À CONFERIR'
                    WHEN '2' THEN 'CONFERIDO'
                    ELSE '' END),NULL) CONFERENCIA_DESCRICAO,

                    (SELECT FIRST 1 G.DESCRICAO
                       FROM TBTABELA_GENERICA G
                      WHERE G.TABELA_ORIGEM        = 'TBESTOQUE_TRANSACAO_ITEM'
                        AND G.TABELA_DESTINO       = A.TABELA
                        AND G.TABELA_DESTINO_NIVEL = A.TABELA_NIVEL) TABELA_DESCRICAO,
                    A.TABELA,
                    A.TABELA_NIVEL,
                    FN_LPAD(A.TABELA_ID,8,0) TABELA_ID,
                    'TBESTOQUE_TRANSACAO_ITEM' TABELA_ORIGEM,
                    A.CENTRO_DE_CUSTO CCUSTO,
                    FN_CCUSTO_MASK(A.CENTRO_DE_CUSTO) CCUSTO_MASK,
                    FN_CCUSTO_DESCRICAO(A.CENTRO_DE_CUSTO) CCUSTO_DESCRICAO

                FROM
                    TBGRADE_SEQ S,
                    TBESTOQUE_TRANSACAO_ITEM A,
                    TBPRODUTO P

                WHERE
                    ((P.GRADE_CODIGO > 0 AND S.TAMANHO > 0) OR (P.GRADE_CODIGO = 0 AND S.TAMANHO = 0))      
                AND P.CODIGO                 = A.PRODUTO_CODIGO
                AND A.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO_ID
                AND P.CODIGO                 = :PRODUTO_ID
                AND A.LOCALIZACAO_CODIGO     = :LOCALIZACAO_ID   
                AND A.DATA             BETWEEN :DATA_1 AND :DATA_2
                )X

            WHERE
                ((GRADE_ID > 0 AND QUANTIDADE_TAMANHO > 0) OR (GRADE_ID = 0 AND QUANTIDADE > 0))

            ORDER BY
                DATA DESC, ID DESC
        ";
        
        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'PRODUTO_ID'         => $param->PRODUTO_ID,
            'LOCALIZACAO_ID'     => $param->LOCALIZACAO_ID,
            'DATA_1'             => date('Y-m-d',strtotime($param->DATA_1)),
            'DATA_2'             => date('Y-m-d',strtotime($param->DATA_2)),
        ];
        
        return $con->query($sql,$args);
    }
	
}