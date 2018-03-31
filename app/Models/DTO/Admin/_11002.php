<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11002DAO;

/**
 * Objeto _11002 - Usuarios
 */
class _11002
{
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        return _11002DAO::consultar($param, $con);
    }

    /**
     * Incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir($param, $con) {
        return _11002DAO::incluir($param, $con);
    }

    /**
     * Incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function resetarSenhaSuper($param, $con) {
        return _11002DAO::resetarSenhaSuper($param, $con);
    }

    /**
     * Incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function atualizarMenusUser($param, $con) {
        return _11002DAO::atualizarMenusUser($param, $con);
    }



    /**
     * Alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar($param, $con) {
        return _11002DAO::alterar($param, $con);
    }

    /**
     * Excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir($param, $con) {
        return _11002DAO::excluir($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens($param, $con) {
        return _11002DAO::consultar_itens($param, $con);
    }

    /**
     * Incluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_itens($param, $con) {
        return _11002DAO::incluir_itens($param, $con);
    }

    /**
     * Alterar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar_itens($param, $con) {
        return _11002DAO::alterar_itens($param, $con);
    }

    /**
     * Excluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_itens($param, $con) {
        return _11002DAO::excluir_itens($param, $con);
    }
}