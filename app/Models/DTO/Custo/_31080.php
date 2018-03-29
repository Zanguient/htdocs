<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31080DAO;

/**
 * Objeto _31080 - Cadastro de Mercados
 */
class _31080
{
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        return _31080DAO::consultar($param, $con);
    }

    /**
     * Consultar Familia.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarFamilia($param, $con) {
        return _31080DAO::consultarFamilia($param, $con);
    }

    /**
     * Consultar Familia.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarConta($param, $con) {
        return _31080DAO::consultarConta($param, $con);
    }

    /**
     * Incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir($param, $con) {
        return _31080DAO::incluir($param, $con);
    }

    /**
     * Alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar($param, $con) {
        return _31080DAO::alterar($param, $con);
    }

    /**
     * Excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir($param, $con) {
        return _31080DAO::excluir($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens($param, $con) {
        return _31080DAO::consultar_itens($param, $con);
    }

    /**
     * Incluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_itens($param, $con) {
        return _31080DAO::incluir_itens($param, $con);
    }

    /**
     * Alterar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar_itens($param, $con) {
        return _31080DAO::alterar_itens($param, $con);
    }

    /**
     * Excluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_itens($param, $con) {
        return _31080DAO::excluir_itens($param, $con);
    }

    /**
     * Consultar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens_conta($param, $con) {
        return _31080DAO::consultar_itens_conta($param, $con);
    }

    /**
     * Incluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_itens_conta($param, $con) {
        return _31080DAO::incluir_itens_conta($param, $con);
    }

    /**
     * Alterar itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar_itens_conta($param, $con) {
        return _31080DAO::alterar_itens_conta($param, $con);
    }

    /**
     * Excluir itens.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_itens_conta($param, $con) {
        return _31080DAO::excluir_itens_conta($param, $con);
    }

}