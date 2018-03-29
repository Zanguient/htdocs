<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15020DAO;

/**
 * Objeto 15020 - Localização
 */
class _15020 {
	
	private $id;
	private $descricao;
	
	public function getId() {
		return $this->id;
	}

	public function getDescricao() {
		return $this->descricao;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Listar Localização para cadastro.
	 * 
	 * @return array
	 */
	public static function listarSelect() {
		return _15020DAO::listarSelect();
	}
	
}

?>