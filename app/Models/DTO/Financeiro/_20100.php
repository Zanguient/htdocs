<?php

namespace App\Models\DTO\Financeiro;

use App\Models\DAO\Financeiro\_20100DAO;

/**
 * Objeto _20100 - Relatorio de Extrato de Caixa/Bancos
 */
class _20100
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _20100DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _20100DAO::listar($dados);
	}

	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarBanco($filtro,$con) {
    	return _20100DAO::ConsultarBanco($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarExtrato($filtro,$con) {
    	return _20100DAO::ConsultarExtrato($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarDetalhe($filtro,$con) {
    	return _20100DAO::ConsultarDetalhe($filtro,$con);
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _20100DAO::Consultar($filtro,$con);
    }

}