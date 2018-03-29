<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _22110 - Registro de Agrupamento de Pedidos e Reposicoes
 */
class _22110DAO {

    public static function selectAgrupamento($param) {
        
        $con = new _Conexao;

        $first    = array_key_exists('FIRST'   , $param) ? "FIRST              " . $param->FIRST         : '';
        $skip     = array_key_exists('SKIP'    , $param) ? "SKIP               " . $param->SKIP          : '';
        $filtro   = array_key_exists('FILTRO'  , $param) ? "AND FILTRO LIKE  '%" . $param->FILTRO . "%'" : '';
        $order_by = array_key_exists('ORDER_BY', $param) ? "ORDER BY " . arrayToList($param->ORDER_BY) : 'ORDER BY ID DESC';

        $sql =
        "
            SELECT /*@FIRST*/ /*@SKIP*/
                ID,
                ESTABELECIMENTO_ID,
                LOCALIZACAO_ID,
                LOCALIZACAO_DESCRICAO,
                DESCRICAO,
                DATA_INICIAL,
                DATA_FINAL,
                PERFIL,
                SEMANA,
                SIMULACAO,
                STATUS,
                STATUS_DESCRICAO

            FROM
                (SELECT
                    A.ID,
                    A.ESTABELECIMENTO_ID,
                    A.LOCALIZACAO_ID,
                    (SELECT FIRST 1 L.CODIGO || ' - ' || L.DESCRICAO
                       FROM TBLOCALIZACAO L
                      WHERE L.CODIGO = A.LOCALIZACAO_ID) LOCALIZACAO_DESCRICAO,
                    A.DESCRICAO,
                    A.DATA_INICIAL,
                    A.DATA_FINAL,
                    A.PERFIL,
                    A.SEMANA,
                    A.SIMULACAO,
                    A.STATUS,
                    (CASE A.STATUS
                    WHEN '1' THEN 'ATIVO'
                    WHEN '0' THEN 'INATIVO' END)STATUS_DESCRICAO,

                    (A.ID        || ' ' ||
                     A.DESCRICAO || ' ' ||
                     (SELECT FIRST 1 L.DESCRICAO
                        FROM TBLOCALIZACAO L
                       WHERE L.CODIGO = A.LOCALIZACAO_ID)
                     )FILTRO
                FROM
                    TBAGRUPAMENTO A)X

            WHERE
                1=1
                /*@FILTRO*/

            /*@ORDER_BY*/

        ";

        $args = [
            '@FIRST'    => $first,
            '@SKIP'     => $skip,
            '@FILTRO'   => $filtro,
            '@ORDER_BY' => $order_by,
        ];

        return $con->query($sql,$args);
    }
	
}