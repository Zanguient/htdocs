<?php

namespace App\Models\DAO\Chamados;

use App\Models\DTO\Chamados\_26010;
use App\Models\Conexao\_Conexao;
use App\Helpers\Helpers;

class _26010DAO
{	
    public static function listar($param)
    {
        return (object) [
            'CHAMADOS' => _26010Select::chamados($param)
        ];
    }
}

class _26010Select
{
    public static function chamados($param = [], _Conexao $con = null) {
        
        $con = $con ? $con : new _Conexao;
        
        $id             = isset($param->ID)             ? "AND ID             IN (    " . Helpers::arrayToList($param->ID            , 999) . ")" : '';
        $destino_ccusto = isset($param->DESTINO_CCUSTO) ? "AND DESTINO_CCUSTO IN (    " . Helpers::arrayToList($param->DESTINO_CCUSTO, 999) . ")" : '';
        $status         = isset($param->STATUS        ) ? "AND STATUS_ID      IN (    " . Helpers::arrayToList($param->STATUS        , 999) . ")" : '';
        $status_entre   = isset($param->STATUS_ENTRE  ) ? "AND STATUS_ID      BETWEEN " . $param->STATUS_ENTRE[0] . " AND " . $param->STATUS_ENTRE[1] : '';
                       
        $sql = "
            SELECT
                X.*,
                IIF(DATAHORA_PREVISAO    > CAST('01.01.1900 07:00:00' AS TIMESTAMP), (SELECT TEMPO_EXTENSO FROM SP_DATA_HORA_CALC (COALESCE(DATAHORA_ENCERRADO, CURRENT_TIMESTAMP),    DATAHORA_PREVISAO,  'SEG')), NULL) SLA_SOLUCAO_EXTENSO,
                IIF(DATAHORA_PREVISAO    > CAST('01.01.1900 07:00:00' AS TIMESTAMP), (SELECT TEMPO_INTEIRO FROM SP_DATA_HORA_CALC (COALESCE(DATAHORA_ENCERRADO, CURRENT_TIMESTAMP),    DATAHORA_PREVISAO,  'DIA')), NULL) SLA_SOLUCAO,
                (SELECT TEMPO_EXTENSO FROM SP_DATA_HORA_CALC (DATAHORA_CHAMADO, COALESCE(DATAHORA_SOLUCAO, CURRENT_TIMESTAMP), 'SEG')) DOWNTIME_EXTENSO,
                (SELECT TEMPO_INTEIRO FROM SP_DATA_HORA_CALC (DATAHORA_CHAMADO, COALESCE(DATAHORA_SOLUCAO, CURRENT_TIMESTAMP), 'DIA')) DOWNTIME
            FROM(
                SELECT
                        LPAD(E.ID, 5, '0') ID,

                        E.STATUS STATUS_ID,
                        
                        S.SEQUENCIA STATUS_SEQUENCIA,

                        S.DESCRICAO STATUS_DESCRICAO,
                        
                        S.RGB STATUS_RGB,

                        E.PRIORIDADE,

                        (CASE E.PRIORIDADE WHEN 0 THEN 'CRÍTICA' WHEN 1 THEN 'ALTA' WHEN 2 THEN 'MÉDIA' WHEN 3 THEN 'BAIXA' END)PRIORIDADE_DESCRICAO,

                        CC.CODIGO ORIGEM_CCUSTO,

                        CAST(CC.DESCRICAO AS VARCHAR(255)) ORIGEM_CCUSTO_DESCRICAO,

                        E.CONTATO_ORIGEM ORIGEM_CONTATO,    

                        E.TURNO ORIGEM_TURNO,

                        E.RAMAL ORIGEM_RAMAL,

                        E.EMAIL ORIGEM_EMAIL,

                        E.CCUSTO_DESTINO DESTINO_CCUSTO,

                        T.ID DESTINO_SETOR_ID,
                        
                        T.DESCRICAO DESTINO_SETOR_DESCRICAO,

                        UPPER(E.DESCRICAO_RESUMO) DESCRICAO_RESUMIDA,

                        UPPER(CAST(SUBSTRING(E.DESCRICAO_DETALHE FROM 1 FOR 8191) AS VARCHAR(8191))) DESCRICAO_DETALHADA,

                        E.DATAHORA_REGISTRO DATAHORA_ABERTURA,

                        E.DATAHORA_CHAMADO DATAHORA_CHAMADO,

                        E.DATAHORA_ATENDIDO,

                        COALESCE(E.PREVISAO, C.TEMPO_SOLUCAO + E.DATAHORA_ATENDIDO) DATAHORA_PREVISAO,

                        E.DATAHORA_FINALIZADO DATAHORA_ENCERRADO,

                        E.DATAHORA_SOLUCAO,

                        (SELECT FIRST 1 D.DATAHORA_REGISTRO
                        FROM TBCHAMADO_DETALHE D
                        WHERE D.CHAMADO_ID = E.ID
                        ORDER BY D.DATAHORA_REGISTRO DESC)
                        DATAHORA_ULTIMO_EVENTO,

                        (SELECT IIF(COUNT(STATUS)=0,0,IIF(AVG(STATUS)=100,1,2)) FROM TBCHAMADO WHERE SUB_CHAMADO_ID = E.ID)SUBCHAMADOS,

                        E.SUB_CHAMADO_ID CHAMADO_PAI,

                        E.ETIQUETA,

                        (SELECT U.NOME FROM TBUSUARIO U WHERE U.CODIGO = E.ABERTO_POR) ABERTO_POR,

                        (SELECT U.NOME FROM TBUSUARIO U WHERE  U.CODIGO = E.ATENDIDO_POR)ATENDIDO_POR,

                        (SELECT FIRST 1  COALESCE((SELECT U.NOME FROM TBUSUARIO U WHERE  U.CODIGO = D.CONTATO_DESTINO_ID) , D.CONTATO_DESTINO)
                        FROM TBCHAMADO_DETALHE D
                        WHERE D.CHAMADO_ID = E.ID
                        ORDER BY D.DATAHORA_REGISTRO DESC)
                        ULTIMO_EVENTO_POR,
                        
                        C.ID CATEGORIA_ID,
                        C.DESCRICAO CATEGORIA_DESCRICAO

                FROM
                        TBCHAMADO E,
                        TBCHAMADO_STATUS S,
                        TBCHAMADO_CATEGORIA C,
                        VWCENTRO_DE_CUSTO CC,
                        TBCCUSTO_SETOR T

                WHERE
                        S.ID        =   E.STATUS
                AND     C.ID        =   E.CATEGORIA_ID
                AND     CC.CODIGO   =   E.CCUSTO_ORIGEM
                AND     T.ID        =   E.SETOR_DESTINO_ID
            )X

            WHERE
                1=1
            /*@ID*/
            /*@DESTINO_CCUSTO*/
            /*@STATUS*/
            /*@STATUS_ENTRE*/
        ";
        
        $args = [
            '@ID'             => $id,
            '@STATUS_ENTRE'   => $status_entre,
            '@STATUS'         => $status,
            '@DESTINO_CCUSTO' => $destino_ccusto
        ];
        
        return $con->query($sql, $args);
    }
}