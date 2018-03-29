<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12100DAO;

/**
 * Objeto _12100 - NOTAS FISCAIS
 */
class _12100
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _12100DAO::getChecList($dados);
	}

	/**
     * Consultar representantes.
     *
     * @return array
     */
    public static function consultarNotas($filtro, $con) {
    	return _12100DAO::consultarNotas($filtro, $con);
    }

    /**
     * Consultar representantes.
     *
     * @return array
     */
    public static function consultarItens($filtro, $con) {
        return _12100DAO::consultarItens($filtro, $con);
    }

    /**
     * Consultar representantes.
     *
     * @return array
     */
    public static function modeloEtiqueta($filtro, $con) {
        return _12100DAO::modeloEtiqueta($filtro, $con);
    }

    /**
     * Consultar representantes.
     *
     * @return array
     */
    public static function DadosEtiqueta($nota, $con) {
        return _12100DAO::DadosEtiqueta($nota, $con);
    }
    

    /**
     * Consultar representantes.
     *
     * @return array
     */
    public static function getArquivo($nota) {
    	return _12100DAO::getArquivo($nota);
    }
	/**
     * Consultar representantes.
     *
     * @return array
     */
    public static function consultarRepresentante($filtro, $con) {
    	return _12100DAO::consultarRepresentante($filtro, $con);
    }


	/**
     * Consultar clientes.
     *
     * @return array
     */
    public static function consultarClientePorRepresentante($filtro, $con) {
    	return _12100DAO::consultarClientePorRepresentante($filtro, $con);
    }
    

	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _12100DAO::listar($dados);
	}
	
	/**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _12100DAO::Consultar($filtro,$con);
    }

}