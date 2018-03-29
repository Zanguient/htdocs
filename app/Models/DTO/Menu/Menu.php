<?php

namespace App\Models\DTO\Menu;

use App\Models\DAO\Menu\MenuDAO;

class Menu
{
	private $id;
	private $descricao;
	private $grupo;
	private $tipo;
		
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getDescricao() {
		return $this->descricao;
	}
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	public function getGrupo() {
		return $this->grupo;
	}
	public function setGrupo($grupo) {
		$this->grupo = $grupo;
	}
	public function getTipo() {
		return $this->tipo;
	}
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}
	
	/**
	 * Filtra menu de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraMenu($filtro) {
		return MenuDAO::filtraMenu($filtro);
	}	
	
	/**
	 * Filtra menu por grupo.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraMenuGrupo($filtro) {
		return MenuDAO::filtraMenuGrupo($filtro);
	}	
    
	/**
	 * Filtra menu de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function selectMenu() {
		return MenuDAO::selectMenu();
	}	
	
}