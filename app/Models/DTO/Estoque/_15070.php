<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15070DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _15070
{
    public function __construct($con) {
        $this->con = $con;
    }  
 

    public function selectConsumo($param1) {       
        
        $sql =
        "
            SELECT
                *
            from(
                    SELECT  
                            CONSUMO_ID,
                            REMESSA,
                            REMESSA_ID,
                            REMESSA_ESTABELECIMENTO_ID,
                            REMESSA_DATA,
                            REMESSA_DATA_TEXT,
                            REMESSA_FAMILIA_ID,
                            REMESSA_FAMILIA_DESCRICAO,
                            REMESSA_TALAO_ID,
                            MODELO_ID,
                            MODELO_DESCRICAO,
                            COR_ID,
                            COR_DESCRICAO,
                            GRADE_ID,
                            TAMANHO,
                            TAMANHO_DESCRICAO,
                            QUANTIDADE_TALAO,
                            UM_TALAO,
                            CONSUMO_FAMILIA_ID,
                            CONSUMO_FAMILIA_DESCRICAO,
                            CONSUMO_PRODUTO_ID,
                            CONSUMO_PRODUTO_DESCRICAO,
                            CONSUMO_GRADE_ID,
                            CONSUMO_TAMANHO,
                            CONSUMO_TAMANHO_DESCRICAO,
                            QUANTIDADE,
                            QUANTIDADE_CONSUMO,
                            QUANTIDADE - QUANTIDADE_CONSUMO QUANTIDADE_SALDO,
                            QUANTIDADE_ESTOQUE,
                            CONSUMO_UM,
                            CONSUMO_STATUS,
                            CONSUMO_STATUS_DESCRICAO,
                            CONSUMO_LOCALIZACAO_ID,
                            CONSUMO_LOCALIZACAO_ID_PROCESSO,
                            GP_ID,
                            UP_CCUSTO,
                            PERFIL_UP,

                            coalesce((select first 1 a.habilita_kanban from vwestoque_minimo_tamanho A
                                    where a.produto_id   = x.CONSUMO_PRODUTO_ID
                                    and a.tamanho        = x.CONSUMO_TAMANHO
                                    and a.localizacao_id = x.LOCALIZACAO_REGRA
                            ),0) as habilita_kanban


                        FROM (
                            SELECT
                                C.ID CONSUMO_ID,
                                R.REMESSA,
                                C.REMESSA_ID,
                                R.ESTABELECIMENTO_ID REMESSA_ESTABELECIMENTO_ID,
                                R.DATA REMESSA_DATA,
                                FN_DATE_TO_STRING(R.DATA) REMESSA_DATA_TEXT,
                                FN_LPAD(R.FAMILIA_ID,3,0) REMESSA_FAMILIA_ID,         
                                (SELECT FIRST 1 F.DESCRICAO FROM TBFAMILIA F WHERE F.CODIGO = R.FAMILIA_ID) REMESSA_FAMILIA_DESCRICAO,
                                FN_LPAD(C.REMESSA_TALAO_ID,4,0) REMESSA_TALAO_ID,
                                T.MODELO_ID,
                                T.id as REMESSA_TALAO_DETALHE_ID,
                                (SELECT FIRST 1 DESCRICAO FROM TBMODELO WHERE CODIGO = T.MODELO_ID) MODELO_DESCRICAO,
                                P2.COR_CODIGO COR_ID,
                                (SELECT FIRST 1 DESCRICAO FROM TBCOR WHERE CODIGO = P2.COR_CODIGO) COR_DESCRICAO,
                                P2.GRADE_CODIGO GRADE_ID,
                                T.TAMANHO,
                                FN_TAMANHO_GRADE(P2.GRADE_CODIGO,T.TAMANHO) TAMANHO_DESCRICAO,
                                T.QUANTIDADE QUANTIDADE_TALAO,
                                P2.UNIDADEMEDIDA_SIGLA UM_TALAO,
                                FN_LPAD(P.FAMILIA_CODIGO,3,0) CONSUMO_FAMILIA_ID,
                                (SELECT FIRST 1 F.DESCRICAO FROM TBFAMILIA F WHERE F.CODIGO = P.FAMILIA_CODIGO) CONSUMO_FAMILIA_DESCRICAO,
                                FN_LPAD(C.PRODUTO_ID,6,0)CONSUMO_PRODUTO_ID,
                                P.DESCRICAO CONSUMO_PRODUTO_DESCRICAO,
                                P.GRADE_CODIGO CONSUMO_GRADE_ID,
                                C.TAMANHO CONSUMO_TAMANHO,
                                COALESCE(FN_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO),'')CONSUMO_TAMANHO_DESCRICAO,
                                C.QUANTIDADE,
                                COALESCE((
                                    SELECT SUM(I.QUANTIDADE)
                                      FROM TBESTOQUE_TRANSACAO_ITEM I
                                     WHERE I.TABELA = 'TBREMESSA_CONSUMO'
                                       AND I.TABELA_ID = C.ID
                                       AND I.TABELA_NIVEL = 0
                                       AND I.TIPO = 'S'),0) QUANTIDADE_CONSUMO,

                                COALESCE((
                                    SELECT FIRST 1 CAST(
                                       IIF(C.TAMANHO=0,S.SALDO,
                                       IIF(C.TAMANHO=1 ,S.T01_SALDO,IIF(C.TAMANHO=2 ,S.T02_SALDO,
                                       IIF(C.TAMANHO=3 ,S.T03_SALDO,IIF(C.TAMANHO=4 ,S.T04_SALDO,
                                       IIF(C.TAMANHO=5 ,S.T05_SALDO,IIF(C.TAMANHO=6 ,S.T06_SALDO,
                                       IIF(C.TAMANHO=7 ,S.T07_SALDO,IIF(C.TAMANHO=8 ,S.T08_SALDO,
                                       IIF(C.TAMANHO=9 ,S.T09_SALDO,IIF(C.TAMANHO=10,S.T10_SALDO,
                                       IIF(C.TAMANHO=11,S.T11_SALDO,IIF(C.TAMANHO=12,S.T12_SALDO,
                                       IIF(C.TAMANHO=13,S.T13_SALDO,IIF(C.TAMANHO=14,S.T14_SALDO,
                                       IIF(C.TAMANHO=15,S.T15_SALDO,IIF(C.TAMANHO=16,S.T16_SALDO,
                                       IIF(C.TAMANHO=17,S.T17_SALDO,IIF(C.TAMANHO=18,S.T18_SALDO,
                                       IIF(C.TAMANHO=19,S.T19_SALDO,IIF(C.TAMANHO=20,S.T20_SALDO,
                                       0.00000))))))))))))))))))))) AS NUMERIC(15,4))
                                      FROM TBESTOQUE_SALDO S
                                     WHERE S.ESTABELECIMENTO_CODIGO = C.ESTABELECIMENTO_ID
                                       AND S.LOCALIZACAO_CODIGO     = P.LOCALIZACAO_CODIGO--C.LOCALIZACAO_ID
                                       AND S.PRODUTO_CODIGO         = C.PRODUTO_ID
                                       ),0) QUANTIDADE_ESTOQUE,

                                P.UNIDADEMEDIDA_SIGLA CONSUMO_UM,
                                TRIM(C.STATUS) CONSUMO_STATUS,
                                TRIM(CASE C.STATUS
                                WHEN '1' THEN 'CONSUMO BAIXADO'
                                WHEN '0' THEN 'CONSUMO PENDENTE'
                                ELSE 'NAO DENFINIDO' END) CONSUMO_STATUS_DESCRICAO,
                                P.LOCALIZACAO_CODIGO CONSUMO_LOCALIZACAO_ID, --C.LOCALIZACAO_ID CONSUMO_LOCALIZACAO_ID,
                                COALESCE((
                                    SELECT FIRST 1 F.LOCALIZACAO_PROCESSO
                                      FROM TBPRODUTO P, TBFAMILIA_FICHA F
                                     WHERE F.FAMILIA_CODIGO         = P.FAMILIA_CODIGO
                                       AND F.ESTABELECIMENTO_CODIGO = C.ESTABELECIMENTO_ID
                                       AND P.CODIGO                 = C.PRODUTO_ID),0) CONSUMO_LOCALIZACAO_ID_PROCESSO,
                                U.CCUSTO UP_CCUSTO,
                                T.GP_ID,
                                U.PERFIL PERFIL_UP,
                                coalesce(FN_LOCALIZACAO_CODIGO2(3,c.produto_id,p.familia_codigo, C.LOCALIZACAO_ID , t.id, g.id,t.up_id,'','S'),0) as LOCALIZACAO_REGRA

                            FROM
                                VWREMESSA_CONSUMO C
                                LEFT JOIN VWREMESSA R        ON R.REMESSA_ID     = C.REMESSA_ID
                                LEFT JOIN VWREMESSA_TALAO T  ON T.REMESSA_ID     = C.REMESSA_ID AND T.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                                LEFT JOIN TBGP G             ON G.ID             = T.GP_ID
                                LEFT JOIN TBUP U             ON U.ID             = T.UP_ID
                                LEFT JOIN TBPRODUTO P2       ON P2.CODIGO        = T.PRODUTO_ID,
                                TBPRODUTO P,
                                TBESTOQUE_TRANSACAO_REGRA TR
                
                            WHERE TRUE
                            AND P.CODIGO = C.PRODUTO_ID
                            --AND C.STATUS = '0'
                            AND C.COMPONENTE = '0'
                            AND C.QUANTIDADE > 0
                            AND TR.TIPO = 4
                            AND TR.FAMILIA_ID = P.FAMILIA_CODIGO

                            --and coalesce(a.habilita_kanban,0) = 0
                            ) X
                    ) y
            WHERE TRUE       
            AND habilita_kanban = 0       
            /*@REMESSA*/
            /*@REMESSA_ID*/
            /*@REMESSA_FAMILIA_ID*/
            /*@FAMILIA_ID*/
        ";
        
            
        $param = (object)[];


        if ( isset($param1->REMESSA) && $param1->REMESSA != '' ) {
            $param->REMESSA = " = '$param1->REMESSA'";
        }

        if ( isset($param1->REMESSA_ID) && $param1->REMESSA_ID > 0 ) {
            $param->REMESSA_ID = " = $param1->REMESSA_ID";
        }

        if ( isset($param1->REMESSA_FAMILIA_ID) && $param1->REMESSA_FAMILIA_ID > 0 ) {
            $param->REMESSA_FAMILIA_ID = " = $param1->REMESSA_FAMILIA_ID";
        }

        if ( isset($param1->FAMILIA_ID) && $param1->FAMILIA_ID > 0 ) {
            $param->FAMILIA_ID = " = $param1->FAMILIA_ID";
        }         
        
        $first              = array_key_exists('FIRST'              , $param) ? "FIRST                  $param->FIRST               " : '';
        $skip               = array_key_exists('SKIP'               , $param) ? "SKIP                   $param->SKIP                " : '';
        $remessa_id         = array_key_exists('REMESSA_ID'         , $param) ? "AND REMESSA_ID         $param->REMESSA_ID          " : '';
        $remessa            = array_key_exists('REMESSA'            , $param) ? "AND REMESSA            $param->REMESSA             " : '';
        $remessa_familia_id = array_key_exists('REMESSA_FAMILIA_ID' , $param) ? "AND REMESSA_FAMILIA_ID $param->REMESSA_FAMILIA_ID  " : '';
        $familia_id         = array_key_exists('FAMILIA_ID'         , $param) ? "AND FAMILIA_ID         $param->FAMILIA_ID          " : '';
        
        $args = [
            '@FIRST'              => $first,
            '@SKIP'               => $skip,
            '@REMESSA_ID'         => $remessa_id,
            '@REMESSA'            => $remessa,
            '@REMESSA_FAMILIA_ID' => $remessa_familia_id,
            '@FAMILIA_ID'         => $familia_id
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectRemessa($param1) {       
        
        $sql =
        "
            SELECT *
            FROM (
                SELECT
                    REMESSA,
                    REMESSA_ID,
                    REMESSA_ESTABELECIMENTO_ID,
                    REMESSA_DATA,
                    REMESSA_DATA_TEXT,
                    REMESSA_FAMILIA_ID,
                    REMESSA_FAMILIA_DESCRICAO,
                    MIN(CONSUMO_STATUS) CONSUMO_STATUS,
                    SUM(QUANTIDADE) QUANTIDADE,
                    SUM(QUANTIDADE_CONSUMO) QUANTIDADE_CONSUMO,
                    ( SUM(IIF(QUANTIDADE_CONSUMO > QUANTIDADE,QUANTIDADE,QUANTIDADE_CONSUMO)) / SUM(QUANTIDADE) ) CONSUMO_PERCENTUAL

                FROM SPC_REMESSA_TRANSACAO (/*@REMESSA_FAMILIA_ID*/)
                
                WHERE TRUE    
                /*@REMESSA*/
                /*@REMESSA_ID*/
                /*@FAMILIA_ID*/
                /*@CONSUMO_STATUS*/
    
                GROUP BY 1,2,3,4,5,6,7
                ) Z
            WHERE TRUE
                /*@CONSUMO_PERCENTUAL*/ /*@CONSUMO_STATUS2*/
        ";
        
            
        $param = (object)[];


        if ( isset($param1->REMESSA) && $param1->REMESSA != '' ) {
            $param->REMESSA = " = '$param1->REMESSA'";
        }

        if ( isset($param1->REMESSA_ID) && $param1->REMESSA_ID > 0 ) {
            $param->REMESSA_ID = " = $param1->REMESSA_ID";
        }

        if ( isset($param1->REMESSA_FAMILIA_ID) && $param1->REMESSA_FAMILIA_ID > 0 ) {
            $param->REMESSA_FAMILIA_ID = " = $param1->REMESSA_FAMILIA_ID";
        }

        if ( isset($param1->REMESSA_FAMILIAS_ID) && trim($param1->REMESSA_FAMILIAS_ID) != '' ) {
            $param->REMESSA_FAMILIA_ID = $param1->REMESSA_FAMILIAS_ID;
        }

        if ( isset($param1->FAMILIA_ID) && $param1->FAMILIA_ID > 0 ) {
            $param->FAMILIA_ID = " = $param1->FAMILIA_ID";
        }    
        
        if ( isset($param1->CONSUMO_PERCENTUAL) && $param1->CONSUMO_PERCENTUAL == '< 1' ) {
            $param->CONSUMO_STATUS = " = '0'";
        }         
        
        if ( isset($param1->CONSUMO_PERCENTUAL) && $param1->CONSUMO_PERCENTUAL == '>= 1' ) {
            $param->CONSUMO_STATUS2 = " = '1'";
        }         
        
        if ( isset($param1->CONSUMO_PERCENTUAL) && $param1->CONSUMO_PERCENTUAL != '' ) {
            $param->CONSUMO_PERCENTUAL = " $param1->CONSUMO_PERCENTUAL";
        }         
        
        if ( isset($param1->PERIODO) && !empty($param1->PERIODO) ) {
            $param->PERIODO = " BETWEEN '". $param1->PERIODO[0] . "' AND '" . $param1->PERIODO[1] . "'";
        }         
     
        
        $first              = array_key_exists('FIRST'              , $param) ? "FIRST                  $param->FIRST               " : '';
        $skip               = array_key_exists('SKIP'               , $param) ? "SKIP                   $param->SKIP                " : '';
        $remessa_id         = array_key_exists('REMESSA_ID'         , $param) ? "AND REMESSA_ID         $param->REMESSA_ID          " : '';
        $remessa            = array_key_exists('REMESSA'            , $param) ? "AND REMESSA            $param->REMESSA             " : '';
        $remessa_familia_id = array_key_exists('REMESSA_FAMILIA_ID' , $param) ? "$param->REMESSA_FAMILIA_ID  " : '';
        $familia_id         = array_key_exists('FAMILIA_ID'         , $param) ? "AND FAMILIA_ID         $param->FAMILIA_ID          " : '';
        $consumo_percentual = array_key_exists('CONSUMO_PERCENTUAL' , $param) ? "AND CONSUMO_PERCENTUAL $param->CONSUMO_PERCENTUAL  " : '';
        $consumo_status     = array_key_exists('CONSUMO_STATUS'     , $param) ? "AND CONSUMO_STATUS     $param->CONSUMO_STATUS     " : '';
        $consumo_status2     = array_key_exists('CONSUMO_STATUS2'     , $param) ? "OR CONSUMO_STATUS     $param->CONSUMO_STATUS2     " : '';
        $periodo            = array_key_exists('PERIODO'            , $param) ? "AND REMESSA_DATA       $param->PERIODO  " : '';
        
        $args = [
            '@FIRST'              => $first,
            '@SKIP'               => $skip,
            '@REMESSA_ID'         => $remessa_id,
            '@REMESSA'            => $remessa,
            '@REMESSA_FAMILIA_ID' => $remessa_familia_id,
            '@FAMILIA_ID'         => $familia_id,
            '@CONSUMO_PERCENTUAL' => $consumo_percentual,
            '@CONSUMO_STATUS'     => $consumo_status,
            '@CONSUMO_STATUS2'     => $consumo_status2,
            '@REMESSA_DATA'       => $periodo
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectFamilia($param1) {
        
        $sql =
        "
            SELECT
                REMESSA_FAMILIA_ID,
                REMESSA_FAMILIA_DESCRICAO
            FROM (
                SELECT
                    REMESSA_FAMILIA_ID,
                    REMESSA_FAMILIA_DESCRICAO,
                    SUM(QUANTIDADE) QUANTIDADE,
                    SUM(QUANTIDADE_CONSUMO) QUANTIDADE_CONSUMO,
                    ( SUM(QUANTIDADE_CONSUMO) / SUM(QUANTIDADE) ) CONSUMO_PERCENTUAL

                FROM(
                        SELECT
                                REMESSA_FAMILIA_ID,
                                REMESSA_FAMILIA_DESCRICAO,
                                QUANTIDADE,
                                IIF(QUANTIDADE_CONSUMO > QUANTIDADE,QUANTIDADE,QUANTIDADE_CONSUMO) QUANTIDADE_CONSUMO,
                                QUANTIDADE - QUANTIDADE_CONSUMO QUANTIDADE_SALDO,
                                COALESCE((SELECT FIRST 1 A.HABILITA_KANBAN FROM VWESTOQUE_MINIMO_TAMANHO A
                                        WHERE A.PRODUTO_ID   = X.CONSUMO_PRODUTO_ID
                                        AND A.TAMANHO        = X.CONSUMO_TAMANHO
                                        AND A.LOCALIZACAO_ID = X.LOCALIZACAO_REGRA
                                ),0) AS HABILITA_KANBAN
                            FROM (
                                SELECT
                                    R.REMESSA,
                                    C.REMESSA_ID,
                                    R.ESTABELECIMENTO_ID REMESSA_ESTABELECIMENTO_ID,
                                    R.DATA REMESSA_DATA,
                                    FN_DATE_TO_STRING(R.DATA) REMESSA_DATA_TEXT,
                                    FN_LPAD(R.FAMILIA_ID,3,0) REMESSA_FAMILIA_ID,         
                                    (SELECT FIRST 1 F.DESCRICAO FROM TBFAMILIA F WHERE F.CODIGO = R.FAMILIA_ID) REMESSA_FAMILIA_DESCRICAO,
                                    C.PRODUTO_ID CONSUMO_PRODUTO_ID,
                                    C.TAMANHO CONSUMO_TAMANHO,
                                    C.QUANTIDADE,
                                    COALESCE((
                                        SELECT SUM(I.QUANTIDADE)
                                          FROM TBESTOQUE_TRANSACAO_ITEM I
                                         WHERE I.TABELA = 'TBREMESSA_CONSUMO'
                                           AND I.TABELA_ID = C.ID
                                           AND I.TABELA_NIVEL = 0
                                           AND I.TIPO = 'S'),0) QUANTIDADE_CONSUMO,
                                    COALESCE(FN_LOCALIZACAO_CODIGO2(3,C.PRODUTO_ID,P.FAMILIA_CODIGO, C.LOCALIZACAO_ID, T.ID, T.GP_ID, T.UP_ID, '', 'S'),0) AS LOCALIZACAO_REGRA
    
                                FROM
                                    VWREMESSA_CONSUMO C
                                    LEFT JOIN VWREMESSA R        ON R.REMESSA_ID     = C.REMESSA_ID
                                    LEFT JOIN VWREMESSA_TALAO T  ON T.REMESSA_ID     = C.REMESSA_ID AND T.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                                    LEFT JOIN TBPRODUTO P        ON P.CODIGO = C.PRODUTO_ID,
                                    TBESTOQUE_TRANSACAO_REGRA TR
                    
                                WHERE TRUE
                                AND C.STATUS = '0'
                                AND C.COMPONENTE = '0'
                                AND C.QUANTIDADE > 0
                                AND TR.TIPO = 4 AND TR.FAMILIA_ID = P.FAMILIA_CODIGO
                                ) X
                        ) Y
                WHERE TRUE       
                AND HABILITA_KANBAN = 0       
                /*@REMESSA*/
                /*@REMESSA_ID*/
                /*@REMESSA_FAMILIA_ID*/
                /*@FAMILIA_ID*/
    
                GROUP BY 1,2
                ) Z
--            WHERE
--                CONSUMO_PERCENTUAL < 1
        ";
        
            
        $param = (object)[];


        if ( isset($param1->REMESSA) && $param1->REMESSA != '' ) {
            $param->REMESSA = " = '$param1->REMESSA'";
        }

        if ( isset($param1->REMESSA_ID) && $param1->REMESSA_ID > 0 ) {
            $param->REMESSA_ID = " = $param1->REMESSA_ID";
        }

        if ( isset($param1->REMESSA_FAMILIA_ID) && $param1->REMESSA_FAMILIA_ID > 0 ) {
            $param->REMESSA_FAMILIA_ID = " = $param1->REMESSA_FAMILIA_ID";
        }

        if ( isset($param1->FAMILIA_ID) && $param1->FAMILIA_ID > 0 ) {
            $param->FAMILIA_ID = " = $param1->FAMILIA_ID";
        }         
        
        $first              = array_key_exists('FIRST'              , $param) ? "FIRST                  $param->FIRST               " : '';
        $skip               = array_key_exists('SKIP'               , $param) ? "SKIP                   $param->SKIP                " : '';
        $remessa_id         = array_key_exists('REMESSA_ID'         , $param) ? "AND REMESSA_ID         $param->REMESSA_ID          " : '';
        $remessa            = array_key_exists('REMESSA'            , $param) ? "AND REMESSA            $param->REMESSA             " : '';
        $remessa_familia_id = array_key_exists('REMESSA_FAMILIA_ID' , $param) ? "AND REMESSA_FAMILIA_ID $param->REMESSA_FAMILIA_ID  " : '';
        $familia_id         = array_key_exists('FAMILIA_ID'         , $param) ? "AND FAMILIA_ID         $param->FAMILIA_ID          " : '';
        
        $args = [
            '@FIRST'              => $first,
            '@SKIP'               => $skip,
            '@REMESSA_ID'         => $remessa_id,
            '@REMESSA'            => $remessa,
            '@REMESSA_FAMILIA_ID' => $remessa_familia_id,
            '@FAMILIA_ID'         => $familia_id
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectRevisao($param) {
        
        $sql = "
            SELECT 'R' TIPO,
                   R.ID PECA_ID,
                   R.PRODUTO_ID,
                   R.TAMANHO,
                   (R.SALDO - (
                    SELECT COALESCE(SUM(V.QUANTIDADE), 0)
                      FROM TBREMESSA_TALAO_VINCULO V
                     WHERE V.STATUS <> '1'
                       AND V.TIPO = 'R'
                       AND V.TABELA_ID = R.ID)) QUANTIDADE_SALDO,
                   (R.METRAGEM_SALDO - (
                    SELECT COALESCE(SUM(V.QUANTIDADE_ALTERNATIVA), 0)
                      FROM TBREMESSA_TALAO_VINCULO V
                     WHERE V.STATUS <> '1'
                       AND V.TIPO = 'R'
                       AND V.TABELA_ID = R.ID)) QUANTIDADE_SALDO_ALTERNATIVO
              FROM TBREVISAO R
             WHERE TRUE
               AND R.ID = :ID
               AND ((R.RESULTADO = 'I' OR R.RESULTADO = 'R' OR AVULSO = '1') OR (R.RESULTADO = 'P' AND R.STATUS_OB = '2'))
               AND R.SALDO > 0
        ";
        
        $args = [
            'ID' => $param->ID
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectRemessaTalaoDetalhe($param) {
        
        $sql = "
            SELECT 'D' TIPO,
                   D.ID PECA_ID,
                   D.PRODUTO_ID,
                   D.TAMANHO,
                   (D.QUANTIDADE_SALDO - (
                    SELECT COALESCE(SUM(V.QUANTIDADE), 0)
                      FROM TBREMESSA_TALAO_VINCULO V
                     WHERE V.STATUS <> '1'
                       AND V.TIPO = 'D'
                       AND V.TABELA_ID = D.ID)) QUANTIDADE_SALDO,
                   (D.QUANTIDADE_ALTERN_SALDO - (
                    SELECT COALESCE(SUM(V.QUANTIDADE_ALTERNATIVA), 0)
                      FROM TBREMESSA_TALAO_VINCULO V
                     WHERE V.STATUS <> '1'
                       AND V.TIPO = 'D'
                       AND V.TABELA_ID = D.ID)) QUANTIDADE_SALDO_ALTERNATIVO
            FROM
                VWREMESSA_TALAO_DETALHE D

            WHERE TRUE
            AND D.ID = :ID
            AND D.QUANTIDADE_SALDO > 0
            AND D.STATUS = 3
        ";
        
        $args = [
            'ID' => $param->ID
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectConsumoTransacaoAvulsa($param1) {
        
        $sql = "
            SELECT
                TRIM('A') TIPO,
                X.CONSUMO_ID,
                C.REMESSA_ID,
                FN_LPAD(C.REMESSA_TALAO_ID,4,0) REMESSA_TALAO_ID,
                X.PRODUTO_ID,
                X.TAMANHO,
                X.QUANTIDADE,
                X.UM,
                X.DATA,
                X.DATA_TEXT,
                X.DATAHORA,
                X.DATAHORA_TEXT,
                C.STATUS,
                CONFERENCIA,
                CONFERENCIA_DESCRICAO

            FROM (
                SELECT
                    I.TABELA_ID CONSUMO_ID,
                    I.PRODUTO_CODIGO PRODUTO_ID,
                    IIF(I.T01 > 0, 01,
                    IIF(I.T02 > 0, 02,
                    IIF(I.T03 > 0, 03,
                    IIF(I.T04 > 0, 04,
                    IIF(I.T05 > 0, 05,
                    IIF(I.T06 > 0, 06,
                    IIF(I.T07 > 0, 07,
                    IIF(I.T08 > 0, 08,
                    IIF(I.T09 > 0, 09,
                    IIF(I.T10 > 0, 10,
                    IIF(I.T11 > 0, 11,
                    IIF(I.T12 > 0, 12,
                    IIF(I.T13 > 0, 13,
                    IIF(I.T14 > 0, 14,
                    IIF(I.T15 > 0, 15,
                    IIF(I.T16 > 0, 16,
                    IIF(I.T17 > 0, 17,
                    IIF(I.T18 > 0, 18,
                    IIF(I.T19 > 0, 19,
                    IIF(I.T20 > 0, 20,
                    00)))))))))))))))))))) TAMANHO,

                    IIF(I.T01 > 0, I.T01,
                    IIF(I.T02 > 0, I.T02,
                    IIF(I.T03 > 0, I.T03,
                    IIF(I.T04 > 0, I.T04,
                    IIF(I.T05 > 0, I.T05,
                    IIF(I.T06 > 0, I.T06,
                    IIF(I.T07 > 0, I.T07,
                    IIF(I.T08 > 0, I.T08,
                    IIF(I.T09 > 0, I.T09,
                    IIF(I.T10 > 0, I.T10,
                    IIF(I.T11 > 0, I.T11,
                    IIF(I.T12 > 0, I.T12,
                    IIF(I.T13 > 0, I.T13,
                    IIF(I.T14 > 0, I.T14,
                    IIF(I.T15 > 0, I.T15,
                    IIF(I.T16 > 0, I.T16,
                    IIF(I.T17 > 0, I.T17,
                    IIF(I.T18 > 0, I.T18,
                    IIF(I.T19 > 0, I.T19,
                    IIF(I.T20 > 0, I.T20,
                    I.QUANTIDADE)))))))))))))))))))) QUANTIDADE,  
                    P.UNIDADEMEDIDA_SIGLA UM,
                    I.DATA,
                    FN_DATE_TO_STRING(I.DATA) DATA_TEXT,
                    I.DATAHORA,
                    FN_TIMESTAMP_TO_STRING(I.DATAHORA)DATAHORA_TEXT,
                    I.CONFERENCIA,
                    CASE I.CONFERENCIA
                    WHEN '1' THEN 'À CONFERIR'
                    WHEN '2' THEN 'CONFERIDO'
                    ELSE '' END CONFERENCIA_DESCRICAO

                FROM
                    TBESTOQUE_TRANSACAO_ITEM I,
                    TBPRODUTO P

                WHERE
                    I.TABELA = 'TBREMESSA_CONSUMO'
                AND I.TABELA_NIVEL = 0
                AND I.TIPO = 'E'
                AND P.CODIGO = I.PRODUTO_CODIGO
                ) X,
                VWREMESSA_CONSUMO C

            WHERE
                C.ID = X.CONSUMO_ID
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
            /*@PRODUTO_ID*/
            /*@TAMANHO*/
        ";
        
            
        $param = (object)[];

        if ( isset($param1->REMESSA_ID) && $param1->REMESSA_ID > 0 ) {
            $param->REMESSA_ID = " = $param1->REMESSA_ID";
        }

        if ( isset($param1->REMESSA_TALAO_ID) && $param1->REMESSA_TALAO_ID > 0 ) {
            $param->REMESSA_TALAO_ID = " = $param1->REMESSA_TALAO_ID";
        }

        if ( isset($param1->PRODUTO_ID) && $param1->PRODUTO_ID > 0 ) {
            $param->PRODUTO_ID = " = $param1->PRODUTO_ID";
        }

        if ( isset($param1->TAMANHO) && $param1->TAMANHO > 0 ) {
            $param->TAMANHO = " = $param1->TAMANHO";
        }

        $remessa_id         = array_key_exists('REMESSA_ID'         , $param) ? "AND C.REMESSA_ID        $param->REMESSA_ID          " : '';
        $remessa_talao_id   = array_key_exists('REMESSA_TALAO_ID'   , $param) ? "AND C.REMESSA_TALAO_ID  $param->REMESSA_TALAO_ID    " : '';
        $produto_id         = array_key_exists('PRODUTO_ID'         , $param) ? "AND X.PRODUTO_ID        $param->PRODUTO_ID          " : '';
        $tamanho            = array_key_exists('TAMANHO'            , $param) ? "AND X.TAMANHO           $param->TAMANHO             " : '';
        
        $args = [
            '@REMESSA_ID'         => $remessa_id,
            '@REMESSA_TALAO_ID'   => $remessa_talao_id,
            '@PRODUTO_ID'         => $produto_id,
            '@TAMANHO'            => $tamanho
        ];
        
        
        return $this->con->query($sql,$args);
    }
    
    public function selectConsumoTransacaoPeca($param1) {
        
        $sql = "
            SELECT
                TRIM('P') TIPO,
                X.CONSUMO_ID,
                C.REMESSA_ID,
                FN_LPAD(C.REMESSA_TALAO_ID,4,0) REMESSA_TALAO_ID,
                X.PRODUTO_ID,
                X.TAMANHO,
                X.QUANTIDADE,
                X.UM,
                X.DATA,
                X.DATA_TEXT,
                X.DATAHORA,
                X.DATAHORA_TEXT,
                C.STATUS,
                CONFERENCIA,
                CONFERENCIA_DESCRICAO

            FROM (
                SELECT
                    I.TABELA_ID CONSUMO_ID,
                    I.PRODUTO_ID,
                    I.TAMANHO,
                    I.QUANTIDADE,
                    P.UNIDADEMEDIDA_SIGLA UM,
                    T.DATA,
                    FN_DATE_TO_STRING(T.DATA) DATA_TEXT,
                    T.DATAHORA,
                    FN_TIMESTAMP_TO_STRING(T.DATAHORA)DATAHORA_TEXT,
                    T.CONFERENCIA,
                    CASE T.CONFERENCIA
                    WHEN '1' THEN 'À CONFERIR'
                    WHEN '2' THEN 'CONFERIDO'
                    ELSE '' END CONFERENCIA_DESCRICAO

                FROM
                    TBREMESSA_TALAO_VINCULO I,
                    TBPRODUTO P,
                    TBESTOQUE_TRANSACAO_ITEM T

                WHERE
                    I.ORIGEM_TABELA = 'TBREMESSA_CONSUMO'
                AND I.ORIGEM_NIVEL = 0
                AND P.CODIGO = I.PRODUTO_ID
                AND T.CONTROLE = I.ESTOQUE_ID_ENTRADA
                ) X,
                VWREMESSA_CONSUMO C

            WHERE
                C.ID = X.CONSUMO_ID
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
            /*@PRODUTO_ID*/
            /*@TAMANHO*/
        ";
        
            
        $param = (object)[];

        if ( isset($param1->REMESSA_ID) && $param1->REMESSA_ID > 0 ) {
            $param->REMESSA_ID = " = $param1->REMESSA_ID";
        }

        if ( isset($param1->REMESSA_TALAO_ID) && $param1->REMESSA_TALAO_ID > 0 ) {
            $param->REMESSA_TALAO_ID = " = $param1->REMESSA_TALAO_ID";
        }

        if ( isset($param1->PRODUTO_ID) && $param1->PRODUTO_ID > 0 ) {
            $param->PRODUTO_ID = " = $param1->PRODUTO_ID";
        }

        if ( isset($param1->TAMANHO) && $param1->TAMANHO > 0 ) {
            $param->TAMANHO = " = $param1->TAMANHO";
        }

        $remessa_id         = array_key_exists('REMESSA_ID'         , $param) ? "AND C.REMESSA_ID        $param->REMESSA_ID          " : '';
        $remessa_talao_id   = array_key_exists('REMESSA_TALAO_ID'   , $param) ? "AND C.REMESSA_TALAO_ID  $param->REMESSA_TALAO_ID    " : '';
        $produto_id         = array_key_exists('PRODUTO_ID'         , $param) ? "AND X.PRODUTO_ID        $param->PRODUTO_ID          " : '';
        $tamanho            = array_key_exists('TAMANHO'            , $param) ? "AND X.TAMANHO           $param->TAMANHO             " : '';
        
        $args = [
            '@REMESSA_ID'         => $remessa_id,
            '@REMESSA_TALAO_ID'   => $remessa_talao_id,
            '@PRODUTO_ID'         => $produto_id,
            '@TAMANHO'            => $tamanho
        ];
        
        
        return $this->con->query($sql,$args);
    }

    public function selectEtiquetaRemessa($param1) {
        
        $sql = "
            SELECT FIRST 1 R.*,
                COALESCE((SELECT FIRST 1 CODIGO FROM TBUSUARIO U WHERE U.USUARIO = CURRENT_USER),0) USUARIO_ID,
                CURRENT_USER USUARIO
              FROM VWREMESSA R
             WHERE R.REMESSA_ID = :REMESSA_ID
        ";
        
            
        $args = [
            'REMESSA_ID' => $param1->REMESSA_ID
        ];
        
        return $this->con->query($sql,$args);
    }

    public function selectEtiquetaRemessaComposicaoAgrup($param1) {
        
        $sql = "
            SELECT
                FN_LPAD(I.PRODUTO_CODIGO,6,0) PRODUTO_ID,
                FN_ELIPSES(P.DESCRICAO,27) PRODUTO_DESCRICAO,

                IIF(I.T01 > 0,01,
                IIF(I.T02 > 0,02,
                IIF(I.T03 > 0,03,
                IIF(I.T04 > 0,04,
                IIF(I.T05 > 0,05,
                IIF(I.T06 > 0,06,
                IIF(I.T07 > 0,07,
                IIF(I.T08 > 0,08,
                IIF(I.T09 > 0,09,
                IIF(I.T10 > 0,10,
                IIF(I.T11 > 0,11,
                IIF(I.T12 > 0,12,
                IIF(I.T13 > 0,13,
                IIF(I.T14 > 0,14,
                IIF(I.T15 > 0,15,
                IIF(I.T16 > 0,16,
                IIF(I.T17 > 0,17,
                IIF(I.T18 > 0,18,
                IIF(I.T19 > 0,19,
                IIF(I.T20 > 0,20,0)))))))))))))))))))) TAMANHO,
                FN_TAMANHO_GRADE(P.GRADE_CODIGO,
                IIF(I.T01 > 0,01,
                IIF(I.T02 > 0,02,
                IIF(I.T03 > 0,03,
                IIF(I.T04 > 0,04,
                IIF(I.T05 > 0,05,
                IIF(I.T06 > 0,06,
                IIF(I.T07 > 0,07,
                IIF(I.T08 > 0,08,
                IIF(I.T09 > 0,09,
                IIF(I.T10 > 0,10,
                IIF(I.T11 > 0,11,
                IIF(I.T12 > 0,12,
                IIF(I.T13 > 0,13,
                IIF(I.T14 > 0,14,
                IIF(I.T15 > 0,15,
                IIF(I.T16 > 0,16,
                IIF(I.T17 > 0,17,
                IIF(I.T18 > 0,18,
                IIF(I.T19 > 0,19,
                IIF(I.T20 > 0,20,0))))))))))))))))))))
                ) TAMANHO_DESCRICAO,
                I.USUARIO_CODIGO USUARIO_ID,
                P.UNIDADEMEDIDA_SIGLA UM,
                I.PECA_ID,
                SUM(I.QUANTIDADE) QUANTIDADE

            FROM
                TBESTOQUE_TRANSACAO_ITEM I
                LEFT JOIN VWREMESSA_CONSUMO C ON C.ID = I.TABELA_ID,
                TBPRODUTO P

            WHERE
                I.TABELA = 'TBREMESSA_CONSUMO'
            AND I.TABELA_NIVEL = 0
            AND I.CONFERENCIA = 1
            AND I.TIPO = 'E'  
            AND P.CODIGO = C.PRODUTO_ID
            AND C.REMESSA_ID = :REMESSA_ID

            GROUP BY 1,2,3,4,5,6,7
        ";
        
            
        $args = [
            'REMESSA_ID' => $param1->REMESSA_ID
        ];
        
        return $this->con->query($sql,$args);
    }

    public function insertConsumoTransacao($param) {
        
        $sql =
        "
            
            EXECUTE PROCEDURE SPI_ESTOQUE_TRANSACAO_REGRA(
                '2',
                :GP_ID,
                :PERFIL_UP,
                :FAMILIA_ID,        
                :LOCALIZACAO_ID,
                'TBREMESSA_CONSUMO',
                0,
                :CONSUMO_ID,
                :ESTABELECIMENTO_ID,
                CURRENT_DATE,
                :PRODUTO_ID,  
                :TAMANHO,   
                :QUANTIDADE,
                :TIPO,
                :CONSUMO,
                :CCUSTO,
                :OBSERVACAO,
                " . ( isset($param->TRANSACAO_ID) ? $param->TRANSACAO_ID : 'NULL' ) . ",
                NULL,
                NULL,
                " . ( isset($param->PECA_ID) ? $param->PECA_ID : 'NULL' ) . "
            );
        ";
        
        $args = [
            'GP_ID'              => $param->GP_ID,
            'PERFIL_UP'          => $param->PERFIL_UP,
            'FAMILIA_ID'         => $param->FAMILIA_ID,
            'LOCALIZACAO_ID'     => $param->LOCALIZACAO_ID,
            'CONSUMO_ID'         => $param->CONSUMO_ID,
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'PRODUTO_ID'         => $param->PRODUTO_ID,
            'TAMANHO'            => $param->TAMANHO,
            'QUANTIDADE'         => $param->QUANTIDADE,
            'TIPO'               => $param->TIPO,
            'CONSUMO'            => $param->CONSUMO,
            'CCUSTO'             => $param->CCUSTO,
            'OBSERVACAO'         => $param->OBSERVACAO,
            //'PECA_ID'            => $param->PECA_ID,
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function insertRemessaTalaoVinculo($param) {

        $sql = "
            INSERT INTO TBREMESSA_TALAO_VINCULO (
                ORIGEM_TABELA,
                ORIGEM_NIVEL,
                ORIGEM_TABELA_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                TAMANHO,
                QUANTIDADE,
                ESTOQUE_ID_SAIDA,
                ESTOQUE_ID_ENTRADA,
                STATUS
            ) VALUES (
                'TBREMESSA_CONSUMO',
                0,
                :CONSUMO_ID,
                :TIPO,
                :TABELA_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :QUANTIDADE,
                :ESTOQUE_ID_ENTRADA,
                :ESTOQUE_ID_SAIDA,
                '0'
            );
        ";
        
        $args = [
            'CONSUMO_ID'             => $param->CONSUMO_ID,
            'TIPO'                   => $param->TIPO,
            'TABELA_ID'              => $param->TABELA_ID,
            'PRODUTO_ID'             => $param->PRODUTO_ID,
            'TAMANHO'                => $param->TAMANHO,
            'QUANTIDADE'             => $param->QUANTIDADE,
            'ESTOQUE_ID_ENTRADA'     => $param->ESTOQUE_ID_ENTRADA,
            'ESTOQUE_ID_SAIDA'       => $param->ESTOQUE_ID_SAIDA,
        ]; 
        
        return $this->con->query($sql,$args);       
    }
    
    public function deleteConsumoTransacaoAvulsa($param) {

        $sql = "
            DELETE
              FROM TBESTOQUE_TRANSACAO_ITEM
             WHERE TABELA       = 'TBREMESSA_CONSUMO'
               AND TABELA_NIVEL = 0
               AND TABELA_ID    = :CONSUMO_ID
               AND DATAHORA BETWEEN DATEADD(-3 SECOND TO:DATAHORA_1) AND DATEADD(1 SECOND TO:DATAHORA_2)
        ";
        
        $args = [
            'CONSUMO_ID' => $param->CONSUMO_ID,
            'DATAHORA_1' => $param->DATAHORA,
            'DATAHORA_2' => $param->DATAHORA,
        ]; 
        
        return $this->con->query($sql,$args);       
    }
    
    public function deleteConsumoTransacaoPeca($param) {

        $sql = "
            DELETE
              FROM TBREMESSA_TALAO_VINCULO
             WHERE TABELA       = 'TBREMESSA_CONSUMO'
               AND TABELA_NIVEL = 0
               AND TABELA_ID    = :CONSUMO_ID
        ";
        
        $args = [
            'CONSUMO_ID' => $param->CONSUMO_ID,
        ]; 
        
        return $this->con->query($sql,$args);       
    }
        
    
}