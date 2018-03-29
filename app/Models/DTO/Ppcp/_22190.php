<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22190DAO;

/**
 * Objeto _22190 - Registro de Producao - Div. Bojo Colante
 */
class _22190
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
    

    public function getTaloesComposicao($param1) {
        
        
        unset($param1->TALAO_ID);
        
        $arr_taloes      = $this->selectTalao($param1);
        $arr_consumos    = [];
        $arr_alocados    = [];
        $arr_historicos  = [];
        $arr_detalhes    = [];
        $arr_componentes = [];
        
        foreach ( $arr_taloes as $talao ) {
        
            $talao_composicao = $this->getTalaoComposicao($talao);
            
            $arr_consumos    = array_merge($arr_consumos    ,$talao_composicao->CONSUMOS   );
            $arr_alocados    = array_merge($arr_alocados    ,$talao_composicao->ALOCADOS   );
            $arr_historicos  = array_merge($arr_historicos  ,$talao_composicao->HISTORICOS );
            $arr_detalhes    = array_merge($arr_detalhes    ,$talao_composicao->DETALHES   );
            $arr_componentes = array_merge($arr_componentes ,$talao_composicao->COMPONENTES);
        }
        
        $ret = [
            'TALOES'      => $arr_taloes,
            'CONSUMOS'    => $arr_consumos,
            'ALOCADOS'    => $arr_alocados,
            'HISTORICOS'  => $arr_historicos,
            'DETALHES'    => $arr_detalhes,
            'COMPONENTES' => $arr_componentes
        ];
        
        return $ret;
    }
    

    public function getTalaoComposicao($talao, $return_talao = false) {
        
        $ret = (object)[];
        
        if ( $return_talao ) {
            
            $arr_talao = $this->selectTalao($talao);
            
            if ( !isset($arr_talao[0]) ) {
                log_erro('Talão não localizado.');
            }
            
            $talao = $arr_talao[0];
            
            $ret->TALAO = $talao;
        }
        
        $ret->CONSUMOS = $this->selectTalaoConsumo((object)[
            'TALAO_ID' => $talao->TALAO_ID,
        ]);

        $ret->ALOCADOS = $this->selectTalaoConsumoAlocacao((object)[
            'TALAO_ID' => $talao->TALAO_ID,
        ]);

        $ret->HISTORICOS = $this->selectTalaoHistorico((object)[
            'TALAO_ID' => $talao->TALAO_ID
        ]);

        $ret->DETALHES = $this->selectTalaoDetalhe((object)[
            'REMESSA_ID'       => $talao->REMESSA_ID,
            'REMESSA_TALAO_ID' => $talao->REMESSA_TALAO_ID
        ]);
        
        $ret->COMPONENTES = $this->selectTalaoConsumoComponente((object)[
            'REMESSA_ID'       => $talao->REMESSA_ID,
            'REMESSA_TALAO_ID' => $talao->REMESSA_TALAO_ID
        ]);
        
        return $ret;
    }
    
    public function postTalaoAcao($param1) {
        

        $param1->WEB = '3';
        $this->updateRemessa($param1);        
        
        if ( array_key_exists('REMESSA_TALAO_STATUS', $param1) ) {
            $this->updateRemessaTalao($param1);
        }
            
        if ( isset($param1->TALAO_DETALHE_STATUS) ) {
            $this->updateRemessaTalaoDetalhe($param1);
        }
        
        if ( isset($param1->PROGRAMACAO_HISTORICO_STATUS) ) {
            $this->updateProgramacaoHistorico($param1);
        }
        
        if ( isset($param1->PROGRAMACAO_STATUS) ) {
            $this->updateProgramacao         ($param1);

            if ( $param1->PROGRAMACAO_STATUS == '3' ) {
                $this->spuReprogramarProduzido($param1);
            }            
        }
        
        if ( isset($param1->ESTACAO_TALAO_ID) ) {
            $this->updateEstacaoBloqueio     ($param1);
        }


    }    
    
    public function selectTalao($param1) {
        
        $sql = "
            SELECT
                R.DATA                                     REMESSA_DATA,
                R.REMESSA                                  REMESSA,
                T.REMESSA_ID                               REMESSA_ID,
                FN_LPAD(T.REMESSA_TALAO_ID,4,0)            REMESSA_TALAO_ID,
                FN_REMESSA_PRINCIPAL(T.REMESSA_ID)         REMESSA_PRINCIPAL,
                T.ID                                       TALAO_ID,
                FN_LPAD(M.CODIGO,5,0)                      MODELO_ID,
                M.DESCRICAO                                MODELO_DESCRICAO,
                FN_LPAD(M.GRADE_CODIGO,2,0)                GRADE_ID,
                FN_LPAD(T.TAMANHO,2,0)                     TAMANHO,
                FN_TAMANHO_GRADE(M.GRADE_CODIGO,T.TAMANHO) TAMANHO_DESCRICAO,
                A.TEMPO                                    TEMPO_PREVISTO,
                COALESCE(A.TEMPO_REALIZADO,0)              TEMPO_REALIZADO,
                T.DATA_PRODUCAO                            DATA_PRODUCAO,
                T.QUANTIDADE                               QUANTIDADE_PROJETADA,
                (SELECT FIRST 1 TRIM(P.UNIDADEMEDIDA_SIGLA)
                   FROM VWREMESSA_TALAO_DETALHE D, TBPRODUTO P
                  WHERE D.REMESSA_ID = T.REMESSA_ID
                    AND D.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                    AND P.CODIGO = D.PRODUTO_ID)                UM,
                A.ID                                       PROGRAMACAO_ID, 
                TRIM(A.STATUS)                             PROGRAMACAO_STATUS,
                TRIM(CASE A.STATUS
                WHEN '0' THEN 'NÃO INICIADO'
                WHEN '1' THEN 'NÃO INICIADO'
                WHEN '2' THEN 'EM ANDAMENTO'
                WHEN '3' THEN 'FINALIZADO'
                WHEN '6' THEN 'ENCERRADO'
                ELSE 'N/D' END) PROGRAMACAO_STATUS_DESCRICAO,
                A.DATAHORA_INICIO,
                A.DATAHORA_FIM,
                (SELECT FIRST 1 PR.DATAHORA
                   FROM TBPROGRAMACAO_REGISTRO PR
                  WHERE PR.PROGRAMACAO_ID = A.ID
                    AND PR.STATUS = 0
               ORDER BY PR.DATAHORA) DATAHORA_INICIADO,
                (SELECT FIRST 1 PR.DATAHORA
                   FROM TBPROGRAMACAO_REGISTRO PR
                  WHERE PR.PROGRAMACAO_ID = A.ID
                    AND PR.STATUS = 2
               ORDER BY PR.DATAHORA DESC) DATAHORA_FINALIZADO,
                1 /*IIF(FN_GP_REMESSA_LIBERADA(T.REMESSA_ID,T.GP_ID,T.UP_ID),1,0)*/ REMESSA_LIBERADA
                

            FROM
                VWREMESSA_TALAO T,
                VWREMESSA R,
                TBPROGRAMACAO A,
                TBMODELO M

            WHERE TRUE
            AND R.REMESSA_ID = T.REMESSA_ID
            AND A.TIPO       = 'A'
            AND A.TABELA_ID  = T.ID
            AND M.CODIGO     = T.MODELO_ID
            /*@TALAO_ID*/
            /*@GP_ID*/
            /*@UP_ID*/
            /*@ESTACAO*/
            /*@PROGRAMACAO_STATUS*/
            /*@DATA_PRODUCAO*/
            
        ";
        
        $param = (object)[];

        if ( isset($param1->TALAO_ID) && $param1->TALAO_ID > -1 ) {
            $param->TALAO_ID = " = $param1->TALAO_ID";
        }

        if ( isset($param1->GP_ID) && $param1->GP_ID > -1 ) {
            $param->GP_ID = " = $param1->GP_ID";
        }

        if ( isset($param1->UP_ID) && $param1->UP_ID > -1 ) {
            $param->UP_ID = " = $param1->UP_ID";
        }

        if ( isset($param1->ESTACAO) && $param1->ESTACAO > -1 ) {
            $param->ESTACAO = " = $param1->ESTACAO";
        }

        if ( isset($param1->PROGRAMACAO_STATUS) && trim($param1->PROGRAMACAO_STATUS) != '' ) {
            $param->PROGRAMACAO_STATUS = $param1->PROGRAMACAO_STATUS;
        }
        
        if ( isset($param1->DATA_PRODUCAO) && trim($param1->DATA_PRODUCAO) != '' ) {
            $param->DATA_PRODUCAO = $param1->DATA_PRODUCAO;
        }

        $talao_id           = array_key_exists('TALAO_ID'          , $param) ? "AND T.ID            $param->TALAO_ID            " : '';
        $gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID         $param->GP_ID               " : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID         $param->UP_ID               " : '';
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND T.ESTACAO       $param->ESTACAO             " : '';
        $programacao_status = array_key_exists('PROGRAMACAO_STATUS', $param) ? "AND A.STATUS        $param->PROGRAMACAO_STATUS  " : '';
        $data_producao      = array_key_exists('DATA_PRODUCAO'     , $param) ? "AND T.DATA_PRODUCAO $param->DATA_PRODUCAO       " : '';
        
        $args = [
            '@TALAO_ID'             => $talao_id,
            '@GP_ID'                => $gp_id,
            '@UP_ID'                => $up_id,
            '@ESTACAO'              => $estacao,
            '@PROGRAMACAO_STATUS'   => $programacao_status,
            '@DATA_PRODUCAO'        => $data_producao
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectTalaoHistorico($param1) {
        
        $sql = "
            SELECT
                X.ID,
                X.PROGRAMACAO_ID,
                X.OPERADOR_ID,
                X.OPERADOR_NOME,
                X.DATAHORA,
                X.STATUS,
                X.STATUS_DESCRICAO,
                X.JUSTIFICATIVA_ID,
                (SELECT FIRST 1 J.DESCRICAO FROM TBJUSTIFICATIVA J WHERE J.ID = X.JUSTIFICATIVA_ID) JUSTIFICATIVA_DESCRICAO

            FROM
                (SELECT
                    R.ID,
                    R.PROGRAMACAO_ID,
                    R.OPERADOR_ID,
                    O.NOME OPERADOR_NOME,
                    R.DATAHORA,
                    TRIM(R.STATUS)STATUS,
                   TRIM((CASE
                        R.STATUS
                    WHEN '0' THEN 'INICIADO/REINICIADO'
                    WHEN '1' THEN 'PARADA TEMPORÁRIA'
                    WHEN '2' THEN 'FINALIZADO'
                    ELSE 'INDEFINIDO' END)) STATUS_DESCRICAO,
                    R.JUSTIFICATIVA_ID
                FROM
                    TBPROGRAMACAO_REGISTRO R,
                    TBPROGRAMACAO P,
                    TBOPERADOR O

                WHERE
                    P.ID = R.PROGRAMACAO_ID
                AND O.CODIGO = R.OPERADOR_ID
                AND P.TIPO = 'A'
                /*@TALAO_ID*/
                )X
        ";
        
        $param = (object)[];

        if ( isset($param1->TALAO_ID) && $param1->TALAO_ID > -1 ) {
            $param->TALAO_ID = " = $param1->TALAO_ID";
        }

        $talao_id = array_key_exists('TALAO_ID', $param) ? "AND P.TABELA_ID $param->TALAO_ID " : '';
        
        $args = [
            '@TALAO_ID' => $talao_id
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectTalaoDetalhe($param1) {
        
        $sql = "
            SELECT
                D.REMESSA_ID,
                D.REMESSA_TALAO_ID,
                D.ID REMESSA_TALAO_DETALHE_ID,
                FN_LPAD(D.PRODUTO_ID,6,0) PRODUTO_ID,
                P.DESCRICAO PRODUTO_DESCRICAO,
                D.TAMANHO,
                FN_TAMANHO_GRADE(P.GRADE_CODIGO,D.TAMANHO)TAMANHO_DESCRICAO,
                D.QUANTIDADE QUANTIDADE_PROJETADA,
                D.QUANTIDADE_PRODUCAO,
                D.QUANTIDADE_SALDO,
                P.UNIDADEMEDIDA_SIGLA UM,
                TRIM(D.STATUS) TALAO_DETALHE_STATUS,
                TRIM((CASE
                    D.STATUS
                WHEN 1 THEN 'EM ABERTO'
                WHEN 2 THEN 'EM PRODUÇÃO'
                WHEN 3 THEN 'PRODUZIDO'
                WHEN 6 THEN 'ENCERRADO'
                ELSE 'INDEFINIDO' END)) TALAO_DETALHE_STATUS_DESCRICAO


            FROM
                VWREMESSA_TALAO_DETALHE D,
                TBPRODUTO P

            WHERE TRUE
            AND P.CODIGO = D.PRODUTO_ID
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
        ";
        
        $param = (object)[];

        if ( isset($param1->REMESSA_ID) && $param1->REMESSA_ID > -1 ) {
            $param->REMESSA_ID = " = $param1->REMESSA_ID";
        }

        if ( isset($param1->REMESSA_TALAO_ID) && $param1->REMESSA_TALAO_ID > -1 ) {
            $param->REMESSA_TALAO_ID = " = $param1->REMESSA_TALAO_ID";
        }

        $remessa_id = array_key_exists('REMESSA_ID'      , $param) ? "AND D.REMESSA_ID       $param->REMESSA_ID       " : '';
        $remessa_talao_id = array_key_exists('REMESSA_TALAO_ID', $param) ? "AND D.REMESSA_TALAO_ID $param->REMESSA_TALAO_ID " : '';
        
        $args = [
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id
        ];    
        
        return $this->con->query($sql,$args);
    }     
    
    public function selectTalaoConsumo($param1) {
        
        $sql = "
            SELECT
                Y.*,
                IIF(COMPONENTE_STATUS = 1 AND  ESTOQUE_SALDO > QUANTIDADE_SALDO,1,0)ESTOQUE_STATUS

            FROM (
                SELECT
                    X.ESTABELECIMENTO_ID,
                    X.CONSUMO_ID,
                    X.REMESSA_ID,
                    X.REMESSA_TALAO_ID,
                    X.REMESSA_TALAO_DETALHE_ID,
                    X.TALAO_ID,
                    X.DENSIDADE,
                    X.ESPESSURA,
                    X.PRODUTO_ID,
                    X.PRODUTO_DESCRICAO,
                    X.GRADE_ID,
                    X.TAMANHO,
                    X.TAMANHO_DESCRICAO,
                    X.QUANTIDADE_PROJETADA,
                    X.QUANTIDADE_CONSUMIDA,
                    X.QUANTIDADE_ALOCADA,
                    X.QUANTIDADE_SALDO,   
                    X.UM,
                    X.FAMILIA_ID,
                    X.LOCALIZACAO_ID,
                    CONSUMO_STATUS,
                    CONSUMO_STATUS_DESCRICAO,
                    FN_ESTOQUE_SALDO_DISPONIVEL(X.ESTABELECIMENTO_ID,X.LOCALIZACAO_ID,X.PRODUTO_ID,X.TAMANHO) ESTOQUE_SALDO,
                    COMPONENTE,
                    COMPONENTE_STATUS
                FROM
                    (SELECT DISTINCT
                        C.ESTABELECIMENTO_ID,
                        C.ID CONSUMO_ID,
                        T.REMESSA_ID,
                        T.REMESSA_TALAO_ID,
                        C.REMESSA_TALAO_DETALHE_ID,
                        T.ID TALAO_ID,
                        T.GP_ID,
                        T.UP_ID,
                        U.PERFIL PERFIL_UP,
                        C.DENSIDADE,
                        C.ESPESSURA,
                        LPAD(C.PRODUTO_ID,5,'0') PRODUTO_ID,
                        P.DESCRICAO PRODUTO_DESCRICAO,
                        P.GRADE_CODIGO GRADE_ID,
                        C.TAMANHO,
                        (SELECT FIRST 1 * FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                        C.QUANTIDADE QUANTIDADE_PROJETADA,
                        COALESCE((SELECT SUM(QUANTIDADE) FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID),0)QUANTIDADE_ALOCADA,
                        C.QUANTIDADE_CONSUMO QUANTIDADE_CONSUMIDA,
                        C.QUANTIDADE_SALDO,   
                        P.UNIDADEMEDIDA_SIGLA UM,
                        P.FAMILIA_CODIGO FAMILIA_ID,
                        TRIM(IIF(C.QUANTIDADE <= C.QUANTIDADE_SALDO,'0','1')) CONSUMO_STATUS,
                        TRIM(IIF(C.QUANTIDADE <= C.QUANTIDADE_SALDO,'INDISPONÍVEL','DISPONÍVEL')) CONSUMO_STATUS_DESCRICAO,
    
                        (SELECT FIRST 1 A.LOCALIZACAO_ID
                           FROM SPC_ESTOQUE_TRANSACAO_REGRA(3,T.GP_ID,(
                            SELECT FIRST 1 PERFIL
                              FROM TBUP U
                             WHERE U.ID = T.UP_ID),C.PRODUTO_ID,'S') A) LOCALIZACAO_ID,
                        C.COMPONENTE,
                        IIF(C.COMPONENTE = '1',
                            IIF(COALESCE((
                            SELECT MIN(STATUS)
                              FROM TBREMESSA_CONSUMO_VINCULO V,
                                   VWREMESSA_TALAO_DETALHE D
                             WHERE V.CONSUMO_ID = C.ID
                               AND D.ID = V.REMESSA_TALAO_DETALHE_ID),0) < 3,0,1),1)COMPONENTE_STATUS
                    FROM
                        VWREMESSA_CONSUMO C,
                        VWREMESSA_TALAO T,
                        TBPRODUTO P,
                        VWREMESSA R,
                        TBFAMILIA F,
                        TBUP U
    
                    WHERE
                        C.REMESSA_ID       = T.REMESSA_ID
                    AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                    AND P.CODIGO           = C.PRODUTO_ID
                    AND R.REMESSA_ID       = C.REMESSA_ID
                    AND F.CODIGO           = P.FAMILIA_CODIGO
                    AND U.ID               = T.UP_ID
                    )X

                WHERE TRUE
                /*@CONSUMO_ID*/
                /*@REMESSA_ID*/
                /*@REMESSA_TALAO_ID*/
                /*@TALAO_ID*/

                ORDER BY PRODUTO_ID
            ) Y
        ";
        
        $param = (object)[];

        if ( isset($param1->CONSUMO_ID) && $param1->CONSUMO_ID > -1 ) {
            $param->CONSUMO_ID = " = $param1->CONSUMO_ID";
        }

        if ( isset($param1->REMESSA_ID) && $param1->REMESSA_ID > -1 ) {
            $param->REMESSA_ID = " = $param1->REMESSA_ID";
        }

        if ( isset($param1->REMESSA_TALAO_ID) && $param1->REMESSA_TALAO_ID > -1 ) {
            $param->REMESSA_TALAO_ID = " = $param1->REMESSA_TALAO_ID";
        }

        if ( isset($param1->TALAO_ID) && trim($param1->TALAO_ID) != '' ) {
            $param->TALAO_ID = " = $param1->TALAO_ID";
        }

        $consumo_id       = array_key_exists('CONSUMO_ID'      , $param) ? "AND CONSUMO_ID       $param->CONSUMO_ID       " : '';
        $remessa_id       = array_key_exists('REMESSA_ID'      , $param) ? "AND REMESSA_ID       $param->REMESSA_ID       " : '';
        $remessa_talao_id = array_key_exists('REMESSA_TALAO_ID', $param) ? "AND REMESSA_TALAO_ID $param->REMESSA_TALAO_ID " : '';
        $talao_id         = array_key_exists('TALAO_ID'        , $param) ? "AND TALAO_ID         $param->TALAO_ID         " : '';
        
        $args = [
            '@CONSUMO_ID'       => $consumo_id,
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id,
            '@TALAO_ID'         => $talao_id,
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectTalaoConsumoAlocacao($param) {

        $sql =
        "
            SELECT
                ID,
                TALAO_ID,
                CONSUMO_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
                LPAD(LOCALIZACAO_ID,3,'0')LOCALIZACAO_ID,
                (SELECT FIRST 1 DESCRICAO FROM TBLOCALIZACAO WHERE CODIGO = LOCALIZACAO_ID)LOCALIZACAO_DESCRICAO,
				OB,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA,
                UM,
                UM_ALTERNATIVA,
                STATUS
            FROM
                (SELECT
                    V.ID,
                    V.TALAO_ID,
                    V.CONSUMO_ID,
                    TRIM(V.TIPO) TIPO,
                    v.TABELA_ID,
                   (CASE
                        V.TIPO
                    WHEN 'R' THEN (SELECT FIRST 1 PRODUTO_ID FROM TBREVISAO WHERE ID = V.TABELA_ID)
                    WHEN 'D' THEN (SELECT FIRST 1 PRODUTO_ID FROM VWREMESSA_TALAO_DETALHE WHERE ID = V.TABELA_ID)
                    ELSE 0 END)PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
                   (CASE
                        V.TIPO
                    WHEN 'R' THEN (SELECT FIRST 1 LOCALIZACAO_ENTRADA FROM TBREVISAO WHERE ID = V.TABELA_ID)
                    WHEN 'D' THEN (SELECT FIRST 1 LOCALIZACAO_ID FROM VWREMESSA_TALAO_DETALHE WHERE ID = V.TABELA_ID)
                    ELSE 0 END)LOCALIZACAO_ID,
					IIF(V.TIPO = 'R', (SELECT FIRST 1 OB FROM TBREVISAO WHERE ID = V.TABELA_ID), '') OB,
                    V.QUANTIDADE,
                    V.QUANTIDADE_ALTERNATIVA,
                    F.UNIDADEMEDIDA_SIGLA UM,
                    F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                    TRIM(V.STATUS) STATUS


                FROM
                    TBREMESSA_TALAO_VINCULO V,
                    TBPRODUTO P,
                    TBFAMILIA F

                WHERE
                    P.CODIGO = V.PRODUTO_ID
                AND F.CODIGO = P.FAMILIA_CODIGO)X

            WHERE
                1=1
            /*@STATUS*/
            /*@CONSUMO_ID*/
            /*@TALAO_ID*/
        ";
                
        $consumo_id = array_key_exists('CONSUMO_ID', $param) ? "AND CONSUMO_ID IN (" . arrayToList($param->CONSUMO_ID, 9999999999999) . ")" : '';
        $status	    = array_key_exists('STATUS'    , $param) ? "AND STATUS     IN (" . arrayToList($param->STATUS    , 9999999999999) . ")" : '';
        $talao_id   = array_key_exists('TALAO_ID'  , $param) ? "AND TALAO_ID   IN (" . arrayToList($param->TALAO_ID  , 9999999999999) . ")" : '';
        
        $args = [
            '@CONSUMO_ID' => $consumo_id,
            '@STATUS'     => $status,
            '@TALAO_ID'   => $talao_id
        ];
        
        return $this->con->query($sql,$args);
    }    
    
    public function selectTalaoConsumoComponente($param) {

        $sql =
        "
            SELECT
                TALAO_ID,
                R.REMESSA,
                R.REMESSA_ID,
                T.ID COMPONENTE_TALAO_ID,
                FN_LPAD(T.REMESSA_TALAO_ID,4,0) REMESSA_TALAO_ID,
                ALOCADO,
                TRIM(P.STATUS) PROGRAMACAO_STATUS,
                TRIM(IIF(ALOCADO IS NOT NULL,
                    'ALOCADO',
                    CASE P.STATUS
                        WHEN '0' THEN 'NÃO INICIADO'
                        WHEN '1' THEN 'NÃO INICIADO'
                        WHEN '2' THEN 'EM ANDAMENTO'
                        WHEN '3' THEN 'FINALIZADO'
                        WHEN '6' THEN 'ENCERRADO'
                        ELSE 'N/D' END)) PROGRAMACAO_STATUS_DESCRICAO

            FROM (
                SELECT
                    X.TALAO_ID,
                    X.REMESSA_ID,
                    X.REMESSA_TALAO_ID,
                    MIN(VINCULO) ALOCADO

                FROM (SELECT T.ID TALAO_ID,
                             V.*,
                            (SELECT FIRST 1 1
                               FROM TBREMESSA_TALAO_VINCULO TV
                              WHERE TV.CONSUMO_ID = V.CONSUMO_ID
                                AND TV.TIPO = 'D'
                                AND TV.TABELA_ID = V.REMESSA_TALAO_DETALHE_ID) VINCULO
                    FROM VWREMESSA_CONSUMO C,
                         TBREMESSA_CONSUMO_VINCULO V,
                         VWREMESSA_TALAO T
                   WHERE C.REMESSA_ID = :REMESSA_ID
                     AND C.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
                     AND C.COMPONENTE = '1'
                     AND V.CONSUMO_ID = C.ID
                     AND T.REMESSA_ID = C.REMESSA_ID
                     AND T.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID) X
                   GROUP BY 1,2,3
                ) X,
                VWREMESSA R,
                VWREMESSA_TALAO T,
                TBPROGRAMACAO P
            WHERE
                T.REMESSA_ID = X.REMESSA_ID
            AND T.REMESSA_TALAO_ID = X.REMESSA_TALAO_ID
            AND R.REMESSA_ID = T.REMESSA_ID
            AND P.TIPO = 'A'
            AND P.TABELA_ID = T.ID
        ";
        
        $args = [
            'REMESSA_ID'       => $param->REMESSA_ID,
            'REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID
        ];
        
        return $this->con->query($sql,$args);
    }    
    
    public function selectTalaoConsumoComponenteVinculo($param) {

        $sql =
        "
            SELECT
                ID,
                CONSUMO_ID,
                REMESSA_ID,
                REMESSA_TALAO_ID,
                REMESSA_TALAO_DETALHE_ID,
                QUANTIDADE QUANTIDADE_SALDO

            FROM (
                SELECT
                    V.ID,
                    V.CONSUMO_ID,
                    V.REMESSA_ID,
                    V.REMESSA_TALAO_ID,
                    V.REMESSA_TALAO_DETALHE_ID,
                    V.QUANTIDADE,
                    D.QUANTIDADE_SALDO

                FROM
                    TBREMESSA_CONSUMO_VINCULO V,
                    VWREMESSA_CONSUMO C,
                    VWREMESSA_TALAO T,
                    VWREMESSA_TALAO_DETALHE D

                WHERE
                    C.ID = V.CONSUMO_ID  
                AND T.REMESSA_ID       = V.REMESSA_ID
                AND T.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID
                AND D.STATUS           = 3
                AND D.ID               = V.REMESSA_TALAO_DETALHE_ID
                AND C.REMESSA_ID       = :REMESSA_ID_ORIGEM
                AND C.REMESSA_TALAO_ID = :REMESSA_TALAO_ID_ORIGEM
                AND T.ID               = :TALAO_ID_DESTINO
                )X
                ORDER BY ID
        ";
        
        $args = [
            'REMESSA_ID_ORIGEM'       => $param->REMESSA_ID_ORIGEM,
            'REMESSA_TALAO_ID_ORIGEM' => $param->REMESSA_TALAO_ID_ORIGEM,
            'TALAO_ID_DESTINO'        => $param->TALAO_ID_DESTINO
        ];
        
        return $this->con->query($sql,$args);
    }    
        
    
    public function insertTalaoConsumoComponenteVinculo($param)
    {
        /**
         * 'D' : BUSCA O REGISTRO NA VWREMESSA_TALAO_DETALHE
         * 'R' : BUSCA O REGISTRO NA TBREVISAO
         */
        if ( trim($param->TIPO) == 'D' && isset($param->QUANTIDADE_ALOCAR) ) {$tipo = "D.ID, D.PRODUTO_ID, D.TAMANHO, " . $param->QUANTIDADE_ALOCAR . ", QUANTIDADE_ALTERN_SALDO FROM VWREMESSA_TALAO_DETALHE D WHERE D.ID = :ITEM_ESTOQUE_ID";} else 
        if ( trim($param->TIPO) == 'D' ) {$tipo = "D.ID, D.PRODUTO_ID, D.TAMANHO, D.QUANTIDADE_SALDO, QUANTIDADE_ALTERN_SALDO FROM VWREMESSA_TALAO_DETALHE D WHERE D.ID = :ITEM_ESTOQUE_ID";} else 
        if ( trim($param->TIPO) == 'R' ) {
            $tipo = "
                    R.ID,
                    R.PRODUTO_ID,
                    R.TAMANHO,
                    (R.SALDO - (SELECT COALESCE(SUM(V.QUANTIDADE), 0)
                    FROM TBREMESSA_TALAO_VINCULO V
                    WHERE V.STATUS <> '1'
                      AND V.TIPO = 'R'
                      AND V.TABELA_ID = R.ID)),
                    (R.METRAGEM_SALDO - (SELECT COALESCE(SUM(V.QUANTIDADE_ALTERNATIVA), 0)
                    FROM TBREMESSA_TALAO_VINCULO V
                    WHERE V.STATUS <> '1'
                      AND V.TIPO = 'R'
                      AND V.TABELA_ID = R.ID))
                FROM TBREVISAO R
                WHERE R.ID = :ITEM_ESTOQUE_ID
            ";
        }
        
        $sql =
        "
            INSERT INTO TBREMESSA_TALAO_VINCULO (
                TALAO_ID,
                CONSUMO_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                TAMANHO,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA
            )
            SELECT
                :TALAO_ID,
                :CONSUMO_ID,
                :TIPO,
                /*@TIPO*/
        ";
        $args = [
            ':TALAO_ID'        => $param->TALAO_ID,
            ':CONSUMO_ID'      => $param->CONSUMO_ID,
            ':TIPO'            => $param->TIPO,
            ':ITEM_ESTOQUE_ID' => $param->ITEM_ESTOQUE_ID,
            '@TIPO'            => $tipo,
        ];
        
        
        return $this->con->query($sql, $args);
    }       
    
    public function updateRemessa($param)
    {
        $sql =
        "
            UPDATE VWREMESSA SET 
                WEB = :WEB
            WHERE
                REMESSA_ID = :REMESSA_ID
        ";
        
        $args = [
            'REMESSA_ID' => $param->REMESSA_ID,
            'WEB'        => $param->WEB
        ];
		
        return $this->con->query($sql,$args);
    }       
    
    public function updateProgramacao($param) {
        
        $estacao			= array_key_exists('ESTACAO'			, $param) ? ", ESTACAO = "			. arrayToList($param->ESTACAO           , 0) : '';
        $status				= array_key_exists('PROGRAMACAO_STATUS'	, $param) ? ", STATUS = "			. arrayToList($param->PROGRAMACAO_STATUS, 0) : '';
        $id					= array_key_exists('PROGRAMACAO_ID'     , $param) ? "AND ID        = "		. arrayToList($param->PROGRAMACAO_ID    , 0) : '';
        $talao_id			= array_key_exists('TALAO_ID'           , $param) ? "AND TABELA_ID = "		. arrayToList($param->TALAO_ID          , 0) : '';
        
        $sql =
        "
            UPDATE TBPROGRAMACAO SET
                ID = ID
                /*@ESTACAO*/
                /*@STATUS*/
            WHERE
                ID = ID
                /*@ID*/
                /*@TALAO_ID*/
        ";
        
        $args = [
            '@ESTACAO'			=> $estacao,
            '@STATUS'			=> $status,
            '@ID'				=> $id,
            '@TALAO_ID'			=> $talao_id,
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    public function updateProgramacaoHistorico($param)
    {
        $sql =
        "
            INSERT INTO TBPROGRAMACAO_REGISTRO (
                PROGRAMACAO_ID,
                STATUS,
                OPERADOR_ID
            ) VALUES (
               :PROGRAMACAO_ID,       
               :STATUS,          
               :OPERADOR_ID        
            );
        ";
        
        $args = [
            'PROGRAMACAO_ID' => $param->PROGRAMACAO_ID,
            'STATUS'         => $param->PROGRAMACAO_HISTORICO_STATUS,
            'OPERADOR_ID'    => $param->OPERADOR_ID,
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    public function updateRemessaTalao($param)
    {
        $sql =
        "
            UPDATE VWREMESSA_TALAO SET 
                STATUS = :STATUS
            WHERE
                REMESSA_ID       = :REMESSA_ID 
            AND REMESSA_TALAO_ID = :REMESSA_TALAO_ID
        ";
        
        $args = [
            'STATUS'           => $param->REMESSA_TALAO_STATUS,
            'REMESSA_ID'       => $param->REMESSA_ID,
            'REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID,
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    
    public function updateRemessaTalaoDetalhe($param)
    {
        $sql =
        "
            UPDATE VWREMESSA_TALAO_DETALHE SET 
                STATUS               = :STATUS,
                OPERADOR_ID_PRODUCAO = :OPERADOR_ID
            WHERE ID = :REMESSA_TALAO_DETALHE_ID 
        ";
        
        $args = [
            'OPERADOR_ID'              => $param->OPERADOR_ID,
            'STATUS'                   => $param->TALAO_DETALHE_STATUS,
            'REMESSA_TALAO_DETALHE_ID' => $param->REMESSA_TALAO_DETALHE_ID,
        ];
		
        return $this->con->query($sql,$args);
    }      
    
    public function updateEstacaoBloqueio($param)
    {
        $sql =
        "
            UPDATE TBUP_ESTACAO SET 
                TALAO_ID = :TALAO_ID,
                INCREMENTO = 1
            WHERE (UP_ID = :UP_ID) AND (ID = :ID);
        ";
        
        $args = [
            'TALAO_ID' => $param->ESTACAO_TALAO_ID,
            'UP_ID'    => $param->UP_ID,
            'ID'       => $param->ESTACAO,
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    public function deleteTalaoConsumoComponente($param)
    {
        $sql =
        "
            DELETE FROM TBREMESSA_TALAO_VINCULO V
            WHERE V.TIPO = 'D' AND V.TABELA_ID IN (


                SELECT DISTINCT
                    V.REMESSA_TALAO_DETALHE_ID

                FROM
                    VWREMESSA_CONSUMO C,
                    TBREMESSA_CONSUMO_VINCULO V,
                    VWREMESSA_TALAO T1,
                    VWREMESSA_TALAO T2

                WHERE
                    C.COMPONENTE = '1'
                AND V.CONSUMO_ID = C.ID
                AND T1.REMESSA_ID = C.REMESSA_ID
                AND T1.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                AND T2.REMESSA_ID = V.REMESSA_ID
                AND T2.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID
                AND T1.ID = :TALAO_ID
                AND T2.ID = :COMPONENTE_TALAO_ID
                )
        ";
        
        $args = [
            'TALAO_ID'            => $param->TALAO_ID,
            'COMPONENTE_TALAO_ID' => $param->COMPONENTE_TALAO_ID,
        ];
		
        return $this->con->query($sql,$args);
    }    
    
    public function spuReprogramarProduzido($param)
    {
        $sql = "
            EXECUTE PROCEDURE SPU_PROGRAMACAO_PRODUZIDO1(
                :ESTABELECIMENTO_ID,
                'A',
                :TALAO_ID
            );
        ";

        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'TALAO_ID'           => $param->TALAO_ID,
        ];
		
        return $this->con->query($sql,$args);
    }    
    
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _22190DAO::listar($dados);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _22190DAO::Consultar($filtro,$con);
    }

}