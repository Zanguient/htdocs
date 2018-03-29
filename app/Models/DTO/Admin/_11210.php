<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11210DAO;

/**
 * Objeto _11210 - Cadastro de Perfil de Usuario
 */
class _11210
{
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        return _11210DAO::consultar($param, $con);
    }

    /**
     * Incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir($param, $con) {
        return _11210DAO::incluir($param, $con);
    }

    /**
     * Alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar($param, $con) {
        return _11210DAO::alterar($param, $con);
    }

    /**
     * Excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir($param, $con) {
        return _11210DAO::excluir($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens($param, $con) {
        return _11210DAO::consultar_itens($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_menu($param, $con) {
        return _11210DAO::consultar_menu($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_grupo($param, $con) {
        return _11210DAO::consultar_grupo($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_perfil_menu($param, $con) {
        return _11210DAO::consultar_perfil_menu($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_perfil_grupo($param, $con) {
        return _11210DAO::consultar_perfil_grupo($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function ConsultaUsuario($param, $con) {
        return _11210DAO::ConsultaUsuario($param, $con);
    }
    

    /**
     * Incluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_itens($param, $con) {
        return _11210DAO::incluir_itens($param, $con);
    }

    /**
     * Alterar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar_itens($param, $con) {
        return _11210DAO::alterar_itens($param, $con);
    }

    /**
     * Excluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_itens($param, $con) {
        return _11210DAO::excluir_itens($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_menu($param, $con) {
        return _11210DAO::consultar_menu($param, $con);
    }
    

    /**
     * Incluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_menu($param, $con) {
        return _11210DAO::incluir_menu($param, $con);
    }

    /**
     * Excluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_menu($param, $con) {
        return _11210DAO::excluir_menu($param, $con);
    }

        /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_grupo($param, $con) {
        return _11210DAO::consultar_grupo($param, $con);
    }
    

    /**
     * Incluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_grupo($param, $con) {
        return _11210DAO::incluir_grupo($param, $con);
    }

    /**
     * Excluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_grupo($param, $con) {
        return _11210DAO::excluir_grupo($param, $con);
    }
}