<?php

namespace App\Models\DAO\#Grupos#;

/**
 * DAO do objeto #TelaNO# - #Titulo#
 */
class #TelaNO#DAO {
	
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

                *

            from #TABELA_PAI# p
        ";

        return $con->query($sql);
    }

    /**
     * Consultar Itens
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens($param, $con) {
        
        $sql = "
            SELECT
                
                *

            from #TABELA_FILHA# i
            where i.#TABELA_PAI#_id = :#TABELA_PAI#_ID
        ";

        $args = array(
            ':#TABELA_PAI#_ID'     => $param->#TABELA_PAI#_ID
        );

        return $con->query($sql, $args);
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
            INSERT INTO #TABELA_PAI# (DESCRICAO)
                    VALUES (:DESCRICAO);
        ";

        $args = array(
            ':DESCRICAO'      => $param->DESCRICAO
        );

        return $con->query($sql, $args);
    }

    /**
     * incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_itens($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            INSERT INTO #TABELA_FILHA# (#TABELA_PAI#_ID, DESCRICAO)
                         VALUES (:#TABELA_PAI#_ID, :DESCRICAO);
        ";

        $args = array(
            ':DESCRICAO'   => $param->DESCRICAO,
            ':#TABELA_PAI#_ID' => $param->#TABELA_PAI#_ID
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
            UPDATE #TABELA_PAI# SET
                DESCRICAO      = :DESCRICAO
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'             => $param->ID,
            ':DESCRICAO'      => $param->DESCRICAO
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
    public static function alterar_itens($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            UPDATE #TABELA_FILHA# SET 
                DESCRICAO   = :DESCRICAO
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'         => $param->ID,
            ':DESCRICAO'  => $param->DESCRICAO
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
        
        $sql = "DELETE FROM #TABELA_PAI# WHERE (ID = :ID);";

        $args = array(
            ':ID' => $param->ID
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
    public static function excluir_itens($param, $con) {

        $param = $param->ITEM;
        
        $sql = "DELETE FROM #TABELA_FILHA# WHERE (ID = :ID);";

        $args = array(
            ':ID' => $param->ID
        );

        return $con->query($sql, $args);
    }
	
}