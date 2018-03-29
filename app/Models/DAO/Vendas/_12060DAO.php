<?php

namespace App\Models\DAO\Vendas;

/**
 * DAO do objeto _12060 - Representante
 */
class _12060DAO {

	/**
     * Consultar representantes.
     *
     * @return array
     */
    public static function consultarRepresentante($con) {
        
       $sql = "
            SELECT
                R.CODIGO,
                R.RAZAOSOCIAL,
                R.UF
            FROM
                TBREPRESENTANTE R
            WHERE
                R.STATUS = '1'
        ";

        return $con->query($sql);
    }
	
}