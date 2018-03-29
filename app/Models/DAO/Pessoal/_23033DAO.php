<?php

namespace App\Models\DAO\Pessoal;

/**
 * DAO do objeto _23033 - Cadastro de formação do avaliado para avaliação de desempenho.
 */
class _23033DAO {
	
	/**
     * Consultar formação.
     *
     * @access public
     * @param _Conexao $con
     * @return array
     */
    public static function consultarFormacao($con) {
        
        $sql = "
            SELECT 
                F.ID,
                F.DESCRICAO,
                F.PONTO

            FROM
                TBAVALIACAO_DES_FORMACAO F

            WHERE
                F.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);
    }
    
    /**
     * Gravar formação.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     */
    public static function gravar($param, $con) {
        
        $sql = "
            UPDATE OR INSERT INTO TBAVALIACAO_DES_FORMACAO (
                ID,
                DESCRICAO,
                PONTO
            )
            VALUES (
                :ID,
                :DESCRICAO,
                :PONTO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'        => $param->ID,
            ':DESCRICAO' => $param->DESCRICAO,
            ':PONTO'     => $param->PONTO
        ];

        $con->execute($sql, $args);
    }

    /**
     * Excluir formação.
     *
     * @access public
     * @param json $dado
     * @param _Conexao $con
     */
    public static function excluir($param, $con) {

        $sql = "
            UPDATE TBAVALIACAO_DES_FORMACAO
            SET STATUSEXCLUSAO = '1'
            WHERE ID = :ID
        ";

        $args = [
            ':ID' => $param->ID
        ];

        $con->execute($sql, $args);
    }
	
}