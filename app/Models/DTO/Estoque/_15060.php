<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15060DAO;

/**
 * Objeto _15060 - Consulta de Estoque
 */
class _15060
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _15060DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _15060DAO::listar($dados);
	}
        
    /**
     * Insere remessa
     */
	public static function selectEstoqueLocalizacao($dados, $con = null) {
		return _15060DAO::selectEstoqueLocalizacao(obj_case($dados),$con);
	}
        
    /**
     * Insere remessa
     */
	public static function selectEstoqueGrade($dados, $con = null) {
		return _15060DAO::selectEstoqueGrade(obj_case($dados),$con);
	}
        
    /**
     * Insere remessa
     */
	public static function selectEstoqueTransacao($dados, $con = null) {
		return _15060DAO::selectEstoqueTransacao(obj_case($dados),$con);
	}

}