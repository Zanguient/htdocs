<?php

namespace App\Models\DTO\Pessoal;

use App\Models\DAO\Pessoal\_23010DAO;

/**
 * Objeto _23010 - Turno
 */
class _23010 {
	
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
	 * Listar Turno para cadastro.
	 * 
	 * @return array
	 */
	public static function listarSelect($param = []) {
		return _23010DAO::listarSelect(obj_case($param));
	}
}