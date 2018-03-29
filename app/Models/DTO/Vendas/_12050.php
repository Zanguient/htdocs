<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12050DAO;

/**
 * Objeto _12050 - RELATORIO DE PEDIDOS X FATURAMENTO X PRODUCAO
 */
class _12050
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _12050DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _12050DAO::listar($dados);
	}
    
    /**
	 * relatorio
	 */
	public static function relatorio($dados) {
		return _12050DAO::relatorio($dados);
	}
    
    /**
	 * detalharFamilia
	 */
	public static function detalharFamilia($dados) {
		return _12050DAO::detalharFamilia($dados);
	}

	/**
	 * detalharFamilia todas
	 */
	public static function detalharFamilia2($dados) {
		return _12050DAO::detalharFamilia2($dados);
	}

	/**
	 * faturamentoDia
	 */
	public static function faturamentoDia($dados) {
		return _12050DAO::faturamentoDia($dados);
	}

	/**
	 * pedidosDia
	 */
	public static function pedidosDia($dados) {
		return _12050DAO::pedidosDia($dados);
	}

	/**
	 * devolucaoDia
	 */
	public static function devolucaoDia($dados) {
		return _12050DAO::devolucaoDia($dados);
	}

	/**
	 * defeitoDia
	 */
	public static function defeitoDia($dados) {
		return _12050DAO::defeitoDia($dados);
	}

	/**
	 * defeitoDia2
	 */
	public static function defeitoDia2($dados) {
		return _12050DAO::defeitoDia2($dados);
	}

	/**
	 * producaoDia
	 */
	public static function producaoDia($dados) {
		return _12050DAO::producaoDia($dados);
	}

	/**
	 * producaoDia
	 */
	public static function producaoDia2($dados) {
		return _12050DAO::producaoDia2($dados);
	}

}