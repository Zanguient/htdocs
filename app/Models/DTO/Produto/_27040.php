<?php

namespace App\Models\DTO\Produto;

use App\Models\DAO\Produto\_27040DAO;

/**
 * Objeto 27040 - Grade
 */
class _27040 {
	
	/**
	 * Listar tamanhos do produto.
	 * 
	 * @param integer $id_prod
	 * @return array
	 */
	public static function listarTamanho($id_prod) {
		return _27040DAO::listarTamanho($id_prod);
	}
}
