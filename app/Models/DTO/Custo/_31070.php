<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31070DAO;

/**
 * Objeto _31070 - Cadastro de Incentivos
 */
class _31070
{
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        return _31070DAO::consultar($param, $con);
    }

    /**
     * Incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir($param, $con) {
        return _31070DAO::incluir($param, $con);
    }

    /**
     * Alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar($param, $con) {
        return _31070DAO::alterar($param, $con);
    }

    /**
     * Excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir($param, $con) {
        return _31070DAO::excluir($param, $con);
    }

}