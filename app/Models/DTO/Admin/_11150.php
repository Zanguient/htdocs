<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11150DAO;

/**
 * Objeto _11150 - Registro de Casos
 */
class _11150
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11150DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11150DAO::listar($dados);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
        return _11150DAO::Consultar($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param int $painel_id
     * @param int $id
     * @param Conection $con
     * @return array
     */
    public static function usuario($painel_id,$id,$con) {
        return _11150DAO::usuario($painel_id,$id,$con);
    }

    /**
     * Consultar
     * @access public
     * @param int $painel_id
     * @param int $id
     * @param Conection $con
     * @return array
     */
    public static function usuario_parametros($painel_id,$id,$con) {
        return _11150DAO::usuario_parametros($painel_id,$id,$con);
    }

    /**
     * Consultar Historico de um caso
     * @access public
     * @param Integer ID
     * @param Conection $con
     * @return array
     */
    public static function historico($painel_id, $caso_id, $con) {
        return _11150DAO::historico($painel_id,$caso_id,$con);
    }

    /**
     * Consultar Casos de um painel
     * @access public
     * @param int $painel_id
     * @param Conection $con
     * @return array
     */
    public static function getCasos($painel_id, $status, $filtro,$con) {
        return _11150DAO::getCasos($painel_id, $status, $filtro,$con);
    }

    /**
     * Consultar visialização de um painel
     * @access public
     * @param int painel_id
     * @param Conection $con
     * @return array
     */
    public static function getVisializacao($painel_id,$con){
        return _11150DAO::getVisializacao($painel_id,$con);
    }

    /**
     * Consultar Casos de um painel
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function excluirCaso($painel_id,$caso_id,$con) {
        return _11150DAO::excluirCaso($painel_id,$caso_id,$con);
    }

    /**
     * Consultar feed de Casos de um painel
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function consultarFeed($painel_id,$caso_id,$comentario,$con) {
        return _11150DAO::consultarFeed($painel_id,$caso_id,$comentario,$con);
    }

    /**
     * Gravar feed de um Casos
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function gravarFeed($dados,$con) {
        return _11150DAO::gravarFeed($dados,$con);
    }

    /**
     * Excluir feed de um Casos
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function excluirFeed($dados,$con) {
        return _11150DAO::excluirFeed($dados,$con);
    }

    /**
     * gostei do feed de um Casos
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function gostei($dados,$con) {
        return _11150DAO::gostei($dados,$con);
    }

    /**
     * Envolvidos em um caso
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function getEnvolvidos($dados,$con) {
        return _11150DAO::getEnvolvidos($dados,$con);
    }
    public static function rmvEnvolvidos($dados,$con) {
        return _11150DAO::rmvEnvolvidos($dados,$con);
    }
    public static function grvEnvolvidos($dados,$con) {
        return _11150DAO::grvEnvolvidos($dados,$con);
    }
    public static function listEnvolvidos($dados,$con) {
        return _11150DAO::listEnvolvidos($dados,$con);
    }


      


    /**
     * Consultar Status Caso
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Status($param,$con) {
        return _11150DAO::Status($param,$con);
    }

    /**
     * Consultar Sql
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function getSql($id,$con) {
        return _11150DAO::getSql($id,$con);
    }

    /**
     * Consultar um registro de caso
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function casoRegistro($painel_id,$caso_id,$con) {
        return _11150DAO::casoRegistro($painel_id,$caso_id,$con);
    }

    /**
     * Consultar um registro de caso
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function casoDetalhe($painel_id,$caso_id,$con) {
        return _11150DAO::casoDetalhe($painel_id,$caso_id,$con);
    }

    /**
     * Consultar um registro de caso
     * @access public
     * @param int $painel_id
     * @param int $caso_id
     * @param Conection $con
     * @return array
     */
    public static function casoItens($painel_id,$caso_id,$con) {
        return _11150DAO::casoItens($painel_id,$caso_id,$con);
    }

    /**
     * Consultar Painel de Casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function getPainelCaso($painel_id,$con) {
        return _11150DAO::getPainelCaso($painel_id,$con);
    }

    /**
     * Consultar Configuraçao dos Painel de Casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function confPainel($painel_id,$con) {
        return _11150DAO::confPainel($painel_id,$con);
    }

    /**
     * Consultar Configuraçao dos Painel de Casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function confPainelItens($painel_id,$con) {
        return _11150DAO::confPainelItens($painel_id,$con);
    }

    /**
     * Consultar vinculos dos campos de um Painel de Casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function vinculos($painel_id,$con) {
        return _11150DAO::vinculos($painel_id,$con);
    }

    /**
     * Consultar vinculos dos campos de um Painel de Casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function itensVinculo($painel_id,$con) {
        return _11150DAO::itensVinculo($painel_id,$con);
    }

    /**
     * Consultar regras de validacao de um Painel de Casos
     * @access public
     * @param Int painel_id
     * @param Conection $con
     * @return array
     */
    public static function validacao($painel_id,$con) {
        return _11150DAO::validacao($painel_id,$con);
    }

    /**
     * Consultar Painel de Casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function paineisCasos($con) {
        return _11150DAO::paineisCasos($con);
    }

    /**
     * Consultar Painel de Casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function gravarContato($con,$itens,$painel_id) {
        return _11150DAO::gravarContato($con,$itens,$painel_id);
    }

    /**
     * Gravar registro de casos
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function gravarCaso($con,$itens,$campos,$painel_id,$caso_id ) {
        return _11150DAO::gravarCaso($con,$itens,$campos,$painel_id,$caso_id );
    }

    /**
     * Consultar Motivos de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function Motivos($param,$con) {
        return _11150DAO::Motivos($param,$con);
    }

    /**
     * Consultar Configurações de contatos de um painel de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function confContato($param,$con) {
        return _11150DAO::confContato($param,$con);
    }

    /**
     * finalizar um caso casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function finalizar($param,$con) {
        return _11150DAO::finalizar($param,$con);
    }

    /**
     * Consultar itens dos campos da Configurações de contatos de um painel de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function confItens($param,$con) {
        return _11150DAO::confItens($param,$con);
    }

    /**
     * Consultar agrupamento dos campos de um painel de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function camposAgrupamentos($param,$con) {
        return _11150DAO::camposAgrupamentos($param,$con);
    }

    /**
     * Consultar agrupamento dos campos de um painel de casos
     * @access public
     * @param Integer $painel_id
     * @param Conection $con
     * @return array
     */
    public static function camposAgrupamentos2($painel_id,$con) {
        return _11150DAO::camposAgrupamentos2($painel_id,$con);
    }

    /**
     * Consultar Responsavel de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function Responsavel($param,$con) {
        return _11150DAO::Responsavel($param,$con);
    }

    /**
     * Consultar Responsavel de um painel
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function getResponsavel($painel_id, $caso_id, $con) {
        return _11150DAO::getResponsavel($painel_id, $caso_id, $con);
    }

    /**
     * Consultar Responsavel de um painel
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function getUserNotification($painel_id, $caso_id, $con) {
        return _11150DAO::getUserNotification($painel_id, $caso_id, $con);
    }

    /**
     * Consultar Responsavel de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function Contatos($param,$con) {
        return _11150DAO::Contatos($param,$con);
    }

    /**
     * Consultar Tipos de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function Tipos($param,$con) {
    	return _11150DAO::Tipos($param,$con);
    }

    /**
     * Consultar Origens de casos
     * @access public
     * @param {} $param
     * @param Conection $con
     * @return array
     */
    public static function Origens($param,$con) {
    	return _11150DAO::Origens($param,$con);
    }

    

}