<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11080DAO;

/**
 * Objeto _11080 - Criar Relatorio
 */
class _11080
{

	/**
	 * Listar relatorios cadastrados
	 */
	public static function listarRelatorios($filtro) {
		return _11080DAO::listarRelatorios($filtro);
	}

	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _11080DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _11080DAO::listar($dados);
	}

	/**
	 * getRetornoSql
	 */
	public static function getRetornoSql($dados) {
		return _11080DAO::getRetornoSql($dados);
	}

	/**
	 * getRetorno
	 */
	public static function getRetorno($dados) {
		return _11080DAO::getRetorno($dados);
	}

	/**
	 * Gravar
	 */
	public static function Gravar($dados) {
		return _11080DAO::Gravar($dados);
	}

	/**
	 * show
	 */
	public static function show($id) {
		return _11080DAO::show($id);
	}

	/**
	 * show
	 */
	public static function Excluir($dados) {
		return _11080DAO::Excluir($dados);
	}

}