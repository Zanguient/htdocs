<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15080DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _15080
{
    public function __construct($con) {
        $this->con = $con;
    }  
 

    public function selectProdutoEstoqueMinimo($param1) {       
        
        $sql =
        "
            SELECT
                X.*,
                IIF( ESTOQUE_FISICO < ESTOQUE_MIN, ESTOQUE_MAX - ESTOQUE_FISICO, 0 ) ESTOQUE_NECESSIDADE
            FROM (
                SELECT
                    L.ID KANBAN_LOTE_ID,
                    E.ID ESTOQUE_MINIMO_ID,
                    FN_LPAD(E.ESTABELECIMENTO_ID,3,0) ESTABELECIMENTO_ID,     
                    FN_LPAD(E.LOCALIZACAO_ID,3,0) LOCALIZACAO_ID,
                    L1.DESCRICAO LOCALIZACAO_DESCRICAO,
                    FN_LPAD(E.PRODUTO_ID,6,0) PRODUTO_ID,     
                    E.PRODUTO_DESCRICAO,
                    FN_LPAD(P.LOCALIZACAO_CODIGO,3,0) PRODUTO_LOCALIZACAO_ID,
                    L2.DESCRICAO PRODUTO_LOCALIZACAO_DESCRICAO,
                    FN_LPAD(E.FAMILIA_ID,3,0)FAMILIA_ID,
                    (SELECT FIRST 1 DESCRICAO FROM TBFAMILIA F WHERE F.CODIGO = E.FAMILIA_ID) FAMILIA_DESCRICAO,
                    E.SEGURANCA,
                    E.PONTO_REPOSICAO,
                    TRIM(E.TIPO_REPOSICAO) TIPO_REPOSICAO,
                    FN_LPAD(P.GRADE_CODIGO,2,0) GRADE_ID,
                    E.TAMANHO,
                    E.TAM_DESCRICAO TAMANHO_DESCRICAO,
                    E.ESTOQUE_MIN,
                    E.ESTOQUE_MAX,
                    COALESCE(
                    (SELECT FIRST 1 SUM(SP.SALDO)
                       FROM VWESTOQUE_SALDO_PRODUTO SP
                      WHERE SP.ESTABELECIMENTO_ID = E.ESTABELECIMENTO_ID
                        AND SP.LOCALIZACAO_ID     = E.LOCALIZACAO_ID
                        AND SP.PRODUTO_ID         = E.PRODUTO_ID
                        AND SP.TAMANHO            = E.TAMANHO),0) ESTOQUE_FISICO,
                    COALESCE(
                    (SELECT FIRST 1 SUM(SP.SALDO)
                       FROM VWESTOQUE_SALDO_PRODUTO SP
                      WHERE SP.ESTABELECIMENTO_ID = E.ESTABELECIMENTO_ID
                        AND SP.LOCALIZACAO_ID     = P.LOCALIZACAO_CODIGO
                        AND SP.PRODUTO_ID         = E.PRODUTO_ID
                        AND SP.TAMANHO            = E.TAMANHO),0) PRODUTO_ESTOQUE_FISICO,
                    P.UNIDADEMEDIDA_SIGLA UM,
                    (SELECT SUM(D.QUANTIDADE)
                       FROM TBKANBAN_LOTE_DETALHE D
                      WHERE D.KANBAN_LOTE_ID = L.ID
                        AND D.ESTOQUE_MINIMO_ID = E.ID) QUANTIDADE_LOTE
    
                FROM
                    VWESTOQUE_MINIMO_TAMANHO E
                    LEFT JOIN TBKANBAN_LOTE L ON L.LOCALIZACAO_ID = E.LOCALIZACAO_ID AND L.STATUS = '0',
                    TBLOCALIZACAO L1,
                    TBPRODUTO P,
                    TBLOCALIZACAO L2,
                    TBFAMILIA_FICHA F
    
    
                WHERE
                    L1.CODIGO                = E.LOCALIZACAO_ID
                AND P.CODIGO                 = E.PRODUTO_ID
                AND L2.CODIGO                = P.LOCALIZACAO_CODIGO
                AND F.FAMILIA_CODIGO         = P.FAMILIA_CODIGO
                AND F.ESTABELECIMENTO_CODIGO = E.ESTABELECIMENTO_ID
                AND P.CODIGO                 = E.PRODUTO_ID
                AND E.HABILITA_KANBAN        = '1'
                /*@LOCALIZACAO_ID*/
                /*@KANBAN_LOTE_ID*/
                ) X
        ";
        
        $param = (object)[];

        if ( isset($param1->LOCALIZACAO_ID) && $param1->LOCALIZACAO_ID > -1 ) {
            $param->LOCALIZACAO_ID = " = $param1->LOCALIZACAO_ID";
        }

        if ( isset($param1->KANBAN_LOTE_ID) && $param1->KANBAN_LOTE_ID > -1 ) {
            $param->KANBAN_LOTE_ID = " = $param1->KANBAN_LOTE_ID";
        }

        $localizacao_id = array_key_exists('LOCALIZACAO_ID', $param) ? "AND E.LOCALIZACAO_ID $param->LOCALIZACAO_ID" : '';
        $kanban_lote_id = array_key_exists('KANBAN_LOTE_ID', $param) ? "AND L.ID             $param->KANBAN_LOTE_ID" : '';
        
        $args = [
            '@LOCALIZACAO_ID' => $localizacao_id,
            '@KANBAN_LOTE_ID' => $kanban_lote_id
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectLocalizacoes() {
        
        $sql = "
            SELECT
                DISTINCT
                L1.CODIGO LOCALIZACAO_ID,
                L1.DESCRICAO LOCALIZACAO_DESCRICAO
            FROM
                VWESTOQUE_MINIMO_TAMANHO E,
                TBLOCALIZACAO L1
            WHERE
                L1.CODIGO                = E.LOCALIZACAO_ID
            AND E.HABILITA_KANBAN        = '1'
        ";
        
        return $this->con->query($sql);
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
               AND ((R.RESULTADO = 'I' OR R.RESULTADO = 'R') OR (R.RESULTADO = 'P' AND R.STATUS_OB = '2'))
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
                       AND V.TIPO = 'R'
                       AND V.TABELA_ID = D.ID)) QUANTIDADE_SALDO,
                   (D.QUANTIDADE_ALTERN_SALDO - (
                    SELECT COALESCE(SUM(V.QUANTIDADE_ALTERNATIVA), 0)
                      FROM TBREMESSA_TALAO_VINCULO V
                     WHERE V.STATUS <> '1'
                       AND V.TIPO = 'R'
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
                C.STATUS

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
                    FN_TIMESTAMP_TO_STRING(I.DATAHORA)DATAHORA_TEXT

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
    
    public function selectTransacao($param) {
        
        $sql = "
            SELECT FIRST 10
                D.KANBAN_LOTE_ID,
                FN_LPAD(D.ID,6,0) KANBAN_LOTE_DETALHE_ID,
                IIF(D.PECA_ID > 0, 'PEÇA','AVULSO') TIPO,
                D.ESTOQUE_MINIMO_ID,
                D.QUANTIDADE,
                P.UNIDADEMEDIDA_SIGLA UM,
                D.DATAHORA,
                FN_TIMESTAMP_TO_STRING(D.DATAHORA)DATAHORA_TEXT,
                (SELECT FIRST 1 FN_LPAD(U.CODIGO,4,0) || ' - ' || IIF(COALESCE(U.NOME,'') = '', U.USUARIO, U.NOME)
                   FROM TBUSUARIO U
                  WHERE U.USUARIO = D.USUARIO) USUARIO_DESCRICAO


            FROM
                TBKANBAN_LOTE_DETALHE D,
                TBPRODUTO P

            WHERE
                P.CODIGO = D.PRODUTO_ID
            AND D.ESTOQUE_MINIMO_ID = :ESTOQUE_MINIMO_ID
            AND D.KANBAN_LOTE_ID = :KANBAN_LOTE_ID

            ORDER BY D.DATAHORA DESC
        ";
        
        $args = [
            'KANBAN_LOTE_ID'    => $param->KANBAN_LOTE_ID,
            'ESTOQUE_MINIMO_ID' => $param->ESTOQUE_MINIMO_ID,
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectKanbanLote($param1) {
        
        $sql = "
            SELECT
                FN_LPAD(L.ID,5,'0') KANBAN_LOTE_ID,
                L.LOCALIZACAO_ID,
                (SELECT FIRST 1 DESCRICAO FROM TBLOCALIZACAO WHERE CODIGO = L.LOCALIZACAO_ID) LOCALIZACAO_DESCRICAO,
                L.DATAHORA_INICIADO,
                L.USUARIO_INICIO,
                L.DATAHORA_FINALIZADO,
                L.USUARIO_FIM,
                L.STATUS,
                CASE L.STATUS
                WHEN '0' THEN 'INICIADO'
                WHEN '1' THEN 'FINALIZADO'
                END STATUS_DESCRICAO

            FROM
                TBKANBAN_LOTE L
            WHERE TRUE
                /*@KANBAN_LOTE_ID*/
                /*@KANBAN_LOTE_STATUS*/
        ";
        
        $param = (object)[];

        if ( isset($param1->KANBAN_LOTE_ID) && $param1->KANBAN_LOTE_ID > -1 ) {
            $param->KANBAN_LOTE_ID = " = $param1->KANBAN_LOTE_ID";
        }

        if ( isset($param1->KANBAN_LOTE_STATUS) && trim($param1->KANBAN_LOTE_STATUS) != '' ) {
            $param->KANBAN_LOTE_STATUS = $param1->KANBAN_LOTE_STATUS;
        }

        $kanban_lote_id     = array_key_exists('KANBAN_LOTE_ID'    , $param) ? "AND L.ID     $param->KANBAN_LOTE_ID    " : '';
        $kanban_lote_status = array_key_exists('KANBAN_LOTE_STATUS', $param) ? "AND L.STATUS $param->KANBAN_LOTE_STATUS" : '';
        
        $args = [
            '@KANBAN_LOTE_ID'     => $kanban_lote_id,
            '@KANBAN_LOTE_STATUS' => $kanban_lote_status,
        ];
        
        return $this->con->query($sql,$args);
    }

    public function lotes_gerados($param) {
        
        $sql = "
            SELECT
                FN_LPAD(L.ID,5,'0') KANBAN_LOTE_ID,
                L.LOCALIZACAO_ID,
                (SELECT FIRST 1 DESCRICAO FROM TBLOCALIZACAO WHERE CODIGO = L.LOCALIZACAO_ID) LOCALIZACAO_DESCRICAO,
                formatdatetime(L.DATAHORA_INICIADO) as DATAHORA_INICIADO,
                L.USUARIO_INICIO,
                formatdatetime(L.DATAHORA_FINALIZADO) as DATAHORA_FIM,
                L.USUARIO_FIM,
                L.STATUS,
                CASE L.STATUS
                WHEN '0' THEN 'INICIADO'
                WHEN '1' THEN 'FINALIZADO'
                END STATUS_DESCRICAO

            FROM
                TBKANBAN_LOTE L
            WHERE L.DATAHORA_INICIADO between :DATA1 and :DATA2

            order by l.id desc
        ";

        $args = [
            ':DATA1' => $param->DATA1,
            ':DATA2' => $param->DATA2,
        ];
        
        return $this->con->query($sql,$args);
    }

    public function lotes_gerados_detalhe($param) {
        
        $sql = "
            SELECT
                D.ID,
                FN_LPAD(D.KANBAN_LOTE_ID,5,0) KANBAN_LOTE_ID,
                FN_LPAD(D.ID,6,0) KANBAN_LOTE_DETALHE_ID,
                IIF(D.PECA_ID > 0, 'PEÇA','AVULSO') TIPO,
                D.ESTOQUE_MINIMO_ID,
                D.QUANTIDADE,
                P.UNIDADEMEDIDA_SIGLA UM,
                D.DATAHORA,
                FN_TIMESTAMP_TO_STRING(D.DATAHORA)DATAHORA_TEXT,
                (SELECT FIRST 1 FN_LPAD(U.CODIGO,4,0) || ' - ' || IIF(COALESCE(U.NOME,'') = '', U.USUARIO, U.NOME)
                   FROM TBUSUARIO U
                  WHERE U.USUARIO = D.USUARIO) USUARIO_DESCRICAO,
                p.descricao  as PRODUTO_DESCRICAO,
                lpad(p.codigo,6,0) as PRODUTO_ID,
                d.estoque_id_saida,
                d.estoque_id_entrada,
                coalesce((SELECT first 1 v.tipo||'/'||v.tabela_id FROM TBREMESSA_TALAO_VINCULO V WHERE V.ID = d.peca_id),0) as peca_id,
                coalesce((select first 1 i.conferencia from tbestoque_transacao_item i where i.controle = d.estoque_id_entrada),0) as conferencia

            FROM
                TBKANBAN_LOTE L, tbkanban_lote_detalhe d, TBPRODUTO P
            WHERE L.DATAHORA_INICIADO between :DATA1 and :DATA2
            and d.kanban_lote_id = l.id
            and P.CODIGO = D.PRODUTO_ID


            order by l.id desc
        ";

        $args = [
            ':DATA1' => $param->DATA1,
            ':DATA2' => $param->DATA2,
        ];
        
        return $this->con->query($sql,$args);
    }
    
    public function selectKanbanLoteDetalheAgrup($param1) {
        
        $sql = "
            SELECT
                FN_LPAD(D.PRODUTO_ID,6,0) PRODUTO_ID,
                FN_ELIPSES(P.DESCRICAO,27) PRODUTO_DESCRICAO,
                TAMANHO,
                FN_TAMANHO_GRADE(P.GRADE_CODIGO,D.TAMANHO) TAMANHO_DESCRICAO,
                D.USUARIO,
                P.UNIDADEMEDIDA_SIGLA UM,
                SUM(D.QUANTIDADE) QUANTIDADE,
                SUM(IIF(D.PECA_ID > 0, 1 ,0 )) PECAS,
                SUM(IIF(D.PECA_ID = 0, 1 ,0 )) AVULSAS

            FROM
                TBKANBAN_LOTE_DETALHE D,
                TBPRODUTO P

            WHERE TRUE
            AND P.CODIGO = D.PRODUTO_ID
            /*@KANBAN_LOTE_ID*/

            GROUP BY 1,2,3,4,5,6
        ";
        
        $param = (object)[];

        if ( isset($param1->KANBAN_LOTE_ID) && $param1->KANBAN_LOTE_ID > -1 ) {
            $param->KANBAN_LOTE_ID = " = $param1->KANBAN_LOTE_ID";
        }
        
        $kanban_lote_id     = array_key_exists('KANBAN_LOTE_ID'    , $param) ? "AND D.KANBAN_LOTE_ID $param->KANBAN_LOTE_ID    " : '';
        
        $args = [
            '@KANBAN_LOTE_ID'     => $kanban_lote_id
        ];
        
        return $this->con->query($sql,$args);
    }

    public function insertKanbanLote($param) {

        $id = $this->con->gen_id('GTBKANBAN_LOTE');
        
        $sql = "
            INSERT INTO TBKANBAN_LOTE (
                ID, 
                LOCALIZACAO_ID
            ) VALUES (
                :ID,
                :LOCALIZACAO_ID
            );
        ";
        
        $args = [
            'ID'             => $id,
            'LOCALIZACAO_ID' => $param->LOCALIZACAO_ID,
        ]; 
        
        $this->con->query($sql,$args);       
        
        return $id;
    }
    
    public function insertKanbanLoteDetalhe($param) {
        
        $id = $this->con->gen_id('GTBKANBAN_LOTE_DETALHE');
        
        $sql =
        "
            INSERT INTO TBKANBAN_LOTE_DETALHE (
                ID,
                KANBAN_LOTE_ID, 
                ESTOQUE_MINIMO_ID, 
                PRODUTO_ID, 
                TAMANHO,
                QUANTIDADE,
                ESTOQUE_ID_SAIDA,
                ESTOQUE_ID_ENTRADA,
                PECA_ID
            ) VALUES (
                :KANBAN_DETALHE_ID,
                :KANBAN_LOTE_ID, 
                :ESTOQUE_MINIMO_ID, 
                :PRODUTO_ID, 
                :TAMANHO,
                :QUANTIDADE,
                :ESTOQUE_ID_SAIDA,
                :ESTOQUE_ID_ENTRADA,
                :PECA_ID
            );            
        ";
        
        $args = [
            'KANBAN_DETALHE_ID'  => $id,
            'KANBAN_LOTE_ID'     => $param->KANBAN_LOTE_ID,
            'ESTOQUE_MINIMO_ID'  => $param->ESTOQUE_MINIMO_ID,            
            'PRODUTO_ID'         => $param->PRODUTO_ID,
            'TAMANHO'            => $param->TAMANHO,      
            'QUANTIDADE'         => $param->QUANTIDADE,      
            'ESTOQUE_ID_SAIDA'   => $param->ESTOQUE_ID_SAIDA,
            'ESTOQUE_ID_ENTRADA' => $param->ESTOQUE_ID_ENTRADA,
            'PECA_ID'            => $param->PECA_ID      
        ];
                
        $this->con->query($sql,$args);
        
        return $id;
    }
    
    public function insertRemessaTalaoVinculo($param) {

        
        $id = $this->con->gen_id('GTBREMESSA_TALAO_VINCULO');
        
        $sql = "
            INSERT INTO TBREMESSA_TALAO_VINCULO (
                ID,
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
                :ID,
                'TBKANBAN_LOTE_DETALHE',
                0,
                :KANBAN_LOTE_DETALHE_ID,
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
            'ID'                     => $id,
            'KANBAN_LOTE_DETALHE_ID' => $param->KANBAN_LOTE_DETALHE_ID,
            'TIPO'                   => $param->TIPO,
            'TABELA_ID'              => $param->TABELA_ID,
            'PRODUTO_ID'             => $param->PRODUTO_ID,
            'TAMANHO'                => $param->TAMANHO,
            'QUANTIDADE'             => $param->QUANTIDADE,
            'ESTOQUE_ID_ENTRADA'     => $param->ESTOQUE_ID_ENTRADA,
            'ESTOQUE_ID_SAIDA'       => $param->ESTOQUE_ID_SAIDA,
        ]; 
        
        $this->con->query($sql,$args);       
        
        return $id;
    }
       
    public function insertTransacao($param) {
        
        $sql =
        "
            EXECUTE PROCEDURE SPI_ESTOQUE_TRANSACAO_REGRA(
                '1',
                NULL,
                NULL,
                :FAMILIA_ID,        
                :LOCALIZACAO_ID,
                'TBKANBAN_LOTE_DETALHE',
                0,
                :KANBAN_LOTE_DETALHE_ID,
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
                :KANBAN_LOTE_ID,
                NULL,
                :PECA_ID
            );
        ";
        
        $args = [
            'FAMILIA_ID'             => $param->FAMILIA_ID,
            'LOCALIZACAO_ID'         => $param->LOCALIZACAO_ID,   
            'ESTABELECIMENTO_ID'     => $param->ESTABELECIMENTO_ID,
            'PRODUTO_ID'             => $param->PRODUTO_ID,        
            'TAMANHO'                => $param->TAMANHO,           
            'QUANTIDADE'             => $param->QUANTIDADE,        
            'TIPO'                   => $param->TIPO,              
            'CONSUMO'                => $param->CONSUMO,           
            'CCUSTO'                 => $param->CCUSTO,            
            'OBSERVACAO'             => $param->OBSERVACAO,     
            'KANBAN_LOTE_ID'         => $param->KANBAN_LOTE_ID,
            'KANBAN_LOTE_DETALHE_ID' => $param->KANBAN_LOTE_DETALHE_ID,
            'PECA_ID'                => $param->PECA_ID
        ];
        
        return $this->con->query($sql,$args);
    }
     
    public function updateKanbanLote($param) {
        
        $sql = "
            UPDATE TBKANBAN_LOTE
               SET STATUS = :KANBAN_LOTE_STATUS
             WHERE ID = :KANBAN_LOTE_ID
        ";
        
        $args = [
            'KANBAN_LOTE_STATUS' => $param->KANBAN_LOTE_STATUS,
            'KANBAN_LOTE_ID'     => $param->KANBAN_LOTE_ID,
        ]; 
        
        return $this->con->query($sql,$args);       
    }
    
    public function deleteKanbanLote($param) {

        $sql = "
            DELETE
              FROM TBKANBAN_LOTE D
             WHERE D.ID = :KANBAN_LOTE_ID

        ";
        
        $args = [
            'KANBAN_LOTE_ID' => $param->KANBAN_LOTE_ID
        ]; 
        
        return $this->con->query($sql,$args);       
    }
    
    public function deleteKanbanLoteDetalhe($param) {

        $sql = "
            DELETE
              FROM TBKANBAN_LOTE_DETALHE D
             WHERE D.ID = :KANBAN_LOTE_DETALHE_ID

        ";
        
        $args = [
            'KANBAN_LOTE_DETALHE_ID' => $param->KANBAN_LOTE_DETALHE_ID
        ]; 
        
        return $this->con->query($sql,$args);       
    }
        
    
}