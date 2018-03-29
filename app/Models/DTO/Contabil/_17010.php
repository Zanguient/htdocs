<?php

namespace App\Models\DTO\Contabil;

use App\Models\DAO\Contabil\_17010DAO;

/**
 * Conta Contábil
 */
class _17010
{
	private $id;
	private $descricao;
	
	/**
	 * Select da página inicial.
	 *
	 * @return array
	 */
	public static function listar() {
		return _20030DAO::listar();
	}
	
	/**
	 * Gerar id do objeto.
	 *
	 * @return integer
	 */
	public static function gerarId() {
		return _20030DAO::gerarId();
	}
	
	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param _20030 $obj
	 */
	public static function gravar(_20030 $obj) {
		_20030DAO::gravar($obj);
	}
	
	/**
	 * Retorna dados do objeto na base de dados.
	 *
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		return _20030DAO::exibir($id);
	}
	
	/**
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param _20030 $obj
	 */
	public static function alterar(_20030 $obj) {
		_20030DAO::alterar($obj);
	}
	
	/**
	 * Exclui dados do objeto na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluir($id) {
		_20030DAO::excluir($id);
	}
	
	/**
	 * Pesquisa CCusto de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCContabil($filtro = false, $analitica = false) {
		return _17010DAO::pesquisaCContabil($filtro, $analitica);
	}
	
	/**
	 * Paginação com scroll.
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
	 * @param int $pagina
	 * @return array
	 */
	public static function paginacaoScroll($qtd_por_pagina, $pagina) {
		return _20030DAO::paginacaoScroll($qtd_por_pagina, $pagina);
	}
	
	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraObj($filtro) {
		return _20030DAO::filtraObj($filtro);
	}
	
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
}

?>