<?php

namespace App\Models\DTO\Financeiro;

use App\Models\DAO\Financeiro\_20110DAO;

/**
 * Objeto _20110 - Relatorio de Extrato de Caixa/Bancos
 */
class _20110
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _20110DAO::getChecList($dados);
	}

    /**
     * Consultar Bancos
     */
    public static function ConsultarBancos($filtro,$con){
        return _20110DAO::ConsultarBancos($filtro,$con) ;
    }

    /**
     * Consultar Negociados
     */
    public static function ConsultarNegociados($filtro,$con){
        return _20110DAO::ConsultarNegociados($filtro,$con);
    }

    /**
     * Consulta Provisoes
     */
    public static function ConsultaProvisoes($filtro,$con){
        return _20110DAO::ConsultaProvisoes($filtro,$con) ;
    }

    /**
     * Consultar Conta Pagar
     */
    public static function ConsultarContaPagar($filtro,$con){
        return _20110DAO::ConsultarContaPagar($filtro,$con);
    }

    /**
     * Consultar Conta Receber
     */
    public static function ConsultarContaReceber($filtro,$con){
        return _20110DAO::ConsultarContaReceber($filtro,$con);
    }

    /**
     * Consultar Ordens Compra
     */
    public static function ConsultarOrdensCompra($filtro,$con){
        return _20110DAO::ConsultarOrdensCompra($filtro,$con);
    }
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _20110DAO::listar($dados);
	}

	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarBanco($filtro,$con) {
    	return _20110DAO::ConsultarBanco($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarExtrato($filtro,$con) {
    	return _20110DAO::ConsultarExtrato($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarDetalhe($filtro,$con) {
    	return _20110DAO::ConsultarDetalhe($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _20110DAO::Consultar($filtro,$con);
    }

}