<?php

namespace App\Models\DTO\#Grupos#;

use App\Models\DAO\#Grupos#\#TelaNO#DAO;

/**
 * Objeto #TelaNO# - #Titulo#
 */
class #TelaNO#
{
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        return #TelaNO#DAO::consultar($param, $con);
    }

    /**
     * Incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir($param, $con) {
        return #TelaNO#DAO::incluir($param, $con);
    }

    /**
     * Alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar($param, $con) {
        return #TelaNO#DAO::alterar($param, $con);
    }

    /**
     * Excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir($param, $con) {
        return #TelaNO#DAO::excluir($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens($param, $con) {
        return #TelaNO#DAO::consultar_itens($param, $con);
    }

    /**
     * Incluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_itens($param, $con) {
        return #TelaNO#DAO::incluir_itens($param, $con);
    }

    /**
     * Alterar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar_itens($param, $con) {
        return #TelaNO#DAO::alterar_itens($param, $con);
    }

    /**
     * Excluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_itens($param, $con) {
        return #TelaNO#DAO::excluir_itens($param, $con);
    }
}