<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22180DAO;

/**
 * Objeto _22180 - Registro de Producao - Div. Bojo Colante
 */
class _22180
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
    

    public function getTaloesComposicao($param1) {
        
        
        $arr_taloes   = $this->selectTalao($param1);
        $arr_consumos = [];
        $arr_historicos = [];
        $arr_detalhes = [];
        
        foreach ( $arr_taloes as $talao ) {
        
                    
            $consumos = $this->selectTalaoConsumo((object)[
                'TALAO_ID' => $talao->TALAO_ID,
            ]);
            
            foreach ( $consumos as $consumo ) {
                array_push($arr_consumos, $consumo);
            }

            $historicos = $this->selectTalaoHistorico((object)[
                'TALAO_ID' => $talao->TALAO_ID
            ]);
            
            foreach ( $historicos as $historico ) {
                array_push($arr_historicos, $historico);
            }

            $detalhes = $this->selectTalaoDetalhe((object)[
                'REMESSA_ID'       => $talao->REMESSA_ID,
                'REMESSA_TALAO_ID' => $talao->REMESSA_TALAO_ID
            ]);
            
            foreach ( $detalhes as $detalhe ) {
                array_push($arr_detalhes, $detalhe);
            }
            
        }
        
        return [
            'TALOES'     => $arr_taloes,
            'CONSUMOS'   => $arr_consumos,
            'HISTORICOS' => $arr_historicos,
            'DETALHES'   => $arr_detalhes,
            'INDICADOR'  => $this->selectIndicador($param1)[0]
        ];
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


        $ret->HISTORICOS = $this->selectTalaoHistorico((object)[
            'TALAO_ID' => $talao->TALAO_ID
        ]);

        $ret->DETALHES = $this->selectTalaoDetalhe((object)[
            'REMESSA_ID'       => $talao->REMESSA_ID,
            'REMESSA_TALAO_ID' => $talao->REMESSA_TALAO_ID
        ]);
        
        
        return $ret;
    }
    
    public function postTalaoAcao($param1) {
        
        $param1->WEB = '2';
        $this->updateRemessa($param1);
        
        if ( $param1->ULTIMO_TALAO ) {
            $this->updateProgramacaoHistorico($param1);
        }
        
        $this->updateRemessaTalaoDetalhe($param1);
//            
//        if ( array_key_exists('REMESSA_TALAO_STATUS', $param1) ) {
//            $this->updateRemessaTalao($param1);
//        }
        
//        $this->updateProgramacao         ($param1);
//        $this->updateEstacaoBloqueio     ($param1);

//        if ( $param1->PROGRAMACAO_STATUS == '3' ) {
//            $this->spuReprogramarProduzido($param1);
//        }
        
        
    }    
    
    public function selectIndicador($param) {
        
        
        if ( isset($param->DATA_1) ) {
            $sql = "
                SELECT *
                  FROM SPC_INDICADOR_BOJO_COLANTE(:DATA_1,:DATA_2)
            ";

            $args = [
                'DATA_1'              => $param->DATA_1,
                'DATA_2'              => $param->DATA_2
            ];    

            return $this->con->query($sql,$args);
        } else {
            $sql = "
                SELECT *
                  FROM SPC_INDICADOR_BOJO_COLANTE
            ";

            return $this->con->query($sql);            
        }
    } 
    
    public function selectTalao($param1) {
        
        $sql = "
            SELECT FIRST :FIRST SKIP :SKIP
                R.DATA                                     REMESSA_DATA,
                R.REMESSA                                  REMESSA,
                T.REMESSA_ID                               REMESSA_ID,
                FN_LPAD(T.REMESSA_TALAO_ID,4,0)            REMESSA_TALAO_ID,
                T.ID                                       TALAO_ID,
                FN_LPAD(P.MODELO_CODIGO,5,0)               MODELO_ID,
                (SELECT M.DESCRICAO
                   FROM TBMODELO M
                  WHERE M.CODIGO = P.MODELO_CODIGO)        MODELO_DESCRICAO,
                FN_LPAD(P.COR_CODIGO,4,0)                  COR_ID,
                (SELECT C.DESCRICAO
                   FROM TBCOR C
                  WHERE C.CODIGO = P.COR_CODIGO)           COR_DESCRICAO,
                FN_LPAD(P.GRADE_CODIGO,2,0)                GRADE_ID,
                FN_LPAD(T.TAMANHO,2,0)                     TAMANHO,
                FN_TAMANHO_GRADE(P.GRADE_CODIGO,T.TAMANHO) TAMANHO_DESCRICAO,
                FN_LPAD(P.CODIGO,6,0)                      PRODUTO_ID,
                P.DESCRICAO                                PRODUTO_DESCRICAO,
                A.TEMPO                                    TEMPO_PREVISTO,
                COALESCE(A.TEMPO_REALIZADO,0)              TEMPO_REALIZADO,
                T.QUANTIDADE                               QUANTIDADE_PROJETADA,
                TRIM(P.UNIDADEMEDIDA_SIGLA)                UM,
                A.ID                                       PROGRAMACAO_ID, 
                TRIM(A.STATUS)                             PROGRAMACAO_STATUS,
                TRIM(CASE A.STATUS
                WHEN '0' THEN 'NÃO INICIADO'
                WHEN '1' THEN 'PARADO'
                WHEN '2' THEN 'EM ANDAMENTO'
                WHEN '3' THEN 'FINALIZADO'
                WHEN '6' THEN 'ENCERRADO'
                ELSE 'N/D' END) PROGRAMACAO_STATUS_DESCRICAO,
                T.STATUS TALAO_STATUS,
                A.DATAHORA_INICIO,
                A.DATAHORA_FIM,
                IIF(FN_GP_REMESSA_LIBERADA(T.REMESSA_ID,T.GP_ID,T.UP_ID),1,0) REMESSA_LIBERADA,
                A.DATA_INICIADO DATAHORA_INICIADO,
                A.DATAHORA_FINALIZADO

            FROM
                VWREMESSA_TALAO T,
                VWREMESSA R,
                TBPROGRAMACAO A,
                TBPRODUTO P

            WHERE TRUE
            AND R.REMESSA_ID = T.REMESSA_ID
            AND A.TIPO       = 'A'
            AND A.TABELA_ID  = T.ID
            AND P.CODIGO     = T.PRODUTO_ID
            /*@GP_ID*/
            /*@UP_ID*/
            /*@ESTACAO*/
            /*@PROGRAMACAO_STATUS*/
            /*@TALAO_STATUS*/
            /*@DATA_PRODUCAO*/
            
            ORDER BY A.DATAHORA_INICIO
        ";
        
        $param = (object)[];

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

        if ( isset($param1->TALAO_STATUS) && trim($param1->TALAO_STATUS) != '' ) {
            $param->TALAO_STATUS = $param1->TALAO_STATUS;
        }

        if ( isset($param1->DATA_PRODUCAO) && trim($param1->DATA_PRODUCAO) != '' ) {
            $param->DATA_PRODUCAO = $param1->DATA_PRODUCAO;
        }
        
        $gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID   $param->GP_ID             " : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID   $param->UP_ID             " : '';
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND T.ESTACAO $param->ESTACAO           " : '';
        $programacao_status = array_key_exists('PROGRAMACAO_STATUS', $param) ? "AND A.STATUS  $param->PROGRAMACAO_STATUS" : '';
        $talao_status       = array_key_exists('TALAO_STATUS'      , $param) ? "AND T.STATUS  $param->TALAO_STATUS      " : '';
        $data_producao      = array_key_exists('DATA_PRODUCAO'     , $param) ? "AND T.DATA_PRODUCAO $param->DATA_PRODUCAO       " : '';
        
        $args = [
            'FIRST'               => setDefValue($param1->FIRST, 10),
            'SKIP'                => setDefValue($param1->SKIP ,  0),
            '@GP_ID'              => $gp_id,
            '@UP_ID'              => $up_id,
            '@ESTACAO'            => $estacao,
            '@PROGRAMACAO_STATUS' => $programacao_status,
            '@TALAO_STATUS'       => $talao_status,
            '@DATA_PRODUCAO'      => $data_producao
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectTalaoDetalhe($param1) {
        
        $sql = "
            SELECT
                D.REMESSA_ID,
                D.REMESSA_TALAO_ID,
                D.ID REMESSA_TALAO_DETALHE_ID,
                D.PRODUTO_ID,
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
                    'H' TIPO,
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
    
                UNION

                SELECT
                    'T',
                    D.ID,
                    P.ID,
                    D.OPERADOR_ID_PRODUCAO,
                    O.NOME,
                    D.DATAHORA_PRODUCAO,
                    11,
                    'TALAO: ' || D.ID || ' PRODUZIDO',
                    0
    
                FROM
                    VWREMESSA_TALAO T,
                    VWREMESSA_TALAO_DETALHE D,
                    TBOPERADOR O,
                    TBPROGRAMACAO P
                WHERE
                    T.REMESSA_ID       = D.REMESSA_ID
                AND T.REMESSA_TALAO_ID = D.REMESSA_TALAO_ID
                AND D.STATUS           > 1
                AND O.CODIGO = D.OPERADOR_ID_PRODUCAO
                AND P.TABELA_ID = T.ID
                AND P.TIPO = 'A'
                /*@TALAO_ID*/
                )X

                ORDER BY DATAHORA DESC
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
    
    public function selectTalaoConsumo($param1) {
        
        $sql = "
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
                X.QUANTIDADE_SALDO,   
                X.UM,
                X.FAMILIA_ID,
                (SELECT FIRST 1 LOCALIZACAO_CODIGO FROM TBFAMILIA WHERE CODIGO = X.FAMILIA_ID) LOCALIZACAO_ID,
                LOCALIZACAO_ID_PROCESSO,
                CONSUMO_STATUS,
                CONSUMO_STATUS_DESCRICAO,
                COALESCE(
                (SELECT FIRST 1 
                        (SALDO -
                        COALESCE((
                            SELECT A.QUANTIDADE
                              FROM TBESTOQUE_SALDO_ALOCACAO A
                             WHERE A.ESTABELECIMENTO_ID = X.ESTABELECIMENTO_ID
                               AND A.LOCALIZACAO_ID     = X.LOCALIZACAO_ID_PROCESSO
                               AND A.PRODUTO_ID         = X.PRODUTO_ID
                               AND A.TAMANHO            = X.TAMANHO),0))                        
                   FROM VWESTOQUE_SALDO_PRODUTO SP
                  WHERE SP.ESTABELECIMENTO_ID  = X.ESTABELECIMENTO_ID
                    AND SP.PRODUTO_ID          = X.PRODUTO_ID
                    AND SP.TAMANHO             = X.TAMANHO
                    AND SP.LOCALIZACAO_ID      = X.LOCALIZACAO_ID_PROCESSO
                    ),0) ESTOQUE_SALDO
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
                    C.QUANTIDADE_CONSUMO QUANTIDADE_CONSUMIDA,
                    C.QUANTIDADE_SALDO,   
                    P.UNIDADEMEDIDA_SIGLA UM,
                    P.FAMILIA_CODIGO FAMILIA_ID,
                    TRIM(IIF(C.QUANTIDADE <= C.QUANTIDADE_SALDO,'0','1')) CONSUMO_STATUS,
                    TRIM(IIF(C.QUANTIDADE <= C.QUANTIDADE_SALDO,'INDISPONÍVEL','DISPONÍVEL')) CONSUMO_STATUS_DESCRICAO,
                    (SELECT FIRST 1 FL.LOCALIZACAO_ID
                       FROM TBFAMILIA_LOCALIZACAO FL
                      WHERE FL.FAMILIA_ID          = F.CODIGO
                        AND FL.GP_ID               = T.GP_ID
                        AND FL.PERFIL_UP           = U.PERFIL
                        AND FL.LOCALIZACAO_TIPO_ID = 5 -- EM PROCESSO
                      ) LOCALIZACAO_ID_PROCESSO

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
    
    public function selectEtiqueta($param) {
        $sql = "
            SELECT
                *

            FROM
                TBETIQUETAS E

            WHERE
                E.TIPO = :ETIQUETA_TIPO           
        ";
        
        $args = [
            'ETIQUETA_TIPO' => $param->ETIQUETA_TIPO
        ];
        
        return $this->con->query($sql,$args);                
    }    
    
    public function selectEtiquetaDados($param) {
        $sql = "
            SELECT
                REMESSA,
                REMESSA_TALAO_ID,
                REMESSA_TALAO_DETALHE_ID,
                PRODUTO_ID,
                MODELO_ID,
                MODELO_DESCRICAO,
                COR_ID,
                COR_DESCRICAO,
                TAMANHO,
                TAMANHO_DESCRICAO_BR,
                TAMANHO_DESCRICAO_USA,
                TAMANHO_DESCRICAO_EUR,
                QUANTIDADE_PRODUCAO,
                DATAHORA_PRODUCAO,
                GP_ID,
                GP_DESCRICAO,
                UP_ID,
                UP_DESCRICAO,
                OPERADOR_ID,
                OPERADOR_DESCRICAO,
                VIA_ETIQUETA,
                CODIGO_BARRAS

            FROM (
                SELECT
                    X.*,

                    (SELECT FIRST 1
                            O.NOME
                       FROM TBOPERADOR O
                      WHERE O.CODIGO = X.OPERADOR_ID) OPERADOR_DESCRICAO,
                    X.PRODUTO_ID || X.TAMANHO || FN_LPAD(X.QUANTIDADE_PRODUCAO,3,0) CODIGO_BARRAS

                FROM (
                    SELECT
                        R.REMESSA,
                        FN_LPAD(T.REMESSA_TALAO_ID,4,0) REMESSA_TALAO_ID,
                        FN_LPAD(D.ID,8,0) REMESSA_TALAO_DETALHE_ID,
                        FN_LPAD(P.CODIGO,5,0) PRODUTO_ID,
                        M.CODIGO MODELO_ID,
                        M.DESCRICAO MODELO_DESCRICAO,
                        C.CODIGO COR_ID,
                        C.DESCRICAO COR_DESCRICAO,
                        FN_LPAD(D.TAMANHO,2,0) TAMANHO,
                        FN_TAMANHO_GRADE(P.GRADE_CODIGO,D.TAMANHO,'BR') TAMANHO_DESCRICAO_BR,  
                        FN_TAMANHO_GRADE(P.GRADE_CODIGO,D.TAMANHO,'USA') TAMANHO_DESCRICAO_USA,
                        FN_TAMANHO_GRADE(P.GRADE_CODIGO,D.TAMANHO,'EUR') TAMANHO_DESCRICAO_EUR,
                        CAST(D.QUANTIDADE_PRODUCAO AS INTEGER) QUANTIDADE_PRODUCAO,
                        D.DATAHORA_PRODUCAO,
                        D.GP_ID,
                        (SELECT FIRST 1
                                G.DESCRICAO
                           FROM TBGP G
                          WHERE G.ID = D.GP_ID) GP_DESCRICAO,
                        PR.UP_ID,
                        (SELECT FIRST 1
                                U.DESCRICAO
                           FROM TBUP U
                          WHERE U.ID = PR.UP_ID) UP_DESCRICAO,
                        D.VIA_ETIQUETA,
                        CAST(:OPERADOR_ID AS INTEGER) OPERADOR_ID



                    FROM
                        VWREMESSA_TALAO_DETALHE D,
                        VWREMESSA_TALAO T,
                        VWREMESSA R,
                        TBPROGRAMACAO PR,
                        TBPRODUTO P,
                        TBMODELO M,
                        TBCOR C

                    WHERE TRUE
                    AND T.REMESSA_ID       = D.REMESSA_ID
                    AND T.REMESSA_TALAO_ID = D.REMESSA_TALAO_ID
                    AND R.REMESSA_ID       = T.REMESSA_ID
                    AND PR.TABELA_ID       = T.ID
                    AND PR.TIPO            = 'A'
                    AND P.CODIGO           = D.PRODUTO_ID
                    AND M.CODIGO           = P.MODELO_CODIGO
                    AND C.CODIGO           = P.COR_CODIGO
                    AND D.ID               = :REMESSA_TALAO_DETALHE_ID
                    AND D.STATUS           = 2
                ) X
            ) Y      
        ";
        
        
        $sql_1 = "
            UPDATE VWREMESSA_TALAO_DETALHE D
               SET D.VIA_ETIQUETA = D.VIA_ETIQUETA + 1
             WHERE D.ID = :REMESSA_TALAO_DETALHE_ID
        ";        
        
        $args_1 = [
            'REMESSA_TALAO_DETALHE_ID' => $param->REMESSA_TALAO_DETALHE_ID
        ];
        
        $args = [
            'REMESSA_TALAO_DETALHE_ID' => $param->REMESSA_TALAO_DETALHE_ID,
            'OPERADOR_ID'              => $param->OPERADOR_ID
        ];

        $this->con->query($sql_1,$args_1);
                
        return $this->con->query($sql,$args);                
    }    
    
    public function updateProgramacao($param)
    {
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
                STATUS = :STATUS,
                OPERADOR_ID_PRODUCAO = :OPERADOR_ID_PRODUCAO
            WHERE ID = :REMESSA_TALAO_DETALHE_ID 
        ";
        
        $args = [
            'STATUS'                   => $param->TALAO_DETALHE_STATUS,
            'REMESSA_TALAO_DETALHE_ID' => $param->REMESSA_TALAO_DETALHE_ID,
            'OPERADOR_ID_PRODUCAO'     => $param->OPERADOR_ID,
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
		return _22180DAO::listar($dados);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _22180DAO::Consultar($filtro,$con);
    }

}