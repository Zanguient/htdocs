<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _22100 - Geracao de Remessas de Bojo
 */
class _22100DAO {

    public static function selectAgrupamentoItens($param) {
        
        $con = new _Conexao;

        $sql = "
            SELECT
                Y.ESTABELECIMENTO_ID,
                Y.AGRUPAMENTO_ID,   
                Y.SEMANA,
                Y.PEDIDO_PERFIL,
                Y.ID,
                Y.PRODUTO_ID,   
                Y.PRODUTO_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,   
                Y.TAMANHO_DESCRICAO,  
                Y.LINHA_ID,
                Y.LINHA_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,   
                Y.MATRIZ_ID,
                Y.MATRIZ_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                (Y.COR_TEMPO_SETUP) COR_TEMPO_SETUP,
                (Y.COR_TEMPO_SETUP_APROVACAO) COR_TEMPO_SETUP_APROVACAO,
                Y.COR_DADOS,
                Y.COR_AMOSTRA,
                IIF(Y.COR_AMOSTRA2 = 0,Y.COR_AMOSTRA,Y.COR_AMOSTRA2)COR_AMOSTRA2,
                Y.COR_CLASSE,
                Y.TABELA_ID,
                Y.TAB_ITEM_ID,
                Y.TIPO,       
                Y.LOCALIZACAO_ID,
                Y.TONALIDADE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.CLIENTE_NOMEFANTASIA,
                Y.DATA_COMPLETA,
                CAST(IIF(Y.TIPO = 'P',SUBSTRING(Y.DATA_COMPLETA FROM 1 FOR 10),NULL) AS DATE) DATA,
                Y.QUANTIDADE_CAPACIDADE,
                Y.QUANTIDADE_PEDIDO,     
                Y.QUANTIDADE_SALDO QUANTIDADE_TOTAL,
                Y.UM,
                Y.TALAO_COTA,
                Y.TALAO_DETALHE_COTA,
                COALESCE((
                    SELECT FIRST 1 MFP.TEMPO
                      FROM TBMODELO_FLUXO_PRODUCAO MFP
                     WHERE TRUE
                       AND MFP.MODELO_ID = Y.MODELO_ID
                       AND MFP.COR_ID    = Y.COR_ID
                       AND MFP.TAMANHO   = Y.TAMANHO
                       AND MFP.FLUXO_ID  = 216),0) TEMPO_UNITARIO               
                --(SELECT TEMPO FROM SPC_PROGRAMACAO_TEMPO_BOJO(Y.MODELO_ID, Y.TAMANHO, Y.COR_ID, 1)) TEMPO_UNITARIO

            FROM
               (SELECT
                    X.ESTABELECIMENTO_ID,
                    X.AGRUPAMENTO_ID,
                    X.SEMANA,
                    X.PEDIDO_PERFIL,
                    X.ID,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.LINHA_ID,
                    X.LINHA_DESCRICAO,
                    X.MODELO_ID,      
                    CAST(SUBSTRING(X.MODELO_DADOS FROM  1 FOR 50) AS VARCHAR(50)) MODELO_DESCRICAO,
                    LPAD(CAST(SUBSTRING(X.MODELO_DADOS FROM 51 FOR 30) AS INTEGER),4,'0') MATRIZ_ID,
                    CAST(SUBSTRING(X.MODELO_DADOS FROM  81 FOR 50) AS VARCHAR(50)) MATRIZ_DESCRICAO,
                    X.COR_ID,
                    X.COR_DADOS,
                    CAST(SUBSTRING(X.COR_DADOS FROM  1 FOR 20) AS VARCHAR(20)) COR_DESCRICAO,
                    X.COR_TEMPO_SETUP,
                    X.COR_TEMPO_SETUP_APROVACAO,
                    CAST(SUBSTRING(X.COR_DADOS FROM 21 FOR 30) AS INTEGER) COR_AMOSTRA,
                    CAST(IIF(SUBSTRING(X.COR_DADOS FROM 52 FOR 30) = '', 0, SUBSTRING(X.COR_DADOS FROM 52 FOR 30)) AS INTEGER) COR_AMOSTRA2,
                    X.GRADE_ID,
                    X.TAMANHO,    
                    X.TAMANHO_DESCRICAO,
                    X.COR_CLASSE,
                    X.TABELA_ID,
                    X.TAB_ITEM_ID,
                    X.TIPO,
                    X.LOCALIZACAO_ID,
                    X.TONALIDADE,
                    X.PERFIL_SKU,
                    X.PERFIL_SKU_DESCRICAO,
                    IIF(PI.CLIENTE_CODIGO>0,
                        (SELECT FIRST 1 E.NOMEFANTASIA
                           FROM TBEMPRESA E
                          WHERE E.CODIGO = PI.CLIENTE_CODIGO),
                          'REPOSIÇÃO DE ESTOQUE') CLIENTE_NOMEFANTASIA,
            
                
                    (SELECT
                        FIRST 1 LPAD(EXTRACT(DAY FROM PE.DATA_ENTREGA),2,'0')||'.'||LPAD(EXTRACT(MONTH FROM PE.DATA_ENTREGA),2,'0')||'.'||LPAD(EXTRACT(YEAR FROM PE.DATA_ENTREGA),4,'0') ||' - '||
                                LPAD(EXTRACT(DAY FROM PE.DATA        ),2,'0')||'.'||LPAD(EXTRACT(MONTH FROM PE.DATA        ),2,'0')||'.'||LPAD(EXTRACT(YEAR FROM PE.DATA        ),4,'0') ||' ('||LPAD(PE.PRIORIDADE,2,'0')||')'
                       FROM TBPEDIDO PE
                      WHERE PE.PEDIDO = PI.PEDIDO
                        AND PE.ESTABELECIMENTO_CODIGO = X.ESTABELECIMENTO_ID) DATA_COMPLETA,
                    X.QUANTIDADE_CAPACIDADE,
                    X.QUANTIDADE_PEDIDO, 
                    X.QUANTIDADE_SALDO,
                    X.UM,
                    X.TALAO_COTA,
                    X.TALAO_DETALHE_COTA
                
                FROM
                   (SELECT
                        A.ESTABELECIMENTO_ID,
                        A.AGRUPAMENTO_ID,
                        D.SEMANA,
                        D.PERFIL PEDIDO_PERFIL,
                        A.ID,
                        LPAD(A.PRODUTO_ID,6,'0') PRODUTO_ID,
                        P.DESCRICAO PRODUTO_DESCRICAO,
                        P.GRADE_CODIGO GRADE_ID,
                        A.TAMANHO,

                        (SELECT TAM_DESCRICAO
                           FROM SP_TAMANHO_GRADE
                           (P.GRADE_CODIGO, A.TAMANHO)) TAMANHO_DESCRICAO,

                        LPAD(P.LINHA_CODIGO,4,'0') LINHA_ID,     

                        (SELECT FIRST 1 DESCRICAO
                           FROM TBMODELO_LINHA
                          WHERE CODIGO = P.LINHA_CODIGO)LINHA_DESCRICAO,

                        LPAD(P.MODELO_CODIGO,5,'0') MODELO_ID,
                         
                        (SELECT FIRST 1 RPAD(MD.DESCRICAO,50) || LPAD(MD.MATRIZ_CODIGO,30) || (SELECT FIRST 1 RPAD(DESCRICAO,50) FROM TBMATRIZ MT WHERE MT.CODIGO = MD.MATRIZ_CODIGO)
                           FROM TBMODELO MD
                          WHERE MD.CODIGO = P.MODELO_CODIGO)MODELO_DADOS,
                          
                        LPAD(P.COR_CODIGO,4,'0') COR_ID,

                        (SELECT
                            RPAD(C.DESCRICAO,20) || 
                            COALESCE(
                                (SELECT FIRST 2 LIST(LPAD(C1.AMOSTRA,30),'')
                                   FROM TBCOR C1, TBCOR_COMPOSICAO CC
                                  WHERE CC.COR_ID = C.CODIGO
                                    AND CC.COR_COMPOSICAO_ID = C1.CODIGO),
                                 LPAD(C.AMOSTRA,30))CORES
                          FROM TBCOR C
                         WHERE C.CODIGO = P.COR_CODIGO)COR_DADOS,

                        (SELECT FIRST 1 C.CLASSE||'.'||LPAD(C.SUBCLASSE,3,'0')
                           FROM TBCOR C
                          WHERE C.CODIGO = P.COR_CODIGO) COR_CLASSE,


                        (SELECT FIRST 1 C.TEMPO_SETUP
                           FROM TBCOR C
                          WHERE C.CODIGO = P.COR_CODIGO) COR_TEMPO_SETUP,

                        (SELECT FIRST 1 C.TEMPO_SETUP_APROVACAO
                           FROM TBCOR C
                          WHERE C.CODIGO = P.COR_CODIGO) COR_TEMPO_SETUP_APROVACAO,

                        A.TABELA_ID,
                        A.TAB_ITEM_ID,
                        A.TIPO,
                        (SELECT FIRST 1 AG.LOCALIZACAO_ID FROM TBAGRUPAMENTO AG WHERE AG.ID = A.AGRUPAMENTO_ID) LOCALIZACAO_ID,
            
                        (SELECT FIRST 1 C.TONALIDADE
                           FROM TBCOR C
                          WHERE C.CODIGO = P.COR_CODIGO) TONALIDADE,

                        A.PERFIL_SKU,
            
                        (SELECT FIRST 1 B.DESCRICAO
                           FROM TBPERFIL B
                          WHERE B.ID = A.PERFIL_SKU
                            AND B.TABELA = 'SKU') PERFIL_SKU_DESCRICAO,
            
                        A.QUANTIDADE_PEDIDO,
                        A.SALDO QUANTIDADE_SALDO,
                        P.UNIDADEMEDIDA_SIGLA UM,
                    
                        CAST((SELECT
                            CASE A.TAMANHO
                                WHEN 01 THEN ML.P01
                                WHEN 02 THEN ML.P02
                                WHEN 03 THEN ML.P03
                                WHEN 04 THEN ML.P04
                                WHEN 05 THEN ML.P05
                                WHEN 06 THEN ML.P06
                                WHEN 07 THEN ML.P07
                                WHEN 08 THEN ML.P08
                                WHEN 09 THEN ML.P09
                                WHEN 10 THEN ML.P10
                                WHEN 11 THEN ML.P11
                                WHEN 12 THEN ML.P12
                                WHEN 13 THEN ML.P13
                                WHEN 14 THEN ML.P14
                                WHEN 15 THEN ML.P15
                                WHEN 16 THEN ML.P16
                                WHEN 17 THEN ML.P17
                                WHEN 18 THEN ML.P18
                                WHEN 19 THEN ML.P19
                                WHEN 20 THEN ML.P20
                            END
                        FROM TBMODELO_LINHA ML WHERE ML.CODIGO = P.LINHA_CODIGO) AS INTEGER) QUANTIDADE_CAPACIDADE,

                        (SELECT
                            CASE A.TAMANHO
                                WHEN 01 THEN RC.T01
                                WHEN 02 THEN RC.T02
                                WHEN 03 THEN RC.T03
                                WHEN 04 THEN RC.T04
                                WHEN 05 THEN RC.T05
                                WHEN 06 THEN RC.T06
                                WHEN 07 THEN RC.T07
                                WHEN 08 THEN RC.T08
                                WHEN 09 THEN RC.T09
                                WHEN 10 THEN RC.T10
                                WHEN 11 THEN RC.T11
                                WHEN 12 THEN RC.T12
                                WHEN 13 THEN RC.T13
                                WHEN 14 THEN RC.T14
                                WHEN 15 THEN RC.T15
                                WHEN 16 THEN RC.T16
                                WHEN 17 THEN RC.T17
                                WHEN 18 THEN RC.T18
                                WHEN 19 THEN RC.T19
                                WHEN 20 THEN RC.T20
                            END
                         FROM TBMODELO_REMESSA_COTA RC WHERE RC.MODELO_CODIGO = P.MODELO_CODIGO) TALAO_COTA,

                        (SELECT
                            CASE A.TAMANHO
                                WHEN 01 THEN PC.T01
                                WHEN 02 THEN PC.T02
                                WHEN 03 THEN PC.T03
                                WHEN 04 THEN PC.T04
                                WHEN 05 THEN PC.T05
                                WHEN 06 THEN PC.T06
                                WHEN 07 THEN PC.T07
                                WHEN 08 THEN PC.T08
                                WHEN 09 THEN PC.T09
                                WHEN 10 THEN PC.T10
                                WHEN 11 THEN PC.T11
                                WHEN 12 THEN PC.T12
                                WHEN 13 THEN PC.T13
                                WHEN 14 THEN PC.T14
                                WHEN 15 THEN PC.T15
                                WHEN 16 THEN PC.T16
                                WHEN 17 THEN PC.T17
                                WHEN 18 THEN PC.T18
                                WHEN 19 THEN PC.T19
                                WHEN 20 THEN PC.T20
                            END
                         FROM TBMODELO_PRODUCAO_COTA PC WHERE PC.MODELO_CODIGO = P.MODELO_CODIGO) TALAO_DETALHE_COTA
                    
                    FROM TBAGRUPAMENTO_PEDIDO A, TBPRODUTO P, TBAGRUPAMENTO D
                    
                    WHERE P.CODIGO             = A.PRODUTO_ID
                      AND A.AGRUPAMENTO_ID     = D.ID
                      AND A.ESTABELECIMENTO_ID = D.ESTABELECIMENTO_ID  
                      AND A.SALDO              > 0               
                      AND A.ESTABELECIMENTO_ID = :ESTABELECIMENTO_ID
                      AND A.AGRUPAMENTO_ID    <= :AGRUPAMENTO_ID
                      AND P.FAMILIA_CODIGO     = :FAMILIA_ID
                      AND D.PERFIL             = :PEDIDO_PERFIL
                      AND D.SIMULACAO          = '0'
                    ) X
                    LEFT JOIN
                    TBPEDIDO_ITEM PI ON
                        X.TABELA_ID = PI.PEDIDO
                    AND X.TAB_ITEM_ID = PI.CONTROLE
                    AND X.TAMANHO = PI.TAMANHO
                WHERE
                    X.TONALIDADE LIKE :TONALIDADE
                ) Y

            ORDER BY
                LINHA_DESCRICAO,
                TAMANHO_DESCRICAO,
                COR_CLASSE,
                COR_DESCRICAO,
                MODELO_DESCRICAO,
                DATA,
                TABELA_ID
        ";
       
        $args = [
            ':AGRUPAMENTO_ID'     => $param->AGRUPAMENTO_ID,
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':FAMILIA_ID'         => $param->FAMILIA_ID,
            ':PEDIDO_PERFIL'      => $param->PEDIDO_PERFIL,
            ':TONALIDADE'         => $param->TONALIDADE
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectGpUpEstacao($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $filtro         = isset($param->FILTRO   ) ? "AND FILTRO   LIKE '%" . str_replace(' ','%', $param->FILTRO               ) ."%'" : '';
        $status         = isset($param->STATUS   ) ? "AND STATUS     IN  (" . arrayToList($param->STATUS    , 999999   ) . ")" : '';
        $familia        = isset($param->FAMILIA  ) ? "AND FAMILIA_ID IN  (" . arrayToList($param->FAMILIA   , 999999   ) . ")" : '';
        $gp             = isset($param->GP       ) ? "AND GP_ID      IN  (" . arrayToList($param->GP        , 999999   ) . ")" : '';
        $up             = isset($param->UP       ) ? "AND UP_ID      IN  (" . arrayToList($param->UP        , 999999   ) . ")" : '';
        $up_status      = isset($param->UP_STATUS) ? "AND UP_STATUS  IN  (" . arrayToList($param->UP_STATUS , "'#'","'") . ")" : '';
        $estacao        = isset($param->ESTACAO  ) ? "AND ESTACAO    IN  (" . arrayToList($param->ESTACAO   , 999999   ) . ")" : '';
        $estacao_perfil = isset($param->PERFIL   ) ? "AND PERFIL_SKU IN  (" . arrayToList($param->PERFIL    ,    "'#'","'",'PERFIL') . ")" : '';
        $order          = isset($param->ORDER    ) ? "ORDER BY            " . arrayToList($param->ORDER     , 'FAMILIA_DESCRICAO, GP_DESCRICAO, ESTACAO_DESCRICAO')  : '';
        
        $sql = "
            SELECT
                X.FAMILIA_ID,
                X.FAMILIA_DESCRICAO,
                X.FAMILIA_UM_ALTERNATIVA,
                X.FAMILIA_STATUS,
                X.GP_ID,
                X.GP_DESCRICAO,
                X.GP_STATUS,
                X.UP_ID,
                X.UP_DESCRICAO,
                X.UP_STATUS,
                X.ESTACAO,
                X.ESTACAO_DESCRICAO,
                X.HABILITA_SETUP_AQUECIMENTO,
                X.ESTACAO_LARGURA,
                X.ESTACAO_COMPRIMENTO,
                X.ESTACAO_ALTURA,
                X.ESTACAO_STATUS,
                X.FILTRO,
                COALESCE(T.MINUTOS,0) MINUTOS,
                COALESCE(T.MINUTOS_PROGRAMADOS,0) MINUTOS_PROGRAMADOS,
                COALESCE(T.MINUTOS_PROGRAMADOS,0) MINUTOS_PROGRAMADOS_ORIGNAL,
                LIST(X.PERFIL_SKU, ', ')PERFIL_SKU,
                LIST(X.PERFIL_SKU_DESCRICAO, ', ')PERFIL_SKU_DESCRICAO

            FROM
                (
                SELECT
                    F.CODIGO    FAMILIA_ID,
                    F.DESCRICAO FAMILIA_DESCRICAO,
                    F.STATUS    FAMILIA_STATUS,
                    F.UNIDADEMEDIDA_ALTERNATIVO FAMILIA_UM_ALTERNATIVA,
                    G.ID        GP_ID,
                    G.DESCRICAO GP_DESCRICAO,      
                    G.STATUS    GP_STATUS,
                    U.ID        UP_ID,
                    U.DESCRICAO UP_DESCRICAO,       
                    U.STATUS    UP_STATUS,
                    S.ID        ESTACAO,
                    S.DESCRICAO ESTACAO_DESCRICAO,
                    TRIM(S.HABILITA_SETUP_AQUECIMENTO) HABILITA_SETUP_AQUECIMENTO,
                    S.LARG      ESTACAO_LARGURA,
                    S.COMP      ESTACAO_COMPRIMENTO,
                    S.ALT       ESTACAO_ALTURA,
                    S.STATUS    ESTACAO_STATUS,
                    S.PERFIL_SKU,
                    (SELECT FIRST 1 P.DESCRICAO FROM TBPERFIL P WHERE P.TABELA = 'SKU' AND P.ID = S.PERFIL_SKU) PERFIL_SKU_DESCRICAO,
    
                   (F.CODIGO    || ' ' ||
                    F.DESCRICAO || ' ' ||
                    G.ID        || ' ' ||
                    G.DESCRICAO || ' ' ||
                    U.ID        || ' ' ||
                    U.DESCRICAO || ' ' ||
                    S.ID        || ' ' ||
                    S.DESCRICAO)FILTRO
                FROM
                    TBUP U,
                    TBSUB_UP S,
                    TBGP G,
                    TBGP_UP GU,
                    TBFAMILIA F
                WHERE
                    U.ID = S.UP_ID
                AND U.ID = GU.UP_ID
                AND G.ID = GU.GP_ID
                AND F.CODIGO = G.FAMILIA_ID
                )X
                LEFT JOIN
                (SELECT
                    U.UP_ID,
                    E.ESTACAO_ID ESTACAO,
                    E.DATA,
                    AVG(U.MINUTOS) MINUTOS,
                    
                    AVG(COALESCE(
                    (SELECT SUM(P.TEMPO)
                       FROM TBPROGRAMACAO P
                      WHERE E.DATA BETWEEN cast(P.DATAHORA_INICIO as date) AND cast(P.DATAHORA_FIM as date)
                        AND P.GP_ID   = E.GP_ID
                        AND P.UP_ID   = E.UP_ID
                        AND P.ESTACAO = E.ESTACAO_ID),0))MINUTOS_PROGRAMADOS
                FROM
                    TBCALENDARIO_UP U,
                    TBCALENDARIO_ESTACAO E
                WHERE
                    E.GP_ID = U.GP_ID
                AND E.UP_ID = U.UP_ID
                AND E.DATA  = U.DATA
                GROUP BY 1,2,3) T ON
                    T.UP_ID   = X.UP_ID
                AND T.ESTACAO = X.ESTACAO
                AND T.DATA    = :DATA

            WHERE
                1=1
            /*@FAMILIA*/
            /*@GP*/
            /*@UP*/
            /*@UP_STATUS*/
            /*@ESTACAO*/
            /*@ESTACAO_PERFIL*/
            /*@STATUS_FAMILIA*/
            /*@STATUS_GP*/
            /*@STATUS_UP*/
            /*@STATUS_ESTACAO*/
            /*@FILTRO*/
                       
            GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20

            /*@ORDER*/
        ";
        
        $args = [
            '@FILTRO'         => $filtro,
            '@STATUS'         => $status,
            '@FAMILIA'        => $familia,
            '@GP'             => $gp,
            '@UP'             => $up,
            '@UP_STATUS'      => $up_status,
            '@ESTACAO'        => $estacao,
            '@ESTACAO_PERFIL' => $estacao_perfil,
            '@ORDER'          => $order,
            ':DATA'           => isset($param->DATA) ? $param->DATA : date('d.m.y')
        ];
        
        return $con->query($sql,$args);
    }

    public static function selectConsumoMpAlocacao($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                A.PRODUTO_ID,
                P.DESCRICAO PRODUTO_DESCRICAO,
                M.FAMILIA_CODIGO FAMILIA,
                P.FAMILIA_CODIGO FAMILIA_MP,
                TRIM(COALESCE(P.PROGRAMAR_SEM_ESTOQUE,'0')) PROGRAMAR_SEM_ESTOQUE,
                CASE T.TAMANHO
                   WHEN 01 THEN CAST((A.T01/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 02 THEN CAST((A.T02/A.FATOR_CONVERSAO) AS NUMERIC(15,4)) 
                   WHEN 03 THEN CAST((A.T03/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 04 THEN CAST((A.T04/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 05 THEN CAST((A.T05/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 06 THEN CAST((A.T06/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 07 THEN CAST((A.T07/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 08 THEN CAST((A.T08/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 09 THEN CAST((A.T09/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 10 THEN CAST((A.T10/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 11 THEN CAST((A.T11/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 12 THEN CAST((A.T12/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 13 THEN CAST((A.T13/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 14 THEN CAST((A.T14/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 15 THEN CAST((A.T15/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 16 THEN CAST((A.T16/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 17 THEN CAST((A.T17/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 18 THEN CAST((A.T18/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 19 THEN CAST((A.T19/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   WHEN 20 THEN CAST((A.T20/A.FATOR_CONVERSAO) AS NUMERIC(15,4))
                   ELSE 0
                END CONSUMO

            FROM
                TBMODELO_CONSUMO_COR A,
                TBMODELO M,
                TBPRODUTO P,
                TBFAMILIA_MODELO_ALOCACAO FMA,
                (SELECT FIRST 1 CAST(:TAMANHO AS INTEGER) TAMANHO FROM RDB\$DATABASE) T
            WHERE
                A.MODELO_ID      = M.CODIGO
            AND A.PRODUTO_ID     = P.CODIGO
            AND A.MODELO_ID      = :MODELO_ID
            AND A.COR_ID         = :COR_ID
            AND M.FAMILIA_CODIGO = FMA.FAMILIA_ID
            AND P.FAMILIA_CODIGO = FMA.FAMILIA_MODELO_ID
            AND FMA.CONSUMO      = '2'
        ";
        
        $args = [
            ':MODELO_ID' => $param->MODELO_ID,
            ':COR_ID'    => $param->COR_ID,
            ':TAMANHO'   => $param->TAMANHO
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectProdutoEstoque($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                P.CODIGO    PRODUTO_ID,
                COALESCE(
                    (SELECT FIRST 1 C.DESCRICAO
                       FROM TBCOR C
                      WHERE C.CODIGO = P.COR_CODIGO),P.DESCRICAO) PRODUTO_DESCRICAO,

                COALESCE(
                    (SELECT SUM(A.SALDO)
                       FROM TBESTOQUE_DETALHE_ALOCACAO A
                      WHERE A.ESTABELECIMENTO_ID = E.ESTABELECIMENTO_ID
                        AND A.PRODUTO_ID         = P.CODIGO
                        AND A.LOCALIZACAO_ID     = P.LOCALIZACAO_CODIGO
                        AND A.TIPO               = 'C'
                        AND A.SALDO              > 0
                        AND A.TAMANHO            = 0),0.0000)  ALOCADO,

                COALESCE(CAST(
                    (SELECT CAST(SUM(E.CONSUMO_KG-COALESCE(E.QUANTIDADE_CONFERENCIA,0)) AS NUMERIC(15,4))
                       FROM TBREMESSA_CONSUMO E
                      WHERE E.ESTABELECIMENTO_ID       = E.ESTABELECIMENTO_ID
                        AND E.PRODUTO_ID               = P.CODIGO
                        AND E.LOCALIZACAO_ID           = P.LOCALIZACAO_CODIGO
                        AND E.CONFERENCIA              = '0'
                        AND COALESCE(E.COMPONENTE,'0') = '0'
                        AND E.TAMANHO                  = 0) AS NUMERIC(15,4)),0.0000) EMPENHADO,

                COALESCE(
                    (SELECT FIRST 1 CAST(B.SALDO AS NUMERIC(15,4))
                       FROM TBESTOQUE_SALDO B
                      WHERE B.ESTABELECIMENTO_CODIGO = E.ESTABELECIMENTO_ID
                        AND B.LOCALIZACAO_CODIGO     = P.LOCALIZACAO_CODIGO
                        AND B.PRODUTO_CODIGO         = P.CODIGO),0) ESTOQUE,

                COALESCE(
                    (SELECT FIRST 1 CAST(B.SALDO AS NUMERIC(15,4))
                       FROM TBESTOQUE_SALDO B, TBFAMILIA_FICHA FF
                      WHERE B.ESTABELECIMENTO_CODIGO = E.ESTABELECIMENTO_ID
                        AND B.PRODUTO_CODIGO         = P.CODIGO
                        AND B.ESTABELECIMENTO_CODIGO = FF.ESTABELECIMENTO_CODIGO
                        AND FF.FAMILIA_CODIGO        = P.FAMILIA_CODIGO
                        AND B.LOCALIZACAO_CODIGO     = FF.LOCALIZACAO_REVISAO),0) REVISAO,

                P.UNIDADEMEDIDA_SIGLA UM

            FROM
                TBPRODUTO P,
                (SELECT FIRST 1 CAST(:ESTABELECIMENTO_ID AS INTEGER) ESTABELECIMENTO_ID FROM TBTURNO T) E
            WHERE
                P.CODIGO = :PRODUTO_ID
        ";
        
        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'PRODUTO_ID'         => $param->PRODUTO_ID
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectFerramenta($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
                --SELECT *
                --    FROM
                --    (
                    SELECT
                        LPAD(F.GRUPO_ID,4,'0') GRUPO_ID,
                        LPAD(F.ID,4,'0') ID,
                        F.DESCRICAO,
                        F.SERIE,
                        LPAD(F.MATRIZ_ID,4,'0') MATRIZ_ID,
                        (SELECT FIRST 1 M.DESCRICAO
                           FROM TBMATRIZ M
                          WHERE M.CODIGO = F.MATRIZ_ID) MATRIZ_DESCRICAO,  
                        LPAD(I.LINHA_ID,4,'0') LINHA_ID,
                        I.TAMANHO,
                        F.LARG LARGURA,
                        F.COMP COMPRIMENTO,
                        F.ALT ALTURA,
                        F.DATA,
                        F.OBSERVACAO,
                        (F.TEMPO_SETUP) TEMPO_SETUP,
                        (F.TEMPO_SETUP_AQUECIMENTO) TEMPO_SETUP_AQUECIMENTO,
                        (SELECT FIRST 1 R.REMESSA || ' / ' || LPAD(T.REMESSA_TALAO_ID,4,0)
                           FROM TBPROGRAMACAO P, VWREMESSA R, VWREMESSA_TALAO T
                          WHERE P.FERRAMENTA_ID = F.ID
                            AND T.ID = P.TABELA_ID
                            AND P.TIPO = 'A'
                            AND P.REMESSA_ID = R.REMESSA_ID
                            AND R.DATA = :DATA_REMESSA
                            AND P.STATUS < 3) TALAO_ID
        
                    FROM
                        TBFERRAMENTARIA F,
                        TBFERRAMENTARIA_ITEM I
                    WHERE
                        F.ID        = I.FERRAMENTARIA_ID
                    AND F.STATUS    = '1'
                    AND I.LINHA_ID  = :LINHA_ID
                    AND I.TAMANHO   = :TAMANHO
                    
                    ORDER BY LARGURA DESC, COMPRIMENTO DESC, ALTURA DESC, F.DATA DESC
                    
                --) X
                --WHERE TALAO_ID IS NULL
        ";
        
        $args = [
            'LINHA_ID'     => $param->LINHA_ID,
            'TAMANHO'      => $param->TAMANHO,
            'DATA_REMESSA' => $param->DATA_REMESSA
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectFerramentaAlocacoes($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                ID,
                TIPO,
                TABELA_ID,
                REMESSA_ID,
                REMESSA,
                REMESSA_TALAO_ID,
                GP_ID,
                GP_DESCRICAO,
                UP_ID,
                UP_DESCRICAO,
                ESTACAO,
                ESTACAO_DESCRICAO,
                MODELO_ID,
                MODELO_DESCRICAO,
                COR_ID,
                COR_DESCRICAO,
                QUANTIDADE_PROGRAMADA,
                DATAHORA_INICIO,
                DATAHORA_FIM,
                CAST(TEMPO_INICIO AS INTEGER) TEMPO_INICIO,
                CAST(TEMPO_FIM AS INTEGER) TEMPO_FIM,
                MINUTOS_PROGRAMADOS MINUTOS_PROGRAMADOS,
                TEMPO_ITEM


            FROM (
                SELECT
                    P.ID,
                    P.TIPO,
                    P.TABELA_ID,
                    P.REMESSA_ID,
                    R.REMESSA,
                    T.REMESSA_TALAO_ID,
                    G.ID GP_ID,
                    G.DESCRICAO GP_DESCRICAO,
                    U.ID UP_ID,
                    U.DESCRICAO UP_DESCRICAO,
                    SU.ID ESTACAO,
                    SU.DESCRICAO ESTACAO_DESCRICAO,
                    M.CODIGO MODELO_ID,
                    M.DESCRICAO MODELO_DESCRICAO,
                    C.CODIGO COR_ID,
                    C.DESCRICAO COR_DESCRICAO,
                    P.QUANTIDADE QUANTIDADE_PROGRAMADA,
                    P.DATAHORA_INICIO,
                    P.DATAHORA_FIM,
                    (P.MINUTO_INICIO - (SELECT ((T.HORA_INICIO - CAST('00:00:00' AS TIME))/60) FROM TBTURNO T WHERE T.CODIGO = 1)) TEMPO_INICIO,
                    (P.MINUTO_FIM - (SELECT ((T.HORA_INICIO - CAST('00:00:00' AS TIME))/60) FROM TBTURNO T WHERE T.CODIGO = 1)) TEMPO_FIM,
                    P.TEMPO MINUTOS_PROGRAMADOS,
                    P.TEMPO_OPERACIONAL TEMPO_ITEM

                FROM
                    TBPROGRAMACAO P,
                    VWREMESSA_TALAO T,
                    VWREMESSA R,
                    TBGP G,
                    TBUP U,
                    TBSUB_UP SU,
                    TBPRODUTO PD,
                    TBMODELO M,
                    TBCOR C

                WHERE
                    G.ID            = P.GP_ID
                AND U.ID            = P.UP_ID
                AND SU.UP_ID        = U.ID
                AND SU.ID           = P.ESTACAO
                AND PD.CODIGO       = P.PRODUTO_ID
                AND M.CODIGO        = PD.MODELO_CODIGO
                AND C.CODIGO        = PD.COR_CODIGO
                AND R.DATA          = :DATA
                AND P.FERRAMENTA_ID = :FERRAMENTA_ID
                AND T.ID            = P.TABELA_ID
                AND p.TIPO          = 'A'
                AND R.REMESSA_ID    = T.REMESSA_ID)X
        ";
        
        $args = [
            'FERRAMENTA_ID'  => $param->FERRAMENTA_ID,
            'DATA'           => $param->DATA
        ];
        
        return $con->query($sql,$args);
    }
    
    
    public static function selectCoresSimilares($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                S.FAMILIA_ID,
                S.COR_ID_1,
                S.COR_ID_2

            FROM
                TBCOR_SIMILAR S

            WHERE                   
                S.FAMILIA_ID = :FAMILIA_ID
            AND S.COR_ID_1   = :COR_ID
        ";
        
        $args = [
            'FAMILIA_ID' => $param->FAMILIA_ID,
            'COR_ID'     => $param->COR_ID
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectModeloTempo($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT FIRST 1 
                COALESCE((
                    SELECT (SUM(MFP.TEMPO) * CAST(1 AS NUMERIC(15,4))) TEMPO
                      FROM TBMODELO_FLUXO_PRODUCAO MFP,
                           TBFLUXO_PRODUCAO FP,
                           TBFLUXO_COMPOSICAO_DETALHE D
                     WHERE FP.ID         = MFP.FLUXO_ID
                       AND FP.TIPO       = '1'
                       AND D.FLUXO_ID    = FP.ID
                       AND D.ID          = 37 -- CONFORMACAO
                       AND MFP.TEMPO     > 0
                       AND MFP.MODELO_ID = :MODELO_ID
                       AND MFP.COR_ID    = :COR_ID
                       AND MFP.TAMANHO   = :TAMANHO),0) TEMPO
            FROM
                RDB\$DATABASE
        ";
        
        $args = [
            'MODELO_ID' => $param->MODELO_ID,
            'TAMANHO'   => $param->TAMANHO,
            'COR_ID'    => $param->COR_ID
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectSkuDefeitoPercentual($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT 
                X.PERCENTUAL_DEFEITO
            FROM SPC_REMESSA_SKU_DEFEITO_PERCENT(
                :MODELO_ID,
                :COR_ID,
                :TAMANHO,
                0,3,'1') X
        ";
        
        $args = [
            'MODELO_ID' => $param->MODELO_ID,
            'TAMANHO'   => $param->TAMANHO,
            'COR_ID'    => $param->COR_ID
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectUltimoTalaoEstacao($param, _Conexao $con = null)
    {
        $sql = "
            SELECT
                GP_ID,
                UP_ID,
                ESTACAO,
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
                LINHA_ID,
                LINHA_DESCRICAO,
                MODELO_ID,
                MODELO_DESCRICAO,
                COR_ID,
                COR_DESCRICAO,
                COR_CLASSE,
                FERRAMENTA_ID,
                FERRAMENTA_DESCRICAO,
                TAMANHO,
                TAMANHO_DESCRICAO,
                QUANTIDADE,
                DATAHORA_INICIO,
                DATAHORA_FIM,
                COR_AMOSTRA_1 COR_AMOSTRA,
                IIF(COR_AMOSTRA_2 = 0,COR_AMOSTRA_1,COR_AMOSTRA_2) COR_AMOSTRA2

            FROM (
                SELECT
                    Y.*,
                    CAST(SUBSTRING(Y.COR_AMOSTRAS FROM 1 FOR 30) AS INTEGER) COR_AMOSTRA_1,
                    CAST(IIF(SUBSTRING(Y.COR_AMOSTRAS FROM 31 FOR 30) = '', 0, SUBSTRING(Y.COR_AMOSTRAS FROM 31 FOR 30)) AS INTEGER) COR_AMOSTRA_2
                FROM (


                    SELECT
                        P.GP_ID,
                        P.UP_ID,
                        P.ESTACAO,
                        PR.CODIGO    PRODUTO_ID,
                        PR.DESCRICAO PRODUTO_DESCRICAO,
                        L.CODIGO     LINHA_ID,
                        L.DESCRICAO  LINHA_DESCRICAO,
                        M.CODIGO     MODELO_ID,
                        M.DESCRICAO  MODELO_DESCRICAO,
                        C.CODIGO     COR_ID,
                        C.DESCRICAO  COR_DESCRICAO,
                        C.CLASSE||'.'||LPAD(C.SUBCLASSE,3,'0') COR_CLASSE,
                        F.ID         FERRAMENTA_ID,
                        F.DESCRICAO  FERRAMENTA_DESCRICAO,
                        P.TAMANHO,
                        TAMANHO_GRADE(PR.GRADE_CODIGO,P.TAMANHO) TAMANHO_DESCRICAO,
                        P.QUANTIDADE,
                        P.DATAHORA_INICIO,
                        P.DATAHORA_FIM,

                        COALESCE(
                            (SELECT FIRST 2 LIST(LPAD(C1.AMOSTRA,30),'')
                               FROM TBCOR C1, TBCOR_COMPOSICAO CC
                              WHERE CC.COR_ID = C.CODIGO
                                AND CC.COR_COMPOSICAO_ID = C1.CODIGO),
                             LPAD(C.AMOSTRA,30))COR_AMOSTRAS

                    FROM (
                        SELECT GP_ID,
                               UP_ID,
                               ESTACAO,
                               MAX(P.DATAHORA_INICIO) DATAHORA_INICIO
                          FROM TBPROGRAMACAO P
                         WHERE
                           --P.STATUS          < 3
                               P.FERRAMENTA_ID   > 0
                           AND P.DATAHORA_INICIO > '01.01.2005'
                           AND NOT (P.STATUS = 1 AND P.STATUS_REQUISICAO = 1)
                         GROUP BY 1,2,3
                        ) X,
                        TBPROGRAMACAO P,
                        TBPRODUTO PR,
                        TBMODELO M,
                        TBMODELO_LINHA L,
                        TBFERRAMENTARIA F,
                        TBCOR C
                    WHERE
                        P.GP_ID           = X.GP_ID
                    AND P.UP_ID           = X.UP_ID
                    AND P.ESTACAO         = X.ESTACAO
                    AND P.DATAHORA_INICIO = X.DATAHORA_INICIO
                    AND PR.CODIGO         = P.PRODUTO_ID
                    AND M.CODIGO          = PR.MODELO_CODIGO
                    AND L.CODIGO          = M.LINHA_CODIGO
                    AND C.CODIGO          = PR.COR_CODIGO
                    AND F.ID              = P.FERRAMENTA_ID
                    ) Y
                ) I
        ";
                
        return $con->query($sql);
    }
    
    public static function selectLinhaRemessaHistorico($param, _Conexao $con = null)
    {
        $sql = "
            SELECT DISTINCT
                R.REMESSA_ID,
                R.REMESSA,
                R.DATA REMESSA_DATA,
                T.GP_ID,
                G.DESCRICAO GP_DESCRICAO,
                T.ESTACAO,
                S.DESCRICAO ESTACAO_DESCRICAO
            
            FROM
                VWREMESSA R,
                VWREMESSA_TALAO T,
                TBMODELO M,
                TBGP G,
                TBGP_UP U,
                TBSUB_UP S
            
            
            WHERE
                R.DATA         >= CURRENT_DATE - 7
            AND R.REQUISICAO    = '0'
            AND T.REMESSA_ID    = R.REMESSA_ID
            AND M.CODIGO        = T.MODELO_ID
            AND G.ID            = T.GP_ID
            AND R.FAMILIA_ID    = :FAMILIA_ID
            AND M.LINHA_CODIGO  = :LINHA_ID
            AND T.TAMANHO       = :TAMANHO
            AND U.GP_ID         = G.ID
            AND S.UP_ID         = U.UP_ID
            AND S.ID            = T.ESTACAO
            
            ORDER BY REMESSA_DATA DESC
        ";
                
        $args = [
            'FAMILIA_ID' => $param->FAMILIA_ID,
            'LINHA_ID'   => $param->LINHA_ID,
            'TAMANHO'    => $param->TAMANHO
        ];
        
        return $con->query($sql,$args);
    }
    
	/**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function insertRemessa($param, _Conexao $con = null) {

		$con = $con ? $con : new _Conexao;

        $id = isset($param->ID) ? $param->ID : $con->gen_id('GTBREMESSA');
        
        $sql = "
            INSERT INTO VWREMESSA (
                REMESSA_ID,
                ESTABELECIMENTO_ID, 
                FAMILIA_ID,
                FAMILIA_ID_MP,
                TIPO,
                DATA,
                DATA_DISPONIBILIDADE,
                AMOSTRA,
                DESCRICAO
            ) VALUES (
                :REMESSA_ID,
                :ESTABELECIMENTO_ID,   
                :FAMILIA_ID,
                :FAMILIA_ID_MP,
                :TIPO,
                :DATA,
                :DATA_DISPONIBILIDADE,
                :AMOSTRA,
                'GERADO A PARTIR DO GC WEB'
            );
        ";
        
        $args = [
            'REMESSA_ID'           => $id,
            'ESTABELECIMENTO_ID'   => $param->ESTABELECIMENTO_ID,
            'FAMILIA_ID'           => $param->FAMILIA_ID,
            'FAMILIA_ID_MP'        => $param->FAMILIA_ID_MP,
            'TIPO'                 => $param->TIPO,
            'DATA'                 => $param->DATA,
            'DATA_DISPONIBILIDADE' => $param->DATA_DISPONIBILIDADE,
            'AMOSTRA'              => $param->AMOSTRA
        ];
        
        $con->query($sql,$args);
        
        return $id;
    }
    
	/**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function insertRemessaTalao($param, _Conexao $con = null) {
        
		$con = $con ? $con : new _Conexao;

        $id = isset($param->ID) ? $param->ID : $con->gen_id('GTBREMESSA_ITEM_PROCESSADO');
        
        $sql = "
            INSERT INTO TBREMESSA_ITEM_PROCESSADO (
                ID,
                REMESSA,
                CONTROLE,
                ESTABELECIMENTO_CODIGO,
                FAMILIA_CODIGO,
                MODELO_CODIGO,
                MATRIZ_CODIGO,
                PRODUTO_CODIGO,
                TAMANHO,
                QUANTIDADE,
                SITUACAO,
                PERFIL,
                PROGRAMACAO_ESTEIRA,
                PROGRAMACAO_BOCA
            ) VALUES (
                :ID,
                :REMESSA_ID,
                :REMESSA_TALAO_ID,
                :ESTABELECIMENTO_ID,
                :FAMILIA_ID,
                :MODELO_ID,
                :MATRIZ_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :QUANTIDADE,
                :STATUS,
                :PERFIL_SKU,
                :GP_ID,
                :ESTACAO
            );
        ";
        
        $args = [
            'ID'                 => $id,
            'REMESSA_ID'         => $param->REMESSA_ID,
            'REMESSA_TALAO_ID'   => $param->REMESSA_TALAO_ID,
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'FAMILIA_ID'         => $param->FAMILIA_ID,
            'MODELO_ID'          => $param->MODELO_ID,
            'MATRIZ_ID'          => $param->MATRIZ_ID,
            'PRODUTO_ID'         => $param->PRODUTO_ID,
            'TAMANHO'            => $param->TAMANHO,
            'QUANTIDADE'         => $param->QUANTIDADE,
            'STATUS'             => $param->STATUS,
            'PERFIL_SKU'         => $param->PERFIL_SKU,
            'GP_ID'              => $param->GP_ID,
            'ESTACAO'            => $param->ESTACAO,
        ];
        
        $con->query($sql,$args);
        
        
  
        $sql = "
            SELECT * FROM VWREMESSA_TALAO T WHERE T.ID = :ID
        ";
        
        $args = [
            'ID' => $id
        ];
        
        return $id;
    }
    
	/**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function insertRemessaTalaoDetalhe($param, _Conexao $con = null) {
        
		$con = $con ? $con : new _Conexao;

        $id = isset($param->ID) ? $param->ID : $con->gen_id('GERADOR1_ID');
        
        $sql = "
            INSERT INTO TBPEDIDO_ITEM_PROCESSADO (
                ESTABELECIMENTO_CODIGO,
                CONTROLE,
                FAMILIA_CODIGO,
                MODELO_CODIGO,
                COR_ID,
                REMESSA,
                REMESSA_ACUMULADO_CONTROLE,
                PRODUTO_CODIGO,
                TAMANHO,
                QUANTIDADE_PEDIDO,
                QUANTIDADE_PRODUCAO,
                PERFIL,
                PROGRAMACAO_ESTEIRA,
                PROGRAMACAO_BOCA,
                LOCALIZACAO_CODIGO,
                SITUACAO,
                DATA_PROCESSAMENTO,
                PEDIDO,
                PEDIDO_ITEM_CONTROLE,
                CLIENTE_CODIGO,
                PECA_CONJUNTO,
                SETOR_CODIGO
            ) VALUES (
                :ESTABELECIMENTO_ID,
                :REMESSA_TALAO_DETALHE_ID,
                :FAMILIA_ID,
                :MODELO_ID,
                :COR_ID,
                :REMESSA_ID,
                :REMESSA_TALAO_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :QUANTIDADE_PEDIDO,
                :QUANTIDADE_PRODUCAO,
                :PERFIL_SKU,
                :GP_ID,
                :ESTACAO,
                :LOCALIZACAO_ID,
                '1',
                CURRENT_DATE,
                0,
                0,
                0,
                0,
                0
            );
        ";
        
        $args = [
            'REMESSA_TALAO_DETALHE_ID' => $id                             ,
            'ESTABELECIMENTO_ID'       => $param->ESTABELECIMENTO_ID      ,
            'FAMILIA_ID'               => $param->FAMILIA_ID              ,
            'MODELO_ID'                => $param->MODELO_ID               ,
            'COR_ID'                   => $param->COR_ID                  ,
            'REMESSA_ID'               => $param->REMESSA_ID              ,
            'REMESSA_TALAO_ID'         => $param->REMESSA_TALAO_ID        ,
            'PRODUTO_ID'               => $param->PRODUTO_ID              ,
            'TAMANHO'                  => $param->TAMANHO                 ,
            'QUANTIDADE_PEDIDO'        => $param->QUANTIDADE              ,
            'QUANTIDADE_PRODUCAO'      => $param->QUANTIDADE              ,
            'PERFIL_SKU'               => $param->PERFIL_SKU              ,
            'GP_ID'                    => $param->GP_ID                   ,
            'ESTACAO'                  => $param->ESTACAO                 ,
            'LOCALIZACAO_ID'           => $param->LOCALIZACAO_ID          ,
        ];
        
        $con->query($sql,$args);
        
        return $id;
    }
    
	/**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function insertPedidoAlocacao($param, _Conexao $con = null) {
        
		$con = $con ? $con : new _Conexao;

        $id = isset($param->ID) ? $param->ID : $con->gen_id('GTBREMESSA_ITEM_ALOCACAO');
        
        $sql = "
            INSERT INTO TBREMESSA_ITEM_ALOCACAO (   
                ID,
                ESTABELECIMENTO_ID,
                TABELA_ID,
                TAB_ITEM_ID,
                PRODUTO_ID,
                TAMANHO,
                LOCALIZACAO_ID,
                REMESSA,
                TALAO,
                AGRUPAMENTO_ID,
                AGRUP_PED_ID,
                TIPO,
                QUANTIDADE,
                SALDO
            ) VALUES (     
                :ID,
                :ESTABELECIMENTO_ID,
                :PEDIDO_ID,
                :PEDIDO_ITEM_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :LOCALIZACAO_ID,
                :REMESSA_ID,
                :REMESSA_TALAO_DETALHE_ID,
                :AGRUPAMENTO_ID,
                :AGRUPAMENTO_PEDIDO_ID,
                :TIPO,
                :QUANTIDADE,
                :SALDO
            );
        ";
        
        $args = [
            'ID'					   => $id,
            'ESTABELECIMENTO_ID'       => $param->ESTABELECIMENTO_ID,
            'PEDIDO_ID'                => $param->PEDIDO_ID,
            'PEDIDO_ITEM_ID'           => $param->PEDIDO_ITEM_ID,
            'PRODUTO_ID'               => $param->PRODUTO_ID,
            'TAMANHO'                  => $param->TAMANHO,
            'LOCALIZACAO_ID'           => $param->LOCALIZACAO_ID,
            'REMESSA_ID'               => $param->REMESSA_ID,
            'REMESSA_TALAO_DETALHE_ID' => $param->REMESSA_TALAO_DETALHE_ID,
            'AGRUPAMENTO_ID'           => $param->AGRUPAMENTO_ID,
            'AGRUPAMENTO_PEDIDO_ID'    => $param->AGRUPAMENTO_PEDIDO_ID,
            'TIPO'                     => $param->TIPO,
            'QUANTIDADE'               => $param->QUANTIDADE,
            'SALDO'                    => $param->QUANTIDADE,
        ];
        
        $con->query($sql,$args);
        
        return $id;
    }
    
    public static function insertProgramacao($param, _Conexao $con = null) {
        $con = $con ? $con : new _Conexao;

        $sql = "
            EXECUTE PROCEDURE SPI_PROGRAMACAO_BOJO(
                :ESTABELECIMENTO_ID,
                :REMESSA_ID,
                :DATA_PRODUCAO,
                :GP_ID,
                :UP_ID,
                :ESTACAO,
                :TALAO_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :FERRAMENTA_ID,
                :QUANTIDADE,
                :TEMPO_OPERACIONAL,
                :TEMPO_SETUP_COR,
                :TEMPO_SETUP_APROVACAO,
                :TEMPO_SETUP_FERRAMENTA,
                :TEMPO_SETUP_AQUECIMENTO,
                :MINUTO_INICIO,
                :MINUTO_FIM
            );
        ";

        $args = [
            'ESTABELECIMENTO_ID'      => $param->ESTABELECIMENTO_ID,
            'REMESSA_ID'              => $param->REMESSA_ID,
            'DATA_PRODUCAO'           => $param->DATA_PRODUCAO,
            'GP_ID'                   => $param->GP_ID,
            'UP_ID'                   => $param->UP_ID,
            'ESTACAO'                 => $param->ESTACAO,
            'TALAO_ID'                => $param->TALAO_ID,
            'PRODUTO_ID'              => $param->PRODUTO_ID,
            'TAMANHO'                 => $param->TAMANHO,
            'FERRAMENTA_ID'           => $param->FERRAMENTA_ID,
            'QUANTIDADE'              => $param->QUANTIDADE,
            'TEMPO_OPERACIONAL'       => $param->TEMPO_OPERACIONAL,
            'TEMPO_SETUP_COR'         => $param->TEMPO_SETUP_COR,
            'TEMPO_SETUP_APROVACAO'   => $param->TEMPO_SETUP_APROVACAO,
            'TEMPO_SETUP_FERRAMENTA'  => $param->TEMPO_SETUP_FERRAMENTA,
            'TEMPO_SETUP_AQUECIMENTO' => $param->TEMPO_SETUP_AQUECIMENTO,
            'MINUTO_INICIO'           => $param->MINUTO_INICIO,
            'MINUTO_FIM'              => $param->MINUTO_FIM,
        ];

        $con->execute($sql, $args);
    }
    
    public static function insertPedidoBloqueio($param, _Conexao $con = null) {
        $con = $con ? $con : new _Conexao;

        $sql = "
            UPDATE OR INSERT INTO TBREMESSA_ITEM_TRATAMENTO (
                ESTABELECIMENTO_ID, 
                TABELA_ID, 
                TAB_ITEM_ID, 
                TAMANHO,
                STATUS
            ) VALUES (
                :ESTABELECIMENTO_ID, 
                :TABELA_ID, 
                :TAB_ITEM_ID, 
                :TAMANHO,
                1                
            ) MATCHING (
                ESTABELECIMENTO_ID,
                TABELA_ID,
                TAB_ITEM_ID,
                TAMANHO
            ) ;
        ";

        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'TABELA_ID'          => $param->TABELA_ID,
            'TAB_ITEM_ID'        => $param->TAB_ITEM_ID,
            'TAMANHO'            => $param->TAMANHO,
        ];

        $con->execute($sql, $args);
    }
    
    public static function insertPedidoDesbloqueio($param, _Conexao $con = null) {
        $con = $con ? $con : new _Conexao;

        $sql = "
            UPDATE TBREMESSA_ITEM_TRATAMENTO T
               SET T.STATUS = '0'
             WHERE T.USUARIO = :USUARIO
        ";

        $args = [
            'USUARIO' => setDefValue($param->USUARIO, \Auth::user()->USUARIO)
        ];

        $con->execute($sql, $args);
    }
    
    public static function selectPedidoBloqueioUsuario($param = null, _Conexao $con = null) {
        $con = $con ? $con : new _Conexao;
        $sql = "
            SELECT DISTINCT T.USUARIO
              FROM TBREMESSA_ITEM_TRATAMENTO T
             WHERE T.STATUS = '1'
        ";

        return $con->query($sql);
    }
    
    public static function spPedidoItemIntegridade($param = null, _Conexao $con = null) {
        
        $sql = "
            EXECUTE PROCEDURE SP_PEDIDO_ITEM_INTEGRIDADE_V2
        ";

        return $con->query($sql);
    }
	
}