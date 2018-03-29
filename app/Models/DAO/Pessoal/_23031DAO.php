<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23031 - Cadastro de tipos de fatores para avaliação de desempenho.
 */
class _23031DAO {
	
	/**
     * Consultar tipos.
     *
     * @access public
     * @param _Conexao $con
     * @return array
     */
    public static function consultarTipo($con) {
        
        $sql = "
            SELECT 
                T.ID,
                T.TITULO

            FROM
                TBAVALIACAO_DES_FATOR_TIPO T

            WHERE
                T.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);
    }
    
    /**
     * Gravar tipo.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     */
    public static function gravar($param, $con) {
        
        $sql = "
            UPDATE OR INSERT INTO TBAVALIACAO_DES_FATOR_TIPO (
                ID,
                TITULO
            )
            VALUES (
                :ID,
                :TITULO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'       => $param->ID,
            ':TITULO'   => $param->TITULO
        ];

        $con->execute($sql, $args);
    }

    /**
     * Excluir tipo.
     *
     * @access public
     * @param json $dado
     * @param _Conexao $con
     */
    public static function excluir($param, $con) {

        $sql = "
            UPDATE TBAVALIACAO_DES_FATOR_TIPO
            SET STATUSEXCLUSAO = '1'
            WHERE ID = :ID
        ";

        $args = [
            ':ID' => $param->ID
        ];

        $con->execute($sql, $args);
    }
	
}