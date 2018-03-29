<?php

namespace App\Models\DTO\Produto;

use App\Models\DAO\Produto\_27030DAO;

/**
 * Objeto _27030 - Cadastro de Cores
 */
class _27030
{
	private $id;
	private $descricao;
	private $tonalidade;
	private $status;
	private $modelo_id;
	private $retorna_todos;
	private $filtro;
	private $cliente_id;
	
	function getId() {
		return $this->id;
	}

	function getDescricao() {
		return $this->descricao;
	}

	function getTonalidade() {
		return $this->tonalidade;
	}

	function getStatus() {
		return $this->status;
	}

	function getModeloId() {
		return $this->modelo_id;
	}

	function getRetornaTodos() {
		return $this->retorna_todos;
	}
	
	function getFiltro() {
		return $this->filtro;
	}

	function getClienteId() {
		return $this->cliente_id;
	}

	function setId($id) {
		$this->id = $id;
	}

	function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	function setTonalidade($tonalidade) {
		$this->tonalidade = $tonalidade;
	}

	function setStatus($status) {
		$this->status = $status;
	}

	function setModeloId($modelo_id) {
		$this->modelo_id = $modelo_id;
	}

	function setRetornaTodos($retorna_todos) {
		$this->retorna_todos = $retorna_todos;
	}
	
	function setFiltro($filtro) {
		$this->filtro = $filtro;
	}

	function setClienteId($cliente_id) {
		$this->cliente_id = $cliente_id;
	}
	
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _27030DAO::listar($dados);
	}
	
	/**
	 * Consultar cores.
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarCor($con) {
		return _27030DAO::consultarCor($con);
	}

	/**
	 * Consultar cores por modelo ou todas.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarCorPorModelo($param, $con) {
		return _27030DAO::consultarCorPorModelo($param, $con);
	}

}