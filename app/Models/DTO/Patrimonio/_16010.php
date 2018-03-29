<?php

namespace App\Models\DTO\Patrimonio;

use App\Models\DAO\Patrimonio\_16010DAO;

/**
 * Objeto _16010 - Registro de Producao - Div. Bojo Colante
 */
class _16010
{
    
    public function __construct($con = null) 
    {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() 
    {
        
    }      
    

    
    public function selectImbolizado($param = null) 
    {
        
        $sql = "
            SELECT
                Y.*,
                NFE ||'-' || SERIE DOC_FISCAL,
                FN_MES_DESCRICAO(MES) MES_DESCRICAO,
                CASE STATUS
                WHEN 0 THEN 'NÃO CONCLUÍDO'
                WHEN 1 THEN 'CONCLUÍDO'
                END STATUS_DESCRICAO
            FROM (
                SELECT
                    E.EMPRESA_CODIGO EMPRESA_ID,
                    COALESCE(E.EMPRESA_RAZAOSOCIAL,'SEM NOTA FISCAL LANÇADA') EMPRESA_RAZAOSOCIAL,
                    E.NUMERO_NOTAFISCAL NFE,
                    FN_LPAD(E.SERIE,3,0) SERIE,
                    E.DATA_ENTRADA NFE_DATA_ENTRADA,
                    FN_DATE_TO_STRING(E.DATA_ENTRADA) NFE_DATA_ENTRADA_TEXT,
                    X.*,
       
                    FN_DESCRICAO('TBIMOBILIZADO_TIPO',X.TIPO_ID) TIPO_DESCRICAO,
                    EXTRACT(YEAR FROM X.DATA_DEPRECIACAO) ANO,
                    EXTRACT(MONTH FROM X.DATA_DEPRECIACAO) MES,
                    FORMATDATE(X.DATA_DEPRECIACAO) AS DESC_DATA_DEPRECIACAO,
                    FN_CCUSTO_MASK(X.CCUSTO) CCUSTO_MASK,
                    FN_CCUSTO_DESCRICAO(X.CCUSTO) CCUSTO_DESCRICAO,
                    (SELECT (SUM(II.QUANTIDADE * VALOR_UNITARIO) + SUM(II.QUANTIDADE * COALESCE(FRETE_UNITARIO,0)))
                       FROM TBIMOBILIZADO_ITEM II
                      WHERE II.IMOBILIZADO_ID = X.ID) VALOR,
    
                        COALESCE((
                            SELECT SUM(SALDO)
                              FROM TBIMOBILIZADO_DEPRECIACAO D,
                                   TBIMOBILIZADO_ITEM II
                             WHERE D.IMOBILIZADO_ITEM_ID = II.ID
                               AND II.IMOBILIZADO_ID = X.ID
                               AND D.DATA BETWEEN FN_START_OF_MONTH(CURRENT_DATE)
                                              AND FN_END_OF_MONTH(CURRENT_DATE)),0) SALDO, 
                        COALESCE((
                            SELECT SUM(D.VALOR)
                              FROM TBIMOBILIZADO_DEPRECIACAO D,
                                   TBIMOBILIZADO_ITEM II
                             WHERE II.ID = D.IMOBILIZADO_ITEM_ID
                               AND II.IMOBILIZADO_ID = X.ID
                               AND D.DATA = X.DATA_DEPRECIACAO),0) SALDO_MES,
    
                        COALESCE((
                            SELECT FIRST 1 1
                              FROM TBIMOBILIZADO_DEPRECIACAO D,
                                   TBIMOBILIZADO_ITEM II
                             WHERE D.IMOBILIZADO_ITEM_ID = II.ID
                               AND II.IMOBILIZADO_ID = X.ID),0) STATUS
                FROM (
                    SELECT DISTINCT
                        II.NFE_ID,
                        FN_LPAD(I.ID,4,0) ID,
                        I.DESCRICAO,
                        I.TIPO_ID,
                        I.TAXA,
                        I.VIDA_UTIL,
                        I.CCUSTO,
                        I.CCONTABIL,
                        I.OBSERVACAO,
                        I.DATAHORA,                                         
                        I.DATA_DEPRECIACAO

        
                      FROM TBIMOBILIZADO I,
                           TBIMOBILIZADO_ITEM II
                     WHERE TRUE
                     AND II.IMOBILIZADO_ID = I.ID
                     /*@ID*/
        
                     ORDER BY I.ID DESC
                     ) X
                     LEFT JOIN TBNFE E ON E.CONTROLE = X.NFE_ID
                     ) Y
        ";
  
        
        $args = [
            '@ID' =>  array_key_exists('ID', $param) ? "AND I.ID = $param->ID" : ''
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectImbolizadoItem($param = null) 
    {
        
        $sql = "
            SELECT
                   X.ID,
                   LPAD(X.IMOBILIZADO_ID,4,0) as IMOBILIZADO_ID,
                   X.PRODUTO_ID,
                   X.NFE_ID,
                   X.NFE_ITEM_ID,
                   X.QUANTIDADE,
                   X.VALOR_UNITARIO,
                   coalesce(X.FRETE_UNITARIO,0) as FRETE_UNITARIO,
                   X.ICMS_UNITARIO,
                   X.SERIE,
                   X.TIPO_ID,
                   X.TAXA,
                   X.VIDA_UTIL,
                   X.MODELO_ID,
                   X.OBSERVACAO,
                   X.STATUS,
                   X.DATAHORA,
                   X.DATA_DEPRECIACAO,
                   X.DATA_DEPRECIACAO_INICIO,
                   X.DATA_DEPRECIACAO_FIM,
                   X.PARCELAS,
                   X.PRODUTO_DESCRICAO,
                   X.NFE,
                   X.NFE_SEQUENCIA,
                   X.VALOR_UNITARIO_SEM_DESC,
                   X.VALOR_DESCONTO,
                   X.VALOR_ACRESCIMO,
                   X.PARCELA,
                   X.DATA_DEPRECIACAO_INICIO as DATA_ENTRADA,
                   IIF(X.DATA_DEPRECIACAO_FIM < CURRENT_DATE,0,X.VALOR_PARCELA) VALOR_PARCELA,
                   IIF(X.DATA_DEPRECIACAO_FIM < CURRENT_DATE,0,X.SALDO) SALDO,
                   FN_DATE_TO_STRING(X.DATA_DEPRECIACAO_INICIO) DATA_DEPRECIACAO_INICIO_TEXT,
                   FN_DATE_TO_STRING(X.DATA_DEPRECIACAO_FIM) DATA_DEPRECIACAO_FIM_TEXT
              FROM (
               SELECT
                    I.*,
                    (SELECT FIRST 1 DESCRICAO
                       FROM TBPRODUTO P
                      WHERE CODIGO = I.PRODUTO_ID) PRODUTO_DESCRICAO,

                    (SELECT FIRST 1 N.NUMERO_NOTAFISCAL
                       FROM TBNFE N
                      WHERE N.CONTROLE = I.NFE_ID) NFE,
                      NI.SEQUENCIA NFE_SEQUENCIA,

                    NI.VALOR_UNITARIO VALOR_UNITARIO_SEM_DESC,
                    ((NI.VALOR_DESCONTO / NI.QUANTIDADE) * I.QUANTIDADE) VALOR_DESCONTO,
                    ((NI.VALOR_ACRESCIMO / NI.QUANTIDADE) * I.QUANTIDADE) VALOR_ACRESCIMO,

                    (SELECT FIRST 1 PARCELA
                       FROM TBIMOBILIZADO_DEPRECIACAO D
                      WHERE D.IMOBILIZADO_ITEM_ID = I.ID
                        AND D.DATA BETWEEN FN_START_OF_MONTH(CURRENT_DATE)
                                       AND FN_END_OF_MONTH(CURRENT_DATE)) PARCELA,

                    (SELECT FIRST 1 D.VALOR
                       FROM TBIMOBILIZADO_DEPRECIACAO D
                      WHERE D.IMOBILIZADO_ITEM_ID = I.ID
                        AND D.DATA BETWEEN FN_START_OF_MONTH(CURRENT_DATE)
                                       AND FN_END_OF_MONTH(CURRENT_DATE)) VALOR_PARCELA,

                    COALESCE((
                        SELECT FIRST 1 SALDO
                          FROM TBIMOBILIZADO_DEPRECIACAO D
                         WHERE D.IMOBILIZADO_ITEM_ID = I.ID
                           AND D.DATA BETWEEN FN_START_OF_MONTH(CURRENT_DATE)
                                          AND FN_END_OF_MONTH(CURRENT_DATE)),I.VALOR_UNITARIO) SALDO


                FROM
                    TBIMOBILIZADO_ITEM I
                    LEFT JOIN TBNFE_ITEM NI ON NI.NFE_CONTROLE = I.NFE_ID AND NI.CONTROLE = I.NFE_ITEM_ID

                WHERE TRUE
                /*@IMOBILIZADO_ID*/
                ) X
             
        ";
        
      $imobilizado_id = array_key_exists('IMOBILIZADO_ID', $param) ? "AND I.IMOBILIZADO_ID =  $param->IMOBILIZADO_ID     " : '';
        
        $args = [
            '@IMOBILIZADO_ID' => $imobilizado_id
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectImbolizadoFrete($param = null) 
    {
        
        $sql = "
            SELECT
                I.IMOBILIZADO_ID,
                E2.CONTROLE NFE_ID,
                E2.NUMERO_NOTAFISCAL,
                E2.SERIE,
                E2.EMPRESA_CODIGO EMPRESA_ID,
                FN_MASK(E2.EMPRESA_CNPJ) EMPRESA_CNPJ_MASK,
                E2.EMPRESA_CNPJ,
                E2.EMPRESA_RAZAOSOCIAL,
                E2.DATA_ENTRADA,
                FN_DATE_TO_STRING(E2.DATA_ENTRADA) DATA_ENTRADA_TEXT,
                T.VALOR_TOTAL_NF VALOR_TOTAL

            FROM
                TBIMOBILIZADO_ITEM I,
                TBNFE E1,
                TBNF_REFERENCIADA R,
                TBNFE E2,
                TBNFE_TOTAIS T

            WHERE
                E1.CONTROLE = I.NFE_ID
            AND R.NFE_ID = E1.CONTROLE
            AND E2.CONTROLE = R.ID
            AND T.NFE_CONTROLE = E2.CONTROLE
            /*@IMOBILIZADO_ID*/
        ";
        
      $imobilizado_id = array_key_exists('IMOBILIZADO_ID', $param) ? "AND I.IMOBILIZADO_ID =  $param->IMOBILIZADO_ID     " : '';
        
        $args = [
            '@IMOBILIZADO_ID' => $imobilizado_id
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function selectImbolizadoParcela($param = null) 
    {
        
        $sql = "
            SELECT
                DATA,
                DATA_TEXT,
                SUM(VALOR) VALOR,
                SUM(SALDO) SALDO
            FROM (
                SELECT
                    II.ID,
                    DATA,
                    FN_DATE_TO_STRING(D.DATA) DATA_TEXT,
                    VALOR,
                    SALDO
                FROM
                    TBIMOBILIZADO I,
                    TBIMOBILIZADO_ITEM II,
                    TBIMOBILIZADO_DEPRECIACAO D
    
                WHERE
                    I.ID = :IMOBILIZADO_ID
                AND II.IMOBILIZADO_ID = I.ID
                AND D.IMOBILIZADO_ITEM_ID = II.ID
            ) X
            GROUP BY 1,2

            ORDER BY DATA
        ";
        
        $args = [
            'IMOBILIZADO_ID' => $param->IMOBILIZADO_ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectImbolizadoItemParcela($param = null) 
    {
        
        $sql = "
            SELECT
                D.*,
                FN_DATE_TO_STRING(D.DATA) DATA_TEXT

            FROM
                TBIMOBILIZADO_DEPRECIACAO D

            WHERE
                D.IMOBILIZADO_ITEM_ID = :IMOBILIZADO_ITEM_ID
        ";
        
        $args = [
            'IMOBILIZADO_ITEM_ID' => $param->IMOBILIZADO_ITEM_ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectImobilizadoTipo($param = null) 
    {
        
        $sql = "
            SELECT *
              FROM (SELECT X.*,
                           UPPER(X.DESCRICAO || ' - ' || VIDA_UTIL_TEXTO || ' - ' || TAXA_DEPRECIACAO_TEXTO) FILTRO
                      FROM (SELECT T.*,
                                   FN_FORMAT_NUMBER(T.VIDA_UTIL) || ' anos' VIDA_UTIL_TEXTO,
                                   FN_FORMAT_NUMBER(T.TAXA_DEPRECIACAO * 100,2) || '%' TAXA_DEPRECIACAO_TEXTO
                              FROM TBIMOBILIZADO_TIPO T) X ) Y
               WHERE TRUE
                  /*@FILTRO*/
                  /*@TIPO_ID*/
        ";

        $tipo_id = array_key_exists('TIPO_ID', $param) ? "AND ID = $param->TIPO_ID" : '';        
        $filtro  = array_key_exists('FILTRO', $param) ? "AND FILTRO LIKE UPPER('%'||REPLACE(CAST('$param->FILTRO' AS VARCHAR(500)),' ','%')||'%')" : '';        
        
        $args = [
            '@FILTRO'  => $filtro,
            '@TIPO_ID' => $tipo_id
        ];
        
        return $this->con->query($sql,$args);
    } 
        
    public function selectNfs($param1 = null) 
    {
        
        $sql = "
            SELECT
                X.*,
                FORMATDATE(X.DATA_EMISSAO) AS DATA_EMISSAO_TEXT

              FROM (
                SELECT
                    FN_LPAD(E.CONTROLE,6,0) NFS_ID,
                    E.NUMERO_NOTAFISCAL NFS,
                    E.SERIE,
                    E.EMPRESA_CODIGO EMPRESA_ID,
                    E.EMPRESA_RAZAOSOCIAL,
                    E.DATA_EMISSAO,
                    E.EMPRESA_CODIGO||'-'||E.SERIE AS FLAG

                FROM
                    TBNFS E

                WHERE TRUE
                /*@NFS*/
                )X
        ";
        
        $param = (object)[];

        if ( isset($param1->NFS) && $param1->NFS > -1 ) {
            $param->NFS = " = '$param1->NFS'";
        }   
        
        $nfe= array_key_exists('NFS', $param) ? "AND E.NUMERO_NOTAFISCAL $param->NFS" : '';
        
        
        $args = [
            '@NFS' => $nfe
        ];
        
        return $this->con->query($sql,$args);
    } 
        
    public function selectNfItem($param1 = null) 
    {
        
        $sql = "
            SELECT
                x.NFE_ITEM_ID,
                x.NFE_ID,
                x.NFE,
                x.SEQUENCIA,
                x.SEQUENCIA NFE_SEQUENCIA,
                x.PRODUTO_ID,
                x.PRODUTO_DESCRICAO,
                x.PRODUTO_UNIDADEMEDIDA,
                x.VALOR_UNITARIO,
                X.VALOR_UNITARIO_SEM_DESC,
                X.VALOR_DESCONTO,
                X.VALOR_ACRESCIMO,
                x.EMPRESA_CODIGO,
                x.QUANTIDADE,
                j.nomefantasia as EMPRESA,
                x.data_entrada,
                formatdate(x.data_entrada) as DESC_DATA_ENTRADA,
                x.serie,
                x.flag,
                x.CUSTO_FRETE_RATEADO as FRETE_UNITARIO,
                X.ICMS_UNITARIO

              FROM (
                SELECT
                    I.CONTROLE NFE_ITEM_ID,
                    FN_LPAD(I.NFE_CONTROLE,6,0) NFE_ID,
                    I.NUMERO_NOTAFISCAL NFE,
                    FN_LPAD(I.SEQUENCIA,3,0) SEQUENCIA,
                    FN_LPAD(I.PRODUTO_CODIGO,6,0) PRODUTO_ID,
                    I.PRODUTO_DESCRICAO,
                    I.PRODUTO_UNIDADEMEDIDA,
                    (I.VALOR_UNITARIO
                            -(coalesce(i.Valor_Desconto,0)    / nullif(i.quantidade,0))
                            -(coalesce(i.fiscal_valor_icms,0) / nullif(i.quantidade,0))
                            -(Coalesce((Select Sum(A.Valor_Imposto) From TbPatrimonio_Entrada A
                                         Where A.NFE_ID = I.Nfe_Controle and A.NFE_Item_Id = I.Controle),0) / nullif(i.quantidade,0))
                            +(coalesce(i.fiscal_valor_ipi,0)  / nullif(i.quantidade,0))
                            +(coalesce(i.Valor_Frete,0)       / nullif(i.quantidade,0))
                            +(coalesce(i.Valor_Acrescimo,0)   / nullif(i.quantidade,0))
                            +(coalesce(i.Valor_Icms_ST,0)     / nullif(i.quantidade,0))
                            +(coalesce(i.Valor_Seguro,0)      / nullif(i.quantidade,0))
                            +(coalesce(i.Valor_Pedagio,0)     / nullif(i.quantidade,0))
                    ) as VALOR_UNITARIO,
                    I.VALOR_UNITARIO VALOR_UNITARIO_SEM_DESC,
                    I.VALOR_DESCONTO,
                    I.VALOR_ACRESCIMO,
                    e.empresa_codigo,
                    e.data_entrada,
                    e.serie,
                    e.empresa_codigo||'-'||e.serie as FLAG,
                    i.CUSTO_FRETE_RATEADO,
                    (Coalesce((Select Sum(A.Valor_Imposto) 
                                 From TbPatrimonio_Entrada A
                                Where A.NFE_ID = I.Nfe_Controle 
                                  and A.NFE_Item_Id = I.Controle),0) / nullif(i.quantidade,0)) ICMS_UNITARIO,
                    nullif(i.quantidade,0) -
                    coalesce((select sum(k.quantidade) from tbimobilizado_item k where k.nfe_item_id = I.CONTROLE),0)  as QUANTIDADE

                FROM
                    TBNFE_ITEM I, tbnfe e

                WHERE TRUE
                /*@NFE*/
                and e.controle = i.nfe_controle

                order by i.sequencia

                )X, tbempresa j

            WHERE QUANTIDADE > 0
            and j.codigo = x.empresa_codigo
            order by  j.nomefantasia,x.empresa_codigo,x.serie
        ";
        
        $param = (object)[];

        if ( isset($param1->NFE) && $param1->NFE > -1 ) {
            $param->NFE = " = '$param1->NFE'";
        }   
        
        $nfe= array_key_exists('NFE', $param) ? "AND I.NUMERO_NOTAFISCAL $param->NFE" : '';
        
        
        $args = [
            '@NFE' => $nfe
        ];
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectDemonstratitvoDepreciacao($param = null) 
    {
        
        $sql = "
            SELECT
                CCUSTO,
                CCUSTO_MASK,
                CCUSTO_DESCRICAO,
                IMOBILIZADO_ID,
                IMOBILIZADO_DESCRICAO,
                TIPO_ID,
                TIPO_DESCRICAO,
                MES,
                ANO,
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
                PARCELAS,
                DEPRECIACAO_INICIO,
                DEPRECIACAO_FIM,
                PARCELA,
                MES_DESCRICAO,
                DEPRECIACAO_INICIO_TEXT,
                DEPRECIACAO_FIM_TEXT,
                SUM(VALOR) VALOR


            FROM (
                SELECT
                    X.*,
                    FN_MES_DESCRICAO(MES) MES_DESCRICAO,
                    FN_DATE_TO_STRING(DEPRECIACAO_INICIO) DEPRECIACAO_INICIO_TEXT,
                    FN_DATE_TO_STRING(DEPRECIACAO_FIM) DEPRECIACAO_FIM_TEXT
    
                FROM (
                    SELECT
                        FN_CCUSTO_MASK(I.CCUSTO) CCUSTO_MASK,
                        FN_CCUSTO_DESCRICAO(I.CCUSTO) CCUSTO_DESCRICAO,
                        I.CCUSTO,
                        I.ID IMOBILIZADO_ID,
                        I.DESCRICAO IMOBILIZADO_DESCRICAO,
                        D.VALOR,
                        I.TIPO_ID,
                        T.DESCRICAO TIPO_DESCRICAO,
                        EXTRACT(MONTH FROM D.DATA) MES,
                        EXTRACT(YEAR FROM D.DATA) ANO,
                        II.PRODUTO_ID,
                        (SELECT FIRST 1 DESCRICAO
                           FROM TBPRODUTO P
                          WHERE P.CODIGO = II.PRODUTO_ID) PRODUTO_DESCRICAO,
                        II.PARCELAS,
                        II.DATA_DEPRECIACAO_INICIO DEPRECIACAO_INICIO,
                        II.DATA_DEPRECIACAO_FIM DEPRECIACAO_FIM,
    
                        D.PARCELA
    
    
    
                    FROM
                        TBIMOBILIZADO_DEPRECIACAO D,
                        TBIMOBILIZADO_ITEM II,
                        TBIMOBILIZADO I,
                        TBIMOBILIZADO_TIPO T,
                        (SELECT FIRST 1
                                CAST(:MES_1 AS INTEGER) MES_1,
                                CAST(:MES_2 AS INTEGER) MES_2,
                                CAST(:ANO_1 AS INTEGER) ANO_1,
                                CAST(:ANO_2 AS INTEGER) ANO_2
                           FROM TBTURNO) A
    
                    WHERE TRUE
                    AND D.DATA BETWEEN '01.' ||  COALESCE(A.MES_1,EXTRACT(MONTH FROM CURRENT_DATE)) || '.' || COALESCE(A.ANO_1,EXTRACT(YEAR FROM CURRENT_DATE)) AND FN_END_OF_MONTH('01.' ||  COALESCE(A.MES_2,EXTRACT(MONTH FROM CURRENT_DATE)) || '.' || COALESCE(A.ANO_2,EXTRACT(YEAR FROM CURRENT_DATE)))
                    AND II.ID = D.IMOBILIZADO_ITEM_ID
                    AND I.ID = II.IMOBILIZADO_ID
                    AND T.ID = I.TIPO_ID
                    ) X
                ) Y

                GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
        ";
                
        $args = [
            'MES_1' => setDefValue($param->MES_1, null),
            'MES_2' => setDefValue($param->MES_2, null),
            'ANO_1' => setDefValue($param->ANO_1, null),
            'ANO_2' => setDefValue($param->ANO_2, null)
        ];
        
        return $this->con->query($sql,$args);
    } 
    
    public function insertImobilizado($param)
    {
        
        $sql =
        "
            UPDATE OR INSERT INTO TBIMOBILIZADO (
                ID,
                DESCRICAO,
                TIPO_ID,
                TAXA,
                VIDA_UTIL,
                CCUSTO,
                OBSERVACAO,
                DATA_DEPRECIACAO
            ) VALUES (
                :ID,
                :DESCRICAO,
                :TIPO_ID,
                :TIPO_TAXA,
                :TIPO_VIDA_UTIL,
                :CCUSTO,
                :OBSERVACAO,
                :DATA_DEPRECIACAO
            ) MATCHING (ID);
        ";
        $args = [
            'ID'               => $param->ID,
            'DESCRICAO'        => $param->DESCRICAO,
            'TIPO_ID'          => $param->TIPO_ID,
            'TIPO_TAXA'        => $param->TIPO_TAXA,
            'TIPO_VIDA_UTIL'   => $param->TIPO_VIDA_UTIL,
            'CCUSTO'           => $param->CCUSTO,
            'DATA_DEPRECIACAO' => date('Y-m-d',strtotime($param->DATA_DEPRECIACAO)),
            'OBSERVACAO'       => setDefValue($param->OBSERVACAO, '')
        ];
        
        
        return $this->con->query($sql, $args);
    }       
    
    public function insertImobilizadoItem($param)
    {
        
        $sql =
        "
            UPDATE OR INSERT INTO TBIMOBILIZADO_ITEM (
                ID,
                IMOBILIZADO_ID,
                PRODUTO_ID,
                VALOR_UNITARIO,
                NFE_ID,
                NFE_ITEM_ID,
                OBSERVACAO,
                STATUS,
                QUANTIDADE,
                DATA_DEPRECIACAO,
                FRETE_UNITARIO,
                ICMS_UNITARIO
            ) VALUES (
                :ID,
                :IMOBILIZADO_ID,
                :PRODUTO_ID,
                :VALOR_UNITARIO,
                :NFE_ID,
                :NFE_ITEM_ID,
                :OBSERVACAO,
                :STATUS,
                :QUANTIDADE,
                :DATA_DEPRECIACAO,
                :FRETE_UNITARIO,
                :ICMS_UNITARIO
            ) MATCHING (ID);
        ";

        $args = [                
            'ID'               => $param->ID,
            'QUANTIDADE'       => $param->QUANTIDADE,
            'IMOBILIZADO_ID'   => $param->IMOBILIZADO_ID,
            'PRODUTO_ID'       => $param->PRODUTO_ID,
            'VALOR_UNITARIO'   => $param->VALOR_UNITARIO,
            'FRETE_UNITARIO'   => $param->FRETE_UNITARIO,
            'ICMS_UNITARIO'    => setDefValue($param->ICMS_UNITARIO,0),
            'NFE_ID'           => setDefValue($param->NFE_ID     , NULL),
            'NFE_ITEM_ID'      => setDefValue($param->NFE_ITEM_ID, NULL),
            'OBSERVACAO'       => setDefValue($param->OBSERVACAO ,   ''),
            'STATUS'           => setDefValue($param->STATUS     ,  '1'),
            'DATA_DEPRECIACAO' => date('Y-m-d',strtotime($param->DATA_ENTRADA)),
        ];
        
        
        return $this->con->query($sql, $args);
    }       
    
    public function deleteImobilizado($param)
    {
        $sql =
        "
            DELETE FROM TBIMOBILIZADO WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    public function deleteImobilizadoItem($param)
    {
        $sql =
        "
            DELETE FROM TBIMOBILIZADO_ITEM WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    public function spiImobilizadoDepreciar($param)
    {
        $sql =
        "
            EXECUTE PROCEDURE SPI_IMOBILIZADO_DEPRECIAR(:IMOBILIZADO_ID,:IMOBILIZADO_ITEM_ID);
        ";
        
        $args = [
            'IMOBILIZADO_ID'      => setDefValue($param->IMOBILIZADO_ID     , null),
            'IMOBILIZADO_ITEM_ID' => setDefValue($param->IMOBILIZADO_ITEM_ID, null),
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    public function spiImobilizadoEncerrar($param)
    {
        $sql =
        "
            EXECUTE PROCEDURE SPU_IMOBILIZADO_BAIXAR(:NFS_ID,:IMOBILIZADO_ID,:IMOBILIZADO_ITEM_ID);
        ";
        
        $args = [
            'NFS_ID'              => setDefValue($param->NFS_ID     , null),
            'IMOBILIZADO_ID'      => setDefValue($param->IMOBILIZADO_ID     , null),
            'IMOBILIZADO_ITEM_ID' => setDefValue($param->IMOBILIZADO_ITEM_ID, null),
        ];
		
        return $this->con->query($sql,$args);
    }    
    
}