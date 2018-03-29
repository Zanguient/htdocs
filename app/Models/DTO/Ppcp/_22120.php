<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22120DAO;

/**
 * Objeto _22120 - Estrutura Analítica de Remessas
 */
class _22120
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    

    public function selectRemessasVinculo($param) {       
        
        $sql =
        "
            SELECT DISTINCT
                R1.REMESSA_ID REMESSA_ID_ORIGEM,
                R1.REMESSA REMESSA_ORIGEM,
                R2.REMESSA,
                V.REMESSA_ID

            FROM
                VWREMESSA R1,
                VWREMESSA_CONSUMO C,
                TBREMESSA_CONSUMO_VINCULO V,
                VWREMESSA R2

            WHERE TRUE
            AND R1.REMESSA_ID = C.REMESSA_ID
            AND V.CONSUMO_ID = C.ID
            AND R2.REMESSA_ID = V.REMESSA_ID
            /*@REMESSA_ID*/
            /*@REMESSA*/
            
        ";
        
        $remessa_id = isset($param->REMESSA_ID) && $param->REMESSA_ID     > -1 ? "AND R1.REMESSA_ID =  " . $param->REMESSA_ID    : '';
        $remessa    = isset($param->REMESSA   ) && trim($param->REMESSA) != '' ? "AND R1.REMESSA    = '" . $param->REMESSA . "'" : '';
        
        $args = [
            '@REMESSA_ID' => $remessa_id,
            '@REMESSA'    => $remessa
        ]; 
        

        return $this->con->query($sql,$args); 
    }

    public function selectTaloesVinculo($param) {       
        
        $sql =
        "
            SELECT
                P2.DESCRICAO TALAO_PRODUTO_DESCRICAO,
                TRIM(S.PERFIL) TALAO_PERFIL_SKU,
                PF.DESCRICAO TALAO_PERFIL_SKU_DESCRICAO,
                C.CLASSE TALAO_COR_CLASSE,
                C.SUBCLASSE TALAO_COR_SUBCLASSE,
                X.*
            FROM (
                SELECT
                    FN_LPAD(T2.REMESSA_TALAO_ID,4,0) TALAO_CONTROLE,
                    T2.MODELO_ID TALAO_MODELO_ID,
                    M.DESCRICAO TALAO_MODELO_DESCRICAO,
                    (SELECT FIRST 1 TAMANHO FROM VWREMESSA_TALAO_DETALHE D WHERE D.REMESSA_ID = V.REMESSA_ID AND D.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID) TALAO_TAMANHO,
                    (SELECT FIRST 1 PRODUTO_ID FROM VWREMESSA_TALAO_DETALHE D WHERE D.REMESSA_ID = V.REMESSA_ID AND D.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID) TALAO_PRODUTO_ID,
                    FN_TAMANHO_GRADE(M.GRADE_CODIGO,(SELECT FIRST 1 TAMANHO FROM VWREMESSA_TALAO_DETALHE D WHERE D.REMESSA_ID = V.REMESSA_ID AND D.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID)) TALAO_TAMANHO_DESCRICAO,
                    SUM(T1.QUANTIDADE) OVER (PARTITION BY V.REMESSA_TALAO_ID) TALAO_QUANTIDADE,
                    P.DESCRICAO PRODUTO_DESCRICAO,
                    FN_TAMANHO_GRADE(P.GRADE_CODIGO,T1.TAMANHO) TAMANHO_DESCRICAO,                    
                    P.UNIDADEMEDIDA_SIGLA UM,
                    MT.LARGURA,
                    MT.COMPRIMENTO,
                    C2.CLASSE COR_CLASSE,
                    C2.SUBCLASSE COR_SUBCLASSE,
                    T1.*
    
                FROM
                    VWREMESSA_TALAO T1,
                    VWREMESSA_CONSUMO C,
                    TBREMESSA_CONSUMO_VINCULO V,
                    VWREMESSA_TALAO T2,
                    TBPRODUTO P,
                    TBMODELO M,
                    TBMODELO M2,
                    SPC_REMESSA_CONSUMO_MATRIZ(M2.MATRIZ_CODIGO,M2.GRADE_CODIGO,T1.TAMANHO) MT,
                    TBCOR C2
    
                WHERE
                    T1.REMESSA_ID = C.REMESSA_ID
                AND T1.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                AND V.CONSUMO_ID = C.ID
                AND T2.REMESSA_ID = V.REMESSA_ID
                AND T2.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID
                AND P.CODIGO = T1.PRODUTO_ID
                AND M.CODIGO = T2.MODELO_ID
                AND M2.CODIGO = P.MODELO_CODIGO
                AND C2.CODIGO = P.COR_CODIGO
                AND T1.REMESSA_ID = :REMESSA_ID_ORIGEM
                AND T2.REMESSA_ID = :REMESSA_ID
                ) X,
                TBPRODUTO P2,
                VWSKU S,
                TBPERFIL PF,
                TBCOR C
            WHERE
                P2.CODIGO = TALAO_PRODUTO_ID
            AND S.MODELO_ID = P2.MODELO_CODIGO
            AND S.COR_ID = P2.COR_CODIGO
            AND S.TAMANHO = TALAO_TAMANHO
            AND PF.TABELA = 'SKU'
            AND PF.ID = S.PERFIL
            AND C.CODIGO = P2.COR_CODIGO

        ";
        
        $args = [
            'REMESSA_ID_ORIGEM' => $param->REMESSA_ID_ORIGEM,
            'REMESSA_ID'        => $param->REMESSA_ID
        ]; 
        

        return $this->con->query($sql,$args); 
    }

    public function selectConsumoPerfil($param) {       
        
        $sql =
        "
            SELECT DISTINCT
                B.PERFIL

            FROM
                VWREMESSA_CONSUMO C,
                TBPRODUTO P,
                TBMODELO_BLOQUEIO B,
                VWREMESSA R

            WHERE
                C.PRODUTO_ID = P.CODIGO
            AND B.MODELO_ID  = P.MODELO_CODIGO
            AND B.COR_ID     = P.COR_CODIGO
            AND B.TAMANHO    = C.TAMANHO
            AND C.REMESSA_ID = R.REMESSA_ID
            /*@REMESSA*/
            /*@REMESSA_ID*/
        ";

        $remessa_id = isset($param->REMESSA_ID) ? "AND R.REMESSA_ID IN  (" . Helpers::arrayToList($param->REMESSA_ID, 999999999) . ")" : '';
        $remessa    = isset($param->REMESSA   ) ? "AND R.REMESSA    IN  ('" . Helpers::arrayToList($param->REMESSA  , 999999999) . "')" : '';

        $args = [
            '@REMESSA'    => $remessa,
            '@REMESSA_ID' => $remessa_id
        ];

        return $this->con->query($sql,$args);
    }

    public function selectConsumoFamilia($param) {       
        
        $sql =
        "
            SELECT DISTINCT
                R.REMESSA_ID,
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO,
                LPAD(R.ESTABELECIMENTO_ID, 3, '0') ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = R.ESTABELECIMENTO_ID) ESTABELECIMENTO_DESCRICAO,
                TRIM(R.REQUISICAO) REQUISICAO

            FROM
                VWREMESSA_CONSUMO C,
                TBPRODUTO P,
                TBFAMILIA F,
                VWREMESSA R

            WHERE
                P.CODIGO     = C.PRODUTO_ID
            AND F.CODIGO     = P.FAMILIA_CODIGO
            AND R.REMESSA_ID = C.REMESSA_ID
            AND R.REMESSA    = :REMESSA
        ";

        $args = [
            ':REMESSA' => $param->REMESSA
        ];

        return $this->con->query($sql,$args);
    }

    public function selectPedidoFamilia($param) {       
        
        $sql =
        "
            SELECT FIRST 1
                ('PD' || P.PEDIDO) REMESSA,
                P.PEDIDO REMESSA_ID,
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO FAMILIA_DESCRICAO,
                LPAD(P.ESTABELECIMENTO_CODIGO, 3, '0') ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = P.ESTABELECIMENTO_CODIGO) ESTABELECIMENTO_DESCRICAO,
                TRIM('3') REQUISICAO

            FROM
                TBPEDIDO P,
                TBPEDIDO_ITEM I,
                TBPRODUTO PR,
                TBFAMILIA F

            WHERE
                 I.PEDIDO   = P.PEDIDO
            AND PR.CODIGO   = I.PRODUTO_CODIGO
            AND  F.CODIGO   = PR.FAMILIA_CODIGO
            AND  I.SITUACAO = '1'
            AND  P.SITUACAO = '1'
            AND  P.STATUS   = '1'
            AND  P.PEDIDO   = :PEDIDO
        ";

        $args = [
            'PEDIDO' => $param->PEDIDO
        ];

        return $this->con->query($sql,$args);
    }
      
    public function selectReposicaoFamilia($param = null) {       
        
        $sql =
        "
            SELECT DISTINCT
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO,
                LPAD(1, 3, '0')  ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = 1) ESTABELECIMENTO_DESCRICAO,
                TRIM('0') REQUISICAO

            FROM
                TBFAMILIA F

            WHERE F.HABILITA_REPOSICAO = '1'
        ";


        return $this->con->query($sql);
    }

    public function selectRequisicaoFamilia($param = null) {       
        
        $sql = "
            SELECT DISTINCT
                F.CODIGO FAMILIA_ID,
                F.DESCRICAO,
                LPAD(C.ESTABELECIMENTO_ID, 3, '0') ESTABELECIMENTO_ID,
                (SELECT E.NOMEFANTASIA FROM TBESTABELECIMENTO E WHERE E.CODIGO = C.ESTABELECIMENTO_ID) ESTABELECIMENTO_DESCRICAO

            FROM
                TBREQUISICAO C,
                TBPRODUTO P,
                TBFAMILIA F

            WHERE
                P.CODIGO     = C.PRODUTO_ID
            AND F.CODIGO     = P.FAMILIA_CODIGO
            AND C.REMESSA_GERADA = 0
            AND C.CONSUMO = 1 ";


        return $this->con->query($sql);
    }

    public function selectConsumoNecessidade($param) {       

        $sql =
        "
            SELECT
                'NORMAL' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                Y.QUANTIDADE,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.ACRESCIMO,
                Y.UM,
                Y.LOCALIZACAO_ID,
                (SELECT FIRST 1
                    IIF(Y.TAMANHO =  1,M.T01, IIF(Y.TAMANHO =  2,M.T02, IIF(Y.TAMANHO =  3,M.T03, IIF(Y.TAMANHO =  4,M.T04, IIF(Y.TAMANHO =  5,M.T05,
                    IIF(Y.TAMANHO =  6,M.T06, IIF(Y.TAMANHO =  7,M.T07, IIF(Y.TAMANHO =  8,M.T08, IIF(Y.TAMANHO =  9,M.T09, IIF(Y.TAMANHO = 10,M.T10,
                    IIF(Y.TAMANHO = 11,M.T11, IIF(Y.TAMANHO = 12,M.T12, IIF(Y.TAMANHO = 13,M.T13, IIF(Y.TAMANHO = 14,M.T14, IIF(Y.TAMANHO = 15,M.T15,
                    IIF(Y.TAMANHO = 16,M.T16, IIF(Y.TAMANHO = 17,M.T17, IIF(Y.TAMANHO = 18,M.T18, IIF(Y.TAMANHO = 19,M.T19, IIF(Y.TAMANHO = 20,M.T20, 0)))))))))))))))))))) QUEBRA
                    FROM TBMODELO_REMESSA_COTA M WHERE M.MODELO_CODIGO = Y.MODELO_ID) FATOR_DIVISAO,
                    
                (SELECT
                    CASE Y.TAMANHO
                        WHEN 01 THEN PC.MI01
                        WHEN 02 THEN PC.MI02
                        WHEN 03 THEN PC.MI03
                        WHEN 04 THEN PC.MI04
                        WHEN 05 THEN PC.MI05
                        WHEN 06 THEN PC.MI06
                        WHEN 07 THEN PC.MI07
                        WHEN 08 THEN PC.MI08
                        WHEN 09 THEN PC.MI09
                        WHEN 10 THEN PC.MI10
                        WHEN 11 THEN PC.MI11
                        WHEN 12 THEN PC.MI12
                        WHEN 13 THEN PC.MI13
                        WHEN 14 THEN PC.MI14
                        WHEN 15 THEN PC.MI15
                        WHEN 16 THEN PC.MI16
                        WHEN 17 THEN PC.MI17
                        WHEN 18 THEN PC.MI18
                        WHEN 19 THEN PC.MI19
                        WHEN 20 THEN PC.MI20
                        ELSE 99999999999
                    END
                 FROM TBMODELO_PEDIDO_COTA PC WHERE PC.MODELO_ID = Y.MODELO_ID) FATOR_DIVISAO_DETALHE

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,
                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,
                    AVG(X.ACRESCIMO) ACRESCIMO,
                    SUM(X.QUANTIDADE_ALTERNATIVA) QUANTIDADE_ALTERNATIVA,

                    IIF(:REQUISICAO = '1',
                        IIF( (SELECT FIRST 1 U.VALOR_EXT --VERIFICA SE REQUISICAO INCLUI ACRESCIMO
                                FROM TBCONTROLE_N U
                               WHERE U.ID = 229) = '1',
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF((SUM(X.QUANTIDADE) + (AVG(X.ACRESCIMO)/2)) < 1, 1,(SUM(X.QUANTIDADE) + (AVG(X.ACRESCIMO)/2))), --INCLUI ACRESCIMO E PERMITE ARREND.
                                    (SUM(X.QUANTIDADE) + (AVG(X.ACRESCIMO)/2))),                                                       --INCLUI ACRESCIMO E NAO PERMITE ARREND.
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF(SUM(X.QUANTIDADE) < 1, 1,SUM(X.QUANTIDADE)),                                           --NAO INCLUI ACRESCIMO E PERMITE ARREND.
                                    SUM(X.QUANTIDADE))),                                                                       --NAO INCLUI ACRESCIMO E NAO PERMITE ARREND.

                        IIF( (SELECT FIRST 1 U.VALOR_EXT --VERIFICA SE REMESSA NORMAL INCLUI ACRESCIMO
                                FROM TBCONTROLE_N U
                               WHERE U.ID = 230) = '1',
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF((SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO)) < 1, 1,(SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO))), --INCLUI ACRESCIMO E PERMITE ARREND.
                                    (SUM(X.QUANTIDADE) + AVG(X.ACRESCIMO))),                                                   --INCLUI ACRESCIMO E NAO PERMITE ARREND.
                               IIF( (SELECT FIRST 1 POSITION (AVG(X.FAMILIA_ID) IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                       FROM TBCONTROLE_N U
                                      WHERE U.ID = 231) > 0,
                                    IIF(SUM(X.QUANTIDADE) < 1, 1,SUM(X.QUANTIDADE)),                                           --NAO INCLUI ACRESCIMO E PERMITE ARREND.
                                    SUM(X.QUANTIDADE)))) QUANTIDADE                                                            --NAO INCLUI ACRESCIMO E NAO PERMITE ARREND.


                FROM
                    (
                    SELECT                                                                    
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.ID) ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.REMESSA_TALAO_ID) REMESSA_TALAO_ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.REMESSA_TALAO_DETALHE_ID) REMESSA_TALAO_DETALHE_ID,   
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.CONTROLE) CONTROLE,
                        C.DENSIDADE,
                        C.ESPESSURA,
                        (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                        LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                        P.DESCRICAO           PRODUTO_DESCRICAO,
                        P.GRADE_CODIGO        GRADE_ID,
                        COALESCE(C.TAMANHO,0)TAMANHO,
                        (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                        C.QUANTIDADE_SALDO QUANTIDADE,
                        C.QUANTIDADE_ALTERNATIVA_SALDO QUANTIDADE_ALTERNATIVA,
                        COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                        P.UNIDADEMEDIDA_SIGLA UM,
                        P.MODELO_CODIGO MODELO_ID,
                        M.DESCRICAO MODELO_DESCRICAO,
                        M.PRIORIDADE MODELO_PRIORIDADE,
                        P.COR_CODIGO COR_ID,
                        (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                        P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                        (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO) PERFIL_SKU,
                        F.CODIGO FAMILIA_ID
                    FROM
                        VWREMESSA_CONSUMO C,
                        TBPRODUTO P,
                        TBFAMILIA F,
                        TBMODELO M,
                        VWREMESSA R
                    WHERE
                        C.PRODUTO_ID     = P.CODIGO
                    AND F.CODIGO         = P.FAMILIA_CODIGO
                    AND M.CODIGO         = P.MODELO_CODIGO
                    AND R.REMESSA_ID     = C.REMESSA_ID
                    /*@REMESSA_ID*/
                    /*@REMESSA_TALAO_ID*/
                    /*@FAMILIA_ID*/
                    AND C.STATUS = '0'
                    ) X

                    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22
                )Y
            /*@ORDER_COMPONENTE*/
        ";
        

        $remessa_id       = isset($param->REMESSA_ID      ) ? "AND C.REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 999999999) . ")" : '';
        $remessa_talao_id = isset($param->REMESSA_TALAO_ID) ? "AND C.REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 999999999) . ")" : '';
        $familia_id       = isset($param->FAMILIA_ID      ) ? "AND P.FAMILIA_CODIGO   IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")" : '';
        $requisicao       = (isset($param->REQUISICAO) && ($param->REQUISICAO == '0')) ? '0' : '1';
        
        $order_componente = strstr($param->REMESSA, '1D') ? 'ORDER BY MODELO_PRIORIDADE, ID' : 'ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID';

        $args = [
            ':REQUISICAO'		=> $requisicao,
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id,
            '@FAMILIA_ID'       => $familia_id,
            '@ORDER_COMPONENTE' => $order_componente
        ];
        
        return $this->con->query($sql,$args);
    }

    public function selectPedidoNecessidade($param) {       
     
        
        $sql =
        "
            SELECT
                'PEDIDO' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                Y.QUANTIDADE,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.UM,
                Y.LOCALIZACAO_ID,
                Y.FATOR_DIVISAO,
                Y.FATOR_DIVISAO_DETALHE,
                Y.CLIENTE_ID

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,
                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,
                    X.QUANTIDADE,
                    X.QUANTIDADE_ALTERNATIVA,
                    X.CLIENTE_ID,
                    COALESCE(
                        (SELECT FIRST 1
                                IIF(X.TAMANHO =  1,M.T01, IIF(X.TAMANHO =  2,M.T02, IIF(X.TAMANHO =  3,M.T03, IIF(X.TAMANHO =  4,M.T04, IIF(X.TAMANHO =  5,M.T05,
                                IIF(X.TAMANHO =  6,M.T06, IIF(X.TAMANHO =  7,M.T07, IIF(X.TAMANHO =  8,M.T08, IIF(X.TAMANHO =  9,M.T09, IIF(X.TAMANHO = 10,M.T10,
                                IIF(X.TAMANHO = 11,M.T11, IIF(X.TAMANHO = 12,M.T12, IIF(X.TAMANHO = 13,M.T13, IIF(X.TAMANHO = 14,M.T14, IIF(X.TAMANHO = 15,M.T15,
                                IIF(X.TAMANHO = 16,M.T16, IIF(X.TAMANHO = 17,M.T17, IIF(X.TAMANHO = 18,M.T18, IIF(X.TAMANHO = 19,M.T19, IIF(X.TAMANHO = 20,M.T20, 0)))))))))))))))))))) QUEBRA
                                FROM TBMODELO_REMESSA_COTA M WHERE M.MODELO_CODIGO = X.MODELO_ID),0) FATOR_DIVISAO,
                    COALESCE(
                        (SELECT FIRST 1 COTA
                           FROM TBCLIENTE_MODELO_PRECO CMP
                          WHERE CMP.CLIENTE_CODIGO = X.CLIENTE_ID
                            AND CMP.MODELO_CODIGO  = X.MODELO_ID),
                        (SELECT
                            CASE X.TAMANHO
                                WHEN 01 THEN PC.MI01
                                WHEN 02 THEN PC.MI02
                                WHEN 03 THEN PC.MI03
                                WHEN 04 THEN PC.MI04
                                WHEN 05 THEN PC.MI05
                                WHEN 06 THEN PC.MI06
                                WHEN 07 THEN PC.MI07
                                WHEN 08 THEN PC.MI08
                                WHEN 09 THEN PC.MI09
                                WHEN 10 THEN PC.MI10
                                WHEN 11 THEN PC.MI11
                                WHEN 12 THEN PC.MI12
                                WHEN 13 THEN PC.MI13
                                WHEN 14 THEN PC.MI14
                                WHEN 15 THEN PC.MI15
                                WHEN 16 THEN PC.MI16
                                WHEN 17 THEN PC.MI17
                                WHEN 18 THEN PC.MI18
                                WHEN 19 THEN PC.MI19
                                WHEN 20 THEN PC.MI20
                                ELSE 99999999999
                            END
                         FROM TBMODELO_PEDIDO_COTA PC WHERE PC.MODELO_ID = X.MODELO_ID)) FATOR_DIVISAO_DETALHE
                FROM
                    (
                        SELECT
                            C.PEDIDO ID,
                            C.PEDIDO REMESSA_TALAO_ID,
                            NULL REMESSA_TALAO_DETALHE_ID,
                            C.PEDIDO CONTROLE,
                            M.DENSIDADE,
                            M.ESPESSURA,
                            (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                            LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                            P.DESCRICAO           PRODUTO_DESCRICAO,
                            P.GRADE_CODIGO        GRADE_ID,
                            COALESCE(C.TAMANHO,0)TAMANHO,
                            (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                            C.QUANTIDADE,
                            0 QUANTIDADE_ALTERNATIVA,
                            P.UNIDADEMEDIDA_SIGLA UM,
                            P.MODELO_CODIGO MODELO_ID,
                            M.DESCRICAO MODELO_DESCRICAO,
                            M.PRIORIDADE MODELO_PRIORIDADE,
                            P.COR_CODIGO COR_ID,
                            (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                            P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                            C.CLIENTE_CODIGO CLIENTE_ID,
                            (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO)
                               FROM VWSKU S, TBPERFIL PER
                              WHERE PER.TABELA  = 'SKU'
                                AND PER.ID      = S.PERFIL
                                AND S.MODELO_ID = P.MODELO_CODIGO
                                AND S.COR_ID    = P.COR_CODIGO
                                AND S.TAMANHO   = C.TAMANHO) PERFIL_SKU
                        FROM
                            TBPEDIDO_ITEM C,
                            TBPEDIDO_ITEM_SALDO S,
                            TBPRODUTO P,
                            TBFAMILIA F,
                            TBMODELO M
                        WHERE
                            C.CONTROLE       = S.PEDIDO_ITEM_CONTROLE
                        AND M.CODIGO         = P.MODELO_CODIGO
                        AND C.PRODUTO_CODIGO = P.CODIGO
                        AND F.CODIGO         = P.FAMILIA_CODIGO     
                        AND C.SITUACAO       = '1'
                        AND M.GERAR_REMESSA  = '1'
                        /*@FAMILIA_ID*/
                        /*@PEDIDO_ID*/
                    ) X
                )Y
            ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID
        ";

        $pedido       = isset($param->PEDIDO      ) ? "AND C.PEDIDO IN (" . arrayToList($param->PEDIDO      , 999999999) . ")" : '';
        
        $familia_id       = isset($param->FAMILIA_ID      ) ? "AND P.FAMILIA_CODIGO   IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")" : '';
        
        $args = [
            '@FAMILIA_ID' => $familia_id,
            '@PEDIDO_ID'  => $pedido
        ];        
        

        return $this->con->query($sql,$args);
    }
      
    public function selectReposicaoNecessidade($param = null) {       
        
        $sql =
        "
            SELECT
                'REPOSICAO' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                ( (SELECT VALOR2 FROM ARREDONDAR_PRA_CIMA(Y.QUANTIDADE / Y.FATOR_DIVISAO)) * Y.FATOR_DIVISAO ) QUANTIDADE,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.UM,
                Y.LOCALIZACAO_ID,
                Y.FATOR_DIVISAO,
                Y.FATOR_DIVISAO_DETALHE,
                ( (SELECT VALOR2 FROM ARREDONDAR_PRA_CIMA(Y.QUANTIDADE / Y.FATOR_DIVISAO)) ) QUANTIDADE_TALOES

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,

                    (SELECT IIF(CT.QUEBRA_TAMANHO = 0, CT.QUEBRA_QUANTIDADE, CT.QUEBRA_TAMANHO) QUEBRA
                       FROM (SELECT FIRST 1
                                M.QUANTIDADE QUEBRA_QUANTIDADE,
                                COALESCE(IIF(X.TAMANHO =  1,M.T01, IIF(X.TAMANHO =  2,M.T02, IIF(X.TAMANHO =  3,M.T03, IIF(X.TAMANHO =  4,M.T04, IIF(X.TAMANHO =  5,M.T05,
                                         IIF(X.TAMANHO =  6,M.T06, IIF(X.TAMANHO =  7,M.T07, IIF(X.TAMANHO =  8,M.T08, IIF(X.TAMANHO =  9,M.T09, IIF(X.TAMANHO = 10,M.T10,
                                         IIF(X.TAMANHO = 11,M.T11, IIF(X.TAMANHO = 12,M.T12, IIF(X.TAMANHO = 13,M.T13, IIF(X.TAMANHO = 14,M.T14, IIF(X.TAMANHO = 15,M.T15,
                                         IIF(X.TAMANHO = 16,M.T16, IIF(X.TAMANHO = 17,M.T17, IIF(X.TAMANHO = 18,M.T18, IIF(X.TAMANHO = 19,M.T19, IIF(X.TAMANHO = 20,M.T20, 0)))))))))))))))))))),0) QUEBRA_TAMANHO
                               FROM TBMODELO_REMESSA_COTA M
                              WHERE M.MODELO_CODIGO = X.MODELO_ID)CT) FATOR_DIVISAO,

                    (SELECT
                        CASE X.TAMANHO
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
                            ELSE 99999999999
                        END
                     FROM TBMODELO_PRODUCAO_COTA PC WHERE PC.MODELO_CODIGO = X.MODELO_ID) FATOR_DIVISAO_DETALHE,

                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,
                    SUM(X.QUANTIDADE) QUANTIDADE,
                    SUM(X.QUANTIDADE_ALTERNATIVA) QUANTIDADE_ALTERNATIVA
                FROM
                    (
                    SELECT                                                                    
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.LOCALIZACAO_ID||C.PRODUTO_ID) ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.LOCALIZACAO_ID||C.PRODUTO_ID) REMESSA_TALAO_ID,
                        NULL REMESSA_TALAO_DETALHE_ID,
                        IIF(F.CONTROLE_TALAO = 'A',NULL,C.LOCALIZACAO_ID||C.PRODUTO_ID) CONTROLE,
                        C.DENSIDADE,
                        C.ESPESSURA,
                        (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                        LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                        P.DESCRICAO           PRODUTO_DESCRICAO,
                        P.GRADE_CODIGO        GRADE_ID,
                        COALESCE(C.TAMANHO_ID,0)TAMANHO,
                        (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO_ID))TAMANHO_DESCRICAO,
                        C.NECESSIDADE QUANTIDADE,
                        0 QUANTIDADE_ALTERNATIVA,
                        COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                        P.UNIDADEMEDIDA_SIGLA UM,
                        P.MODELO_CODIGO MODELO_ID,
                        M.DESCRICAO MODELO_DESCRICAO,
                        M.PRIORIDADE MODELO_PRIORIDADE,
                        P.COR_CODIGO COR_ID,
                        (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                        P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                        (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO_ID) PERFIL_SKU
                    FROM
                        (SELECT * FROM SPC_PROJECAO_REMESSA1 (:ESTABELECIMENTO_ID,:FAMILIA_ID)) C ,
                        TBPRODUTO P,
                        TBFAMILIA F,
                        TBMODELO M
                    WHERE
                        C.PRODUTO_ID     = P.CODIGO
                    AND F.CODIGO         = P.FAMILIA_CODIGO
                    AND M.CODIGO         = P.MODELO_CODIGO
                    AND C.TAMANHO_ID > 0
                    /*@REMESSA_ID*/
                    /*@REMESSA_TALAO_ID*/
                    /*@FAMILIA_ID*/
                    ) X

                    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24
                )Y
            ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID
        ";

        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'FAMILIA_ID'         => $param->FAMILIA_ID
        ];

        return $this->con->query($sql,$args);
    }

    public function selectRequisicaoNecessidade($param = null) {       
        
        $sql =
        "
            SELECT
                'REQUISICAO' TIPO,
                Y.ID,
                Y.REMESSA_TALAO_ID,
                Y.REMESSA_TALAO_DETALHE_ID,
                Y.CONTROLE,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.CLASSE,
                Y.SUBCLASSE,
                Y.PERFIL_SKU,
                Y.PERFIL_SKU_DESCRICAO,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                IIF(Y.QUANTIDADE < 1, 1,Y.QUANTIDADE)QUANTIDADE ,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.UM,
                Y.LOCALIZACAO_ID,
                (SELECT FIRST 1
                    IIF(Y.TAMANHO =  1,M.T01, IIF(Y.TAMANHO =  2,M.T02, IIF(Y.TAMANHO =  3,M.T03, IIF(Y.TAMANHO =  4,M.T04, IIF(Y.TAMANHO =  5,M.T05,
                    IIF(Y.TAMANHO =  6,M.T06, IIF(Y.TAMANHO =  7,M.T07, IIF(Y.TAMANHO =  8,M.T08, IIF(Y.TAMANHO =  9,M.T09, IIF(Y.TAMANHO = 10,M.T10,
                    IIF(Y.TAMANHO = 11,M.T11, IIF(Y.TAMANHO = 12,M.T12, IIF(Y.TAMANHO = 13,M.T13, IIF(Y.TAMANHO = 14,M.T14, IIF(Y.TAMANHO = 15,M.T15,
                    IIF(Y.TAMANHO = 16,M.T16, IIF(Y.TAMANHO = 17,M.T17, IIF(Y.TAMANHO = 18,M.T18, IIF(Y.TAMANHO = 19,M.T19, IIF(Y.TAMANHO = 20,M.T20, 0)))))))))))))))))))) QUEBRA
                    FROM TBMODELO_REMESSA_COTA M WHERE M.MODELO_CODIGO = Y.MODELO_ID) FATOR_DIVISAO,
                    
                (SELECT
                    CASE Y.TAMANHO
                        WHEN 01 THEN PC.MI01
                        WHEN 02 THEN PC.MI02
                        WHEN 03 THEN PC.MI03
                        WHEN 04 THEN PC.MI04
                        WHEN 05 THEN PC.MI05
                        WHEN 06 THEN PC.MI06
                        WHEN 07 THEN PC.MI07
                        WHEN 08 THEN PC.MI08
                        WHEN 09 THEN PC.MI09
                        WHEN 10 THEN PC.MI10
                        WHEN 11 THEN PC.MI11
                        WHEN 12 THEN PC.MI12
                        WHEN 13 THEN PC.MI13
                        WHEN 14 THEN PC.MI14
                        WHEN 15 THEN PC.MI15
                        WHEN 16 THEN PC.MI16
                        WHEN 17 THEN PC.MI17
                        WHEN 18 THEN PC.MI18
                        WHEN 19 THEN PC.MI19
                        WHEN 20 THEN PC.MI20
                        ELSE 99999999999
                    END
                 FROM TBMODELO_PEDIDO_COTA PC WHERE PC.MODELO_ID = Y.MODELO_ID) FATOR_DIVISAO_DETALHE

            FROM
                (
                SELECT             
                    X.ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.CONTROLE,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                    CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.MODELO_PRIORIDADE,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.UM,
                    X.LOCALIZACAO_ID,
                    SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                    CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,

                    IIF( (SELECT FIRST 1 U.VALOR_EXT --VERIFICA SE REQUISICAO INCLUI ACRESCIMO
                            FROM TBCONTROLE_N U
                           WHERE U.ID = 229) = '1',
                           IIF( (SELECT FIRST 1 POSITION (X.FAMILIA_ID IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                   FROM TBCONTROLE_N U
                                  WHERE U.ID = 231) > 0,
                                IIF((X.QUANTIDADE + X.ACRESCIMO) < 1, 1,(X.QUANTIDADE + (X.ACRESCIMO/2))), --INCLUI ACRESCIMO E PERMITE ARREND.
                                (X.QUANTIDADE + (X.ACRESCIMO/2))),                                         --INCLUI ACRESCIMO E NAO PERMITE ARREND.
                           IIF( (SELECT FIRST 1 POSITION (X.FAMILIA_ID IN U.VALOR_EXT) -- VERIFICA SE PERMITE ARREDONDAMENTO
                                   FROM TBCONTROLE_N U
                                  WHERE U.ID = 231) > 0,
                                IIF(X.QUANTIDADE < 1, 1,X.QUANTIDADE),                                     --NAO INCLUI ACRESCIMO E PERMITE ARREND.
                                X.QUANTIDADE)) QUANTIDADE,                                                 --NAO INCLUI ACRESCIMO E NAO PERMITE ARREND.

                    X.QUANTIDADE_ALTERNATIVA,
                    X.ACRESCIMO
                            
                FROM
                    (
                        SELECT
                            'SQL_1' SQL_ID,
                            C.QUEBRA,
                            IIF(F.CONTROLE_TALAO = 'A',NULL,C.ID) ID,
                            C.ID REMESSA_TALAO_ID,
                            NULL REMESSA_TALAO_DETALHE_ID,
                            C.ID CONTROLE,
                            M.DENSIDADE,
                            M.ESPESSURA,
                            (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                            LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                            P.DESCRICAO           PRODUTO_DESCRICAO,
                            P.GRADE_CODIGO        GRADE_ID,
                            COALESCE(C.TAMANHO,0)TAMANHO,
                            (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,

                            CAST(IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',
                                    IIF(C.QUEBRA = '1',(C.QUANTIDADE/2),C.QUANTIDADE),
                                    COALESCE(IIF(C.QUEBRA = '1',(C.QUANTIDADE/2),C.QUANTIDADE)/COALESCE(C.FATOR_CONVERSAO,0),0)
                            ) AS NUMERIC(15,4))QUANTIDADE,

                            CAST(IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',0,
                                    COALESCE(IIF(C.QUEBRA = '1',(C.QUANTIDADE/2),C.QUANTIDADE),0)
                            ) AS NUMERIC(15,4)) QUANTIDADE_ALTERNATIVA,
                            COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                            P.UNIDADEMEDIDA_SIGLA UM,
                            P.MODELO_CODIGO MODELO_ID,
                            M.DESCRICAO MODELO_DESCRICAO,
                            M.PRIORIDADE MODELO_PRIORIDADE,
                            P.COR_CODIGO COR_ID,
                            (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                            P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                            (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO) PERFIL_SKU,
                            F.CODIGO FAMILIA_ID

                        FROM
                            TBREQUISICAO C,
                            TBPRODUTO P,
                            TBFAMILIA F,
                            TBMODELO M
                        WHERE
                            C.PRODUTO_ID     = P.CODIGO
                        AND F.CODIGO         = P.FAMILIA_CODIGO 
                        AND M.CODIGO         = P.MODELO_CODIGO
                        AND C.CONSUMO        = '1'
                        AND (C.STATUS        = '1' OR C.AUTORIZACAO_STATUS = '1')
                        AND C.REMESSA_GERADA < 1
                        /*@FAMILIA_ID*/

                        UNION

                        SELECT               
                            'SQL_2' SQL_ID,
                            C.QUEBRA,
                            IIF(F.CONTROLE_TALAO = 'A',NULL,C.ID) ID,
                            C.ID REMESSA_TALAO_ID,
                            NULL REMESSA_TALAO_DETALHE_ID,
                            C.ID CONTROLE,
                            M.DENSIDADE,
                            M.ESPESSURA,
                            (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                            LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                            P.DESCRICAO           PRODUTO_DESCRICAO,
                            P.GRADE_CODIGO        GRADE_ID,
                            COALESCE(C.TAMANHO,0)TAMANHO,
                            (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,

                            IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',
                                    (C.QUANTIDADE/2),
                                    COALESCE((C.QUANTIDADE/2)/COALESCE(C.FATOR_CONVERSAO,0),0)
                            ) QUANTIDADE,

                            IIF(
                                COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') = '',0,
                                    COALESCE((C.QUANTIDADE/2),0)
                            ) QUANTIDADE_ALTERNATIVA,
                            COALESCE(M.ACRESCIMO,0) ACRESCIMO,
                            P.UNIDADEMEDIDA_SIGLA UM,
                            P.MODELO_CODIGO MODELO_ID,
                            M.DESCRICAO MODELO_DESCRICAO, 
                            M.PRIORIDADE MODELO_PRIORIDADE,
                            P.COR_CODIGO COR_ID,
                            (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                            P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                            (SELECT FIRST 1 (S.PERFIL||PER.DESCRICAO) FROM VWSKU S, TBPERFIL PER WHERE PER.TABELA = 'SKU' AND PER.ID = S.PERFIL AND S.MODELO_ID = P.MODELO_CODIGO AND S.COR_ID = P.COR_CODIGO AND S.TAMANHO = C.TAMANHO) PERFIL_SKU,
                            F.CODIGO FAMILIA_ID
                        FROM
                            TBREQUISICAO C,
                            TBPRODUTO P,
                            TBFAMILIA F,
                            TBMODELO M
                        WHERE
                            C.PRODUTO_ID     = P.CODIGO
                        AND F.CODIGO         = P.FAMILIA_CODIGO     
                        AND M.CODIGO         = P.MODELO_CODIGO
                        AND C.CONSUMO        = '1'
                        AND (C.STATUS        = '1' OR C.AUTORIZACAO_STATUS = '1')
                        AND C.REMESSA_GERADA < 1
                        AND C.QUEBRA         = '1'
                        /*@FAMILIA_ID*/
                    ) X
                )Y
            ORDER BY MODELO_PRIORIDADE, DENSIDADE, ESPESSURA, CLASSE, SUBCLASSE, MODELO_DESCRICAO, REMESSA_TALAO_DETALHE_ID
        ";

        $familia_id       = isset($param->FAMILIA_ID      ) ? "AND P.FAMILIA_CODIGO   IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")" : '';
        
        $args = ['@FAMILIA_ID' => $familia_id];


        return $this->con->query($sql,$args);
    }

    public function selectConsumo($param = null) {       

        $sql =
        "
            SELECT
                iif(X.OB1 = '', iif(X.OB2 = '', '',X.OB2),iif(X.OB2 = '', X.OB1,(X.OB1||', '||X.OB2))) AS OB,
                X.ID,
                X.REMESSA_ID,
                X.REMESSA_TALAO_ID,
                X.REMESSA_TALAO_DETALHE_ID,
                X.CONTROLE,
                X.DENSIDADE,
                X.ESPESSURA,
                SUBSTRING(X.CLASSE FROM 1 FOR 1) CLASSE,
                CAST(SUBSTRING(X.CLASSE FROM 2) AS INTEGER) SUBCLASSE,
                X.PRODUTO_ID,
                X.PRODUTO_DESCRICAO,
                X.MODELO_ID,
                X.MODELO_DESCRICAO,
                X.COR_ID,
                X.COR_DESCRICAO,
                X.GRADE_ID,
                X.TAMANHO,
                X.TAMANHO_DESCRICAO,
                X.LOCALIZACAO_ID,
                SUBSTRING(X.PERFIL_SKU FROM 1 FOR 1) PERFIL_SKU,
                CAST(SUBSTRING(X.PERFIL_SKU FROM 2) AS VARCHAR(20)) PERFIL_SKU_DESCRICAO,  
                X.QUANTIDADE_ORIGINAL,
                X.UM_ORIGINAL,
                X.QUANTIDADE_PROJECAO,
                X.QUANTIDADE_PROJECAO_ALTERNATIVA, 
                X.QUANTIDADE, 
                X.QUANTIDADE_ALTERNATIVA, 
                X.UM,     
                X.UM_ALTERNATIVA,
                X.STATUS,
                X.PECA_CONJUNTO,
                X.UP_ID,
                X.UP_DESCRICAO,
                X.TALAO_MODELO_ID,
                X.TALAO_MODELO_DESCRICAO,
                X.DATAHORA_INICIO,
                X.FAMILIA_CODIGO,
                X.TALAO_PRODUTO_ID,
                X.TALAO_TAMANHO,
                X.TALAO_COR_CLASSE
            FROM
                (
                SELECT
                    C.ID,
                    C.REMESSA_ID,
                    C.REMESSA_TALAO_ID,
                    IIF(F.CONTROLE_TALAO = 'A',0,C.REMESSA_TALAO_DETALHE_ID) REMESSA_TALAO_DETALHE_ID,
                    C.CONTROLE,
                    C.DENSIDADE,
                    C.ESPESSURA,
                    (SELECT FIRST 1 LPAD(CO.CLASSE, 1, ' ') || LPAD(CO.SUBCLASSE, 10, ' ') FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) CLASSE,
                    LPAD(P.CODIGO,5,'0')  PRODUTO_ID,
                    P.DESCRICAO           PRODUTO_DESCRICAO,
                    P.GRADE_CODIGO        GRADE_ID,
                    COALESCE(C.TAMANHO,0)TAMANHO,
                    (SELECT FIRST 1 TAM_DESCRICAO FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                    C.QUANTIDADE_ORIGINAL,
                    (SELECT FIRST 1 P.UNIDADEMEDIDA_SIGLA
                       FROM TBPRODUTO P
                      WHERE P.CODIGO = (SELECT FIRST 1 PRODUTO_ID
                                          FROM VWREMESSA_TALAO_DETALHE D
                                         WHERE D.REMESSA_ID = C.REMESSA_ID
                                           AND D.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID)) UM_ORIGINAL,
                    C.QUANTIDADE_SALDO QUANTIDADE,
                    C.QUANTIDADE_ALTERNATIVA_SALDO QUANTIDADE_ALTERNATIVA,
                    C.QUANTIDADE QUANTIDADE_PROJECAO,
                    C.QUANTIDADE_ALTERNATIVA QUANTIDADE_PROJECAO_ALTERNATIVA,
                    P.UNIDADEMEDIDA_SIGLA UM,
                    F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                    P.MODELO_CODIGO MODELO_ID,
                    (SELECT FIRST 1 M.DESCRICAO FROM TBMODELO M WHERE M.CODIGO = P.MODELO_CODIGO) MODELO_DESCRICAO,
                    P.COR_CODIGO COR_ID,
                    (SELECT FIRST 1 CO.DESCRICAO FROM TBCOR CO WHERE CO.CODIGO = P.COR_CODIGO) COR_DESCRICAO,
                    P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                    (SELECT FIRST 1 (S.PERFIL ||
                            (SELECT FIRST 1 DESCRICAO
                              FROM TBPERFIL P
                             WHERE P.TABELA = 'SKU'
                               AND P.ID     = S.PERFIL))
                       FROM VWSKU S
                      WHERE S.MODELO_ID = P.MODELO_CODIGO
                        AND S.COR_ID    = P.COR_CODIGO
                        AND S.TAMANHO   = C.TAMANHO) PERFIL_SKU,
                    C.STATUS,
                    D.PECA_CONJUNTO,
                    T.UP_ID,
                    (SELECT FIRST 1 DESCRICAO FROM TBUP WHERE ID = T.UP_ID) UP_DESCRICAO,
                    T.MODELO_ID TALAO_MODELO_ID,
                    M.DESCRICAO TALAO_MODELO_DESCRICAO,
                    T.TAMANHO TALAO_TAMANHO,
                    T.PRODUTO_ID TALAO_PRODUTO_ID,
                    (SELECT FIRST 1 C.CLASSE||'.'||C.SUBCLASSE
                       FROM TBCOR C, TBPRODUTO PP
                      WHERE PP.CODIGO = T.PRODUTO_ID
                        AND C.CODIGO = PP.COR_CODIGO) TALAO_COR_CLASSE,
                    PR.DATAHORA_INICIO,
                    PR.DATAHORA_FIM,
                    P.FAMILIA_CODIGO,
                    
                    coalesce((SELECT LIST( (select list(DISTINCT

                        (SELECT LIST(DISTINCT (SELECT FIRST 1 R.OB FROM TBREVISAO R WHERE R.ID = x.TABELA_ID and r.ob > 0
                        ), ', ')
                         FROM TBREMESSA_TALAO_VINCULO x
                         WHERE 1 = 1
                           AND x.TALAO_ID = g.id
                           AND x.CONSUMO_ID = k.id
                           AND x.TIPO = 'R'
                         )

                    ,', ') from VWREMESSA_CONSUMO k LEFT JOIN
                        VWREMESSA_TALAO_DETALHE s ON s.ID = k.REMESSA_TALAO_DETALHE_ID LEFT JOIN
                        VWREMESSA_TALAO g ON g.REMESSA_ID = k.REMESSA_ID AND g.REMESSA_TALAO_ID = k.REMESSA_TALAO_ID
                        where k.remessa_talao_detalhe_id = y.tabela_id)
                    , ', ')
                     FROM TBREMESSA_TALAO_VINCULO y
                     WHERE 1 = 1
                       AND y.TALAO_ID = t.id
                       AND y.CONSUMO_ID = c.id
                       AND y.TIPO = 'D'
                     ),'')OB1,

                     coalesce((SELECT LIST(DISTINCT (SELECT FIRST 1 R.OB FROM TBREVISAO R WHERE R.ID = y.TABELA_ID and r.ob > 0
                     ), ', ')
                     FROM TBREMESSA_TALAO_VINCULO y
                     WHERE 1 = 1
                       AND y.TALAO_ID = t.id
                       AND y.CONSUMO_ID = c.id
                       AND y.TIPO = 'R'
                     ),'') OB2
                 
                FROM
                    VWREMESSA_CONSUMO C LEFT JOIN
                    VWREMESSA_TALAO_DETALHE D ON D.ID = C.REMESSA_TALAO_DETALHE_ID LEFT JOIN
                    VWREMESSA_TALAO T ON T.REMESSA_ID = C.REMESSA_ID AND T.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID LEFT JOIN
                    TBPROGRAMACAO PR ON PR.TABELA_ID = T.ID AND PR.TIPO = 'A',
                    TBMODELO M,
                    TBPRODUTO P,
                    TBFAMILIA F
                WHERE
                    C.PRODUTO_ID       = P.CODIGO
                AND F.CODIGO           = P.FAMILIA_CODIGO
                AND M.CODIGO           = T.MODELO_ID
                ) X
            WHERE
                1=1
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
            /*@FAMILIA_ID*/
            /*@FAMILIA_ID_CONSUMO*/
            /*@STATUS_CONSUMO*/
                        ORDER BY UP_DESCRICAO, REMESSA_TALAO_ID, FAMILIA_CODIGO, PECA_CONJUNTO, CONTROLE, CLASSE, SUBCLASSE, PRODUTO_DESCRICAO
        ";

        $remessa_id			= isset($param->REMESSA_ID      )		? "AND REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 999999999) . ")"	: '';
        $remessa_talao_id	= isset($param->REMESSA_TALAO_ID)		? "AND REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 999999999) . ")"	: '';
        $familia_id			= isset($param->FAMILIA_ID      )		? "AND FAMILIA_ID       IN (" . arrayToList($param->FAMILIA_ID      , 999999999) . ")"	: '';
        $familia_id_consumo = isset($param->FAMILIA_ID_CONSUMO) && !empty($param->FAMILIA_ID_CONSUMO) ? "AND FAMILIA_CODIGO   =	" . $param->FAMILIA_ID_CONSUMO : '';
        $status				= isset($param->STATUS_CONSUMO  )		? "AND STATUS           IN (" . arrayToList($param->STATUS_CONSUMO  , "'#'","'") . ")"	: '';
        
        
        $args = [
            '@REMESSA_ID'			=> $remessa_id,
            '@REMESSA_TALAO_ID'		=> $remessa_talao_id,
            '@FAMILIA_ID'			=> $familia_id,
            '@FAMILIA_ID_CONSUMO'	=> $familia_id_consumo,
            '@STATUS_CONSUMO'		=> $status
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function insertRemessaTalaoIntermediario($param) {       
        
        $sql =
        "
            INSERT INTO VWREMESSA_TALAO (
                REMESSA_ID,
                REMESSA_TALAO_ID,
                MODELO_ID,
                TAMANHO,
                GP_ID,
                UP_ID,
                ESTACAO,
                QUANTIDADE,
                OBSERVACAO
            ) VALUES (
                -99999,
                :REMESSA_TALAO_ID,
                :MODELO_ID,
                :TAMANHO,
                :GP_ID,
                :UP_ID,
                :ESTACAO,
                :QUANTIDADE,
                :OBSERVACAO
            );
        ";
        
        $args = [
            'OBSERVACAO'        => $param->OBSERVACAO,
            'REMESSA_TALAO_ID'  => $param->REMESSA_TALAO_ID,
            'GP_ID'             => $param->GP_ID,
            'UP_ID'             => $param->UP_ID,
            'ESTACAO'           => $param->ESTACAO,
            'MODELO_ID'         => $param->MODELO_ID,
            'TAMANHO'           => $param->TAMANHO,
            'QUANTIDADE'        => $param->QUANTIDADE,
        ]; 
        
        return $this->con->query($sql,$args); 
    }
    
    public function updateRemessaTalaoLiberacaoCancelar($param) {       
        
        $sql =
        "
         UPDATE VWREMESSA_TALAO T
            SET T.STATUS             = 2,
                T.OPERADOR_LIBERACAO = 0,
                T.DATA_LIBERACAO     = NULL,
                T.HORA_LIBERACAO     = NULL
          WHERE T.STATUS = 3
            AND T.REMESSA_ID       = :REMESSA_ID
            AND T.REMESSA_TALAO_ID = :REMESSA_TALAO_ID;
        ";

        
        $args = [
            'REMESSA_ID'       => $param->REMESSA_ID,
            'REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID
        ]; 
        
        $this->con->query($sql,$args); 
        
        $sql =
        "
         UPDATE TBPEDIDO_ITEM_PROCESSADO
            SET SITUACAO = 1,
                ESTEIRA_LIBERACAO = 0,
                DATA_LIBERACAO = NULL,
                HORA_LIBERACAO = NULL
          WHERE SITUACAO = 2
            AND REMESSA                    = :REMESSA_ID
            AND REMESSA_ACUMULADO_CONTROLE = :REMESSA_TALAO_ID;
        ";

        
        $args = [
            'REMESSA_ID'       => $param->REMESSA_ID,
            'REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID
        ]; 
        
        $this->con->query($sql,$args); 
    }
    
    public function updateRemessaConsumo($param) {       
        
        $sql =
        "
            UPDATE TBREMESSA_CONSUMO C
            SET ID=ID
                /*@UPD_PRODUTO_ID*/
                /*@UPD_TAMANHO*/
            WHERE 1=1
                /*@ID*/
        ";
        
        $consumo_id     = array_key_exists('ID'            , $param) ? "AND C.ID = $param->ID" : '';
        $upd_produto_id = array_key_exists('UPD_PRODUTO_ID', $param) ? ", C.PRODUTO_ID = $param->UPD_PRODUTO_ID" : '';
        $upd_tamanho    = array_key_exists('UPD_TAMANHO'   , $param) ? ", C.TAMANHO    = $param->UPD_TAMANHO" : '';
        
        
        $args = [
            '@ID'             => $consumo_id,
            '@UPD_PRODUTO_ID' => $upd_produto_id,
            '@UPD_TAMANHO'    => $upd_tamanho
        ]; 
        
        return $this->con->query($sql,$args); 
    }

    public function spiRemessaIntermediaria() {       
        
        $sql =
        "
            EXECUTE PROCEDURE SPI_REMESSA_INTERMEDIARIA;
        ";
        
        return $this->con->query($sql); 
    }
    
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _22120DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _22120DAO::listar($dados);
	}
	
	/**
	 * Listar
	 */
	public static function selectRemessas($dados, $con = null) {
		return _22120DAO::selectRemessas(obj_case($dados),$con);
	}
	
	/**
	 * Listar
	 */
	public static function selectRemessasTalaoVinculo($dados, $con = null) {
		return _22120DAO::selectRemessasTalaoVinculo(obj_case($dados),$con);
	}
	
	/**
	 * Listar
	 */
	public static function selectRemessaTalaoDetalhe($dados, $con = null) {
		return _22120DAO::selectRemessaTalaoDetalhe(obj_case($dados),$con);
	}
	
	/**
	 * Listar
	 */
	public static function selectRemessaTalaoMaxId($dados, $con = null) {
		return _22120DAO::selectRemessaTalaoMaxId(obj_case($dados),$con);
	}
    
	public static function selectRemessaConsumo($dados, $con = null) {
		return _22120DAO::selectRemessaConsumo(obj_case($dados),$con);
	}
    
	public static function selectRemessaConsumoAlocacoes($dados, $con = null) {
		return _22120DAO::selectRemessaConsumoAlocacoes(obj_case($dados),$con);
	}
    
	public static function selectRemessaConsumoFamilia($dados, $con = null) {
		return _22120DAO::selectRemessaConsumoFamilia(obj_case($dados),$con);
	}
    
	public static function selectSkus($dados, $con = null) {
		return _22120DAO::selectSkus(obj_case($dados),$con);
	}
    
	public static function selectTaloesExtra($dados, $con = null) {
		return _22120DAO::selectTaloesExtra(obj_case($dados),$con);
	}
    
	public static function selectDefeitoOrigem($dados, $con = null) {
		return _22120DAO::selectDefeitoOrigem(obj_case($dados),$con);
	}
    
    public static function updateRemessaTalaoDetalhe($dados, $con = null) {
        return _22120DAO::updateRemessaTalaoDetalhe(obj_case($dados),$con);
    }

    public static function updateRemessaTalaoDetalheEncerrar($dados, $con = null) {
        return _22120DAO::updateRemessaTalaoDetalheEncerrar(obj_case($dados),$con);
    }

    public static function insertRemessaTalao($dados, $con = null) {
        return _22120DAO::insertRemessaTalao(obj_case($dados),$con);
    }
        
    public static function spiRemessaConsumo($dados, $con = null) {
        return _22120DAO::spiRemessaConsumo(obj_case($dados),$con);
    }
        
    public static function spiRemessaSobra($dados, $con = null) {
        return _22120DAO::spiRemessaSobra(obj_case($dados),$con);
    }
        
    public static function spuTalaoReabrir($dados, $con = null) {
        return _22120DAO::spuTalaoReabrir(obj_case($dados),$con);
    }
        
    public static function updateRemessaTalaoDetalheReabrir($dados, $con = null) {
        return _22120DAO::updateRemessaTalaoDetalheReabrir(obj_case($dados),$con);
    }
    
    public static function spuDesmembrarEtapa2($dados, $con = null) {
        return _22120DAO::spuDesmembrarEtapa2(obj_case($dados),$con);
    }

    public static function deleteRemessaTalaoExtra($dados, $con = null) {
        _22120DAO::deleteRemessaTalaoExtra(obj_case($dados),$con);
    }

    public static function deleteTalaoZerado($dados, $con = null) {
        _22120DAO::deleteTalaoZerado(obj_case($dados),$con);
    }

    public static function deleteRemessaVazia($dados, $con = null) {
        _22120DAO::deleteRemessaVazia(obj_case($dados),$con);
    }
    
    public static function deleteRemessa($dados, $con = null) {
        _22120DAO::deleteRemessa(obj_case($dados),$con);
    }
    
    public static function deleteTalao($dados, $con = null) {
        _22120DAO::deleteTalao(obj_case($dados),$con);
    }
    
    public static function deleteTalaoDetalhe($dados, $con = null) {
        _22120DAO::deleteTalaoDetalhe(obj_case($dados),$con);
    }
    
    public static function deleteTalaoConsumo($dados, $con = null) {
        _22120DAO::deleteTalaoConsumo(obj_case($dados),$con);
    }
}