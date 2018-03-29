<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11030DAO;

/**
 * Objeto 11030 - Perfil
 */
class _11030 {

	/**
    * Retorna um perfil (descricao, id, mask)
    */
	public static function getPerfil($id,$tabela) {
		return _11030DAO::getPerfil($id,$tabela);
	}

	/**
	 * Consultar perfil por tabela.
	 *
	 * @param array $param
	 * @return array
	 */
	public static function consultarPerfilPorTabela($param = []) {
		return _11030DAO::consultarPerfilPorTabela((object) $param);
	}
    
}