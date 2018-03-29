<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22160DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _22160
{
    public function __construct($con) {
        $this->con = $con;
    }  
 

    public function selectConsumo($param) {       
        
        $sql =
        "
            SELECT
                REMESSA,
                REMESSA_ID,
                REMESSA_TALAO_ID,
                TALAO_ID,
                TALAO_GP_ID,
                TALAO_PERFIL_UP,
                TALAO_MODELO_ID,
                TALAO_MODELO_DESCRICAO,
                TALAO_COR_ID,
                TALAO_COR_DESCRICAO,
                TALAO_PRODUTO_ID,
                TALAO_PRODUTO_DESCRICAO,
                TALAO_TAMANHO,
                TALAO_TAMANHO_DESCRICAO,
                TALAO_QUANTIDADE,
                TALAO_UM,
                TALAO_UP_CCUSTO,
                CONSUMO_ESTABELECIMENTO_ID,
                CONSUMO_LOCALIZACAO_ID,
                CONSUMO_PROCESSO_LOCALIZACAO_ID,
                CONSUMO_ID,
                CONSUMO_FAMILIA_ID,
                CONSUMO_PRODUTO_ID,
                CONSUMO_PRODUTO_DESCRICAO,
                CONSUMO_TAMANHO,
                CONSUMO_TAMANHO_DESCRICAO,
                QUANTIDADE_PROJECAO,
                QUANTIDADE_CONSUMO,
                QUANTIDADE_SALDO,
                CONSUMO_UM,
                CONSUMO_TOLERANCIA_MAX,
                CONSUMO_TOLERANCIA_MIN,
                DATAHORA,
                DATAHORA_INICIO,
                COALESCE(
                    (SELECT ESP.SALDO
                      FROM VWESTOQUE_SALDO_PRODUTO ESP
                     WHERE ESP.ESTABELECIMENTO_ID = CONSUMO_ESTABELECIMENTO_ID
                       AND ESP.LOCALIZACAO_ID     = CONSUMO_PROCESSO_LOCALIZACAO_ID
                       AND ESP.PRODUTO_ID         = CONSUMO_PRODUTO_ID
                       AND ESP.TAMANHO            = CONSUMO_TAMANHO),0) ESTOQUE_SALDO

            FROM (
    
                SELECT
                    R.REMESSA,
                    C.REMESSA_ID,
                    FN_LPAD(C.REMESSA_TALAO_ID,4,0) REMESSA_TALAO_ID,
                    T.ID TALAO_ID,
                    T.GP_ID TALAO_GP_ID,
                    (SELECT FIRST 1 U.PERFIL FROM TBUP U WHERE U.ID = T.UP_ID) TALAO_PERFIL_UP,
                    FN_LPAD(M.CODIGO,4,0) TALAO_MODELO_ID,
                    M.DESCRICAO TALAO_MODELO_DESCRICAO,
                    FN_LPAD(C1.CODIGO,4,0) TALAO_COR_ID,
                    C1.DESCRICAO TALAO_COR_DESCRICAO,
                    FN_LPAD(P1.CODIGO,6,0) TALAO_PRODUTO_ID,
                    P1.DESCRICAO TALAO_PRODUTO_DESCRICAO,
                    FN_LPAD(T.TAMANHO,2,0) TALAO_TAMANHO,
                    FN_TAMANHO_GRADE(P1.GRADE_CODIGO,T.TAMANHO) TALAO_TAMANHO_DESCRICAO,
                    T.QUANTIDADE TALAO_QUANTIDADE,
                    P1.UNIDADEMEDIDA_SIGLA TALAO_UM,
                    (SELECT FIRST 1 CCUSTO
                       FROM TBUP U
                      WHERE U.ID = T.UP_ID) TALAO_UP_CCUSTO,
                    C.ESTABELECIMENTO_ID CONSUMO_ESTABELECIMENTO_ID,
                    P2.LOCALIZACAO_CODIGO CONSUMO_LOCALIZACAO_ID,
                    (SELECT FIRST 1 LOCALIZACAO_ID
                       FROM TBFAMILIA_LOCALIZACAO FL,
                            TBUP U
                      WHERE U.ID = T.UP_ID
                        AND FL.FAMILIA_ID = P2.FAMILIA_CODIGO
                        AND FL.GP_ID = T.GP_ID
                        AND FL.PERFIL_UP = U.PERFIL
                        AND FL.LOCALIZACAO_TIPO_ID = 5) CONSUMO_PROCESSO_LOCALIZACAO_ID,

                    C.ID CONSUMO_ID,
                    P2.FAMILIA_CODIGO CONSUMO_FAMILIA_ID,
                    FN_LPAD(P2.CODIGO,6,0) CONSUMO_PRODUTO_ID,
                    P2.DESCRICAO CONSUMO_PRODUTO_DESCRICAO,
                    C.TAMANHO CONSUMO_TAMANHO,
                    FN_TAMANHO_GRADE(P2.GRADE_CODIGO,C.TAMANHO) CONSUMO_TAMANHO_DESCRICAO,
                    C.QUANTIDADE QUANTIDADE_PROJECAO,
                    C.QUANTIDADE_CONSUMO,
                    C.QUANTIDADE_SALDO,
                    P2.UNIDADEMEDIDA_SIGLA CONSUMO_UM,
                    FN_PRODUTO_TOLERANCIA(C.PRODUTO_ID,C.QUANTIDADE_SALDO,'MAX') CONSUMO_TOLERANCIA_MAX,
                    FN_PRODUTO_TOLERANCIA(C.PRODUTO_ID,C.QUANTIDADE_SALDO,'MIN') CONSUMO_TOLERANCIA_MIN,
                    (SELECT MAX(I.DATAHORA)
                       FROM TBESTOQUE_TRANSACAO_ITEM I
                      WHERE I.TABELA = 'TBREMESSA_CONSUMO'
                        AND I.TABELA_NIVEL = 1
                        AND I.TABELA_ID = C.ID) DATAHORA,
                    P.DATAHORA_INICIO
    
                FROM
                    VWREMESSA_CONSUMO C,
                    VWREMESSA R,
                    VWREMESSA_TALAO T,
                    TBPROGRAMACAO P,
                    TBPRODUTO P1,
                    TBMODELO M,
                    TBCOR C1,
                    TBPRODUTO P2
    
                WHERE                      
                    R.REMESSA_ID       = C.REMESSA_ID
                AND T.REMESSA_ID       = C.REMESSA_ID
                AND T.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                AND P.TIPO             = 'A'
                AND P.TABELA_ID        = T.ID
                AND P1.CODIGO          = T.PRODUTO_ID
                AND M.CODIGO           = T.MODELO_ID
                AND C1.CODIGO          = P1.COR_CODIGO
                AND P2.CODIGO          = C.PRODUTO_ID
                AND P2.FAMILIA_CODIGO  = 6
                AND P1.FAMILIA_CODIGO = 133
                /*@QUANTIDADE_SALDO*/
                /*@CONSUMO_STATUS*/
            ) X
            WHERE TRUE
            /*@DATAHORA*/
            
            
        ";
        
        $quantidade_saldo = array_key_exists('QUANTIDADE_SALDO', $param) ? "AND C.QUANTIDADE_SALDO $param->QUANTIDADE_SALDO" : '';
        $consumo_status   = array_key_exists('CONSUMO_STATUS'  , $param) ? "AND C.STATUS           $param->CONSUMO_STATUS  " : '';
        $datahora         = array_key_exists('DATAHORA'        , $param) ? "AND X.DATAHORA BETWEEN '" . $param->DATAHORA->DATAHORA_1 . "' AND '" . $param->DATAHORA->DATAHORA_2 . "'" : '';
        
        
        $args = [
            '@QUANTIDADE_SALDO' => $quantidade_saldo,
            '@CONSUMO_STATUS'   => $consumo_status,
            '@DATAHORA'         => $datahora,
        ];
        
        return $this->con->query($sql,$args);
    }

    public function selectOperador($param) {       
        
        $sql =
        "
            SELECT
                O.codigo,
                O.nome,
                coalesce((select first 1 c.valor_ext FROM tbcontrole_operador C WHERE C.operador_id = O.codigo and c.id = 28),0) as PERMICAO,
                coalesce((select first 1 j.id||' - '||j.parametro FROM tbcontrole_o j WHERE j.id = 28),'') as DESCRICAO
            from
                tboperador O
            WHERE O.codigo_barras = :COD_BARRAS
        ";
        
        
        $args = [
            ':COD_BARRAS' => $param->COD_BARRAS
        ];

        $ret = $this->con->query($sql,$args);

        if(count($ret) > 0){
            $ret = $ret[0];
        }else{
            $ret = [];
        }
        
        return $ret;
    }
    

    public function selectTransacao($param) {
        
        $sql = "
            SELECT
                TRIM('AVULSO') TIPO,
                I.TABELA_ID CONSUMO_ID,
                I.TABELA_NIVEL,
                I.QUANTIDADE,
                P.UNIDADEMEDIDA_SIGLA UM,
                I.DATA,
                FN_DATE_TO_STRING(I.DATA) DATA_TEXT,
                I.DATAHORA,
                FN_TIMESTAMP_TO_STRING(I.DATAHORA)DATAHORA_TEXT,   
                FN_LPAD(I.USUARIO_CODIGO,4,0) USUARIO_ID,       
                (SELECT FIRST 1 IIF(COALESCE(U.NOME,'') = '', U.USUARIO, U.NOME)
                   FROM TBUSUARIO U
                  WHERE U.CODIGO = I.USUARIO_CODIGO) USUARIO_DESCRICAO

            FROM
                TBESTOQUE_TRANSACAO_ITEM I,
                TBPRODUTO P

            WHERE
                P.CODIGO = I.PRODUTO_CODIGO
            AND I.TABELA = 'TBREMESSA_CONSUMO'
            AND I.TABELA_ID = :CONSUMO_ID_1
            AND I.TABELA_NIVEL = 1
            AND I.TIPO = 'S'

            UNION

            SELECT
                TRIM('PEÇA') TIPO,
                V.ORIGEM_TABELA_ID,
                V.ORIGEM_NIVEL,
                V.QUANTIDADE,  
                P.UNIDADEMEDIDA_SIGLA UM,
                I.DATA,
                FN_DATE_TO_STRING(I.DATA) DATA_TEXT,
                I.DATAHORA,
                FN_TIMESTAMP_TO_STRING(I.DATAHORA)DATAHORA_TEXT,    
                FN_LPAD(I.USUARIO_CODIGO,4,0) USUARIO_ID,
                (SELECT FIRST 1 IIF(COALESCE(U.NOME,'') = '', U.USUARIO, U.NOME)
                   FROM TBUSUARIO U
                  WHERE U.CODIGO = I.USUARIO_CODIGO) USUARIO_DESCRICAO

            FROM
                TBREMESSA_TALAO_VINCULO V,
                TBESTOQUE_TRANSACAO_ITEM I,
                TBPRODUTO P

            WHERE
                P.CODIGO = V.PRODUTO_ID
            AND V.ORIGEM_TABELA = 'TBREMESSA_CONSUMO'
            AND V.ORIGEM_NIVEL = 1
            AND V.ORIGEM_TABELA_ID = :CONSUMO_ID_2
            AND I.CONTROLE = V.ESTOQUE_ID_ENTRADA
        ";
        
        $args = [
            'CONSUMO_ID_1' => $param->CONSUMO_ID,
            'CONSUMO_ID_2' => $param->CONSUMO_ID
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function updateConsumo($param) {       
        
        $sql =
        "
            UPDATE VWREMESSA_CONSUMO C
               SET C.QUANTIDADE_CONSUMO = COALESCE(C.QUANTIDADE_CONSUMO,0) + :QUANTIDADE_CONSUMO,
                   C.STATUS = :CONSUMO_STATUS,
                   C.COMPONENTE = NULL
             WHERE C.ID = :CONSUMO_ID
        ";
        
        $args = [
            'QUANTIDADE_CONSUMO' => $param->QUANTIDADE_CONSUMO,
            'CONSUMO_STATUS'     => $param->CONSUMO_STATUS,
            'CONSUMO_ID'         => $param->CONSUMO_ID,
        ];
        
        return $this->con->query($sql,$args);
    }

    public function historico($param) {       
        
        $sql = "EXECUTE procedure SPI_HISTORICO(:TABELA, :TABELA_ID, :HISTORICO)";
        
        $args = [
            ':TABELA'    => $param->TABELA,
            ':TABELA_ID' => $param->TABELA_ID,
            ':HISTORICO' => $param->HISTORICO,
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function updateConsumoBaixado($param) {       
        
        $sql =
        "
            UPDATE VWREMESSA_CONSUMO C
               SET C.QUANTIDADE_CONSUMO = COALESCE(C.QUANTIDADE_CONSUMO,0) - :QUANTIDADE_CONSUMO,
                   C.STATUS = :CONSUMO_STATUS,
                   C.COMPONENTE = NULL
             WHERE C.ID = :CONSUMO_ID
        ";
        
        $args = [
            'QUANTIDADE_CONSUMO' => $param->QUANTIDADE_CONSUMO,
            'CONSUMO_STATUS'     => $param->CONSUMO_STATUS,
            'CONSUMO_ID'         => $param->CONSUMO_ID,
        ];
        
        return $this->con->query($sql,$args);
    }
    

    public function insertTransacao($param) {
        
        $sql =
        "
            EXECUTE PROCEDURE SPI_ESTOQUE_TRANSACAO_REGRA(
                3,
                :GP_ID,
                :PERFIL_UP,
                :FAMILIA_ID,
                :LOCALIZACAO_ID,
                'TBREMESSA_CONSUMO',
                1,
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
                :DOCUMENTO
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
            'DOCUMENTO'          => $param->DOCUMENTO
        ];
        
        return $this->con->query($sql,$args);
    }    

    public function deleteTransacao($param) {

        $sql = "
            DELETE
              FROM TBESTOQUE_TRANSACAO_ITEM
             WHERE TABELA       = 'TBREMESSA_CONSUMO'
               AND TABELA_NIVEL = 1
               AND TABELA_ID    = :CONSUMO_ID
        ";
        
        $args = [
            'CONSUMO_ID' => $param->CONSUMO_ID,
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
    
    public function selectRemessaTalaoEAN($param) {
        $sql = "
            SELECT
                MODELO_ID,
                MODELO_DESCRICAO,
                COR_ID,
                COR_DESCRICAO,
                TAMANHO,
                TAMANHO_DESCRICAO,
                CODIGO_EAN

            FROM
                (SELECT
                    ROW_NUMBER() OVER(PARTITION BY T.REMESSA_ID, T.REMESSA_TALAO_ID ORDER BY T.REMESSA_ID, T.REMESSA_TALAO_ID) NUMBER,
                    FN_ARRENDONDAR_PARA_CIMA(T.QUANTIDADE/2) ETIQUETA,
                    M.CODIGO MODELO_ID,
                    M.DESCRICAO MODELO_DESCRICAO,
                    C.CODIGO COR_ID,
                    C.DESCRICAO COR_DESCRICAO,
                    T.TAMANHO,
                    FN_TAMANHO_GRADE(P.GRADE_CODIGO,T.TAMANHO) TAMANHO_DESCRICAO,
                    (SELECT FIRST 1 E.PREFIXO || E.CODIGO
                       FROM TBEAN E
                      WHERE E.MODELO_ID = P.MODELO_CODIGO
                        AND E.COR_ID    = P.COR_CODIGO
                        AND E.TAMANHO   = T.TAMANHO) CODIGO_EAN


                FROM
                    VWREMESSA_TALAO T,
                    TBPRODUTO P,
                    TBMODELO M,
                    TBCOR C,
                    SP_GERAR_LINHAS(100) AG

                WHERE TRUE
                AND P.CODIGO = T.PRODUTO_ID
                AND M.CODIGO = P.MODELO_CODIGO
                AND C.CODIGO = P.COR_CODIGO
                AND T.ID = :TALAO_ID

                ORDER BY T.REMESSA_ID, T.REMESSA_TALAO_ID) X
            WHERE
                X.NUMBER <= X.ETIQUETA            
        ";
        
        $args = [
            'TALAO_ID' => $param->TALAO_ID
        ];
        
        return $this->con->query($sql,$args);                
    }
}
