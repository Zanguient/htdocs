<?php

namespace App\Models\DAO\Vendas;

/**
 * DAO do objeto _12070 - Clientes
 */
class _12070DAO {

    /**
     * Consultar clientes.
     *
     * @return array
     */
    public static function consultarClientePorRepresentante($param, $con) {
        
        $sql = "
            SELECT
                E.CODIGO,
                E.RAZAOSOCIAL,
                E.NOMEFANTASIA,
                E.UF

            FROM
                TBEMPRESA E
                INNER JOIN TBCLIENTE C
                    ON C.CODIGO = E.CODIGO
                INNER JOIN TBREPRESENTANTE R
                    ON R.CODIGO = C.REPRESENTANTE_CODIGO

            WHERE
                C.STATUS = '1'
            AND E.STATUS = '1'
            AND E.STATUSEXCLUSAO = '1'
            AND R.STATUS = '1'
            AND R.CODIGO = :REPRESENTANTE_CODIGO
        ";

        $args = [
            ':REPRESENTANTE_CODIGO' => $param->representanteId
        ];

        return $con->query($sql, $args);
    }
	
}