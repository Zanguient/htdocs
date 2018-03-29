<?php

namespace App\Models\DAO\Custo;

/**
 * DAO do objeto _31070 - Cadastro de Incentivos
 */
class _31070DAO {
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        
        $sql = "
            SELECT  
                ID,
                DESCRICAO,
                PERCENTUAL,
                PERCENTUAL_IR
            from tbcusto_incentivo i
        ";

        return $con->query($sql);
    }

    /**
     * incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            INSERT INTO TBCUSTO_INCENTIVO (DESCRICAO, PERCENTUAL, PERCENTUAL_IR)
                       VALUES (:DESCRICAO, :PERCENTUAL, :PERCENTUAL_IR);
        ";

        $args = array(
            ':DESCRICAO'     => $param->DESCRICAO,
            ':PERCENTUAL'    => $param->PERCENTUAL,
            ':PERCENTUAL_IR' => $param->PERCENTUAL_IR
        );

        return $con->query($sql, $args);
    }

    /**
     * alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            UPDATE TBCUSTO_INCENTIVO SET 
                DESCRICAO = :DESCRICAO,
                PERCENTUAL = :PERCENTUAL,
                PERCENTUAL_IR = :PERCENTUAL_IR
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'            => $param->ID,
            ':DESCRICAO'     => $param->DESCRICAO,
            ':PERCENTUAL'    => $param->PERCENTUAL,
            ':PERCENTUAL_IR' => $param->PERCENTUAL_IR
        );

        return $con->query($sql, $args);
    }

    /**
     * excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir($param, $con) {

        $param = $param->ITEM;
        
        $sql = "DELETE FROM TBCUSTO_INCENTIVO WHERE (ID = :ID);";

        $args = array(
            ':ID' => $param->ID
        );

        return $con->query($sql, $args);
    }
	
}