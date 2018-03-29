<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15040DAO;

/**
 * Objeto 15040 - Baixa de Estoque
 */
class _15040
{
	private $id;
	private $requisicao_id;
	private $saldo;
	private $localizacao_id;
	private $operacao_codigo;
	private $quantidade;
	private $usuario_id;
	private $estoque_id;
	
	public function getId() {
		return $this->id;
	}
	
	public function getRequisicaoId() {
		return $this->requisicao_id;
	}

	public function getSaldo() {
		return $this->saldo;
	}

	public function getLocalizacaoId() {
		return $this->localizacao_id;
	}

	public function getOperacaoCodigo() {
		return $this->operacao_codigo;
	}

	public function getQuantidade() {
		return $this->quantidade;
	}

	public function getUsuarioId() {
		return $this->usuario_id;
	}

	public function getEstoqueId() {
		return $this->estoque_id;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function setRequisicaoId($requisicao_id) {
		$this->requisicao_id[] = $requisicao_id;
	}

	public function setSaldo($saldo) {
		$this->saldo[] = $saldo;
	}

	public function setLocalizacaoId($localizacao_id) {
		$this->localizacao_id[] = $localizacao_id;
	}

	public function setOperacaoCodigo($operacao_codigo) {
		$this->operacao_codigo[] = $operacao_codigo;
	}

	public function setQuantidade($quantidade) {
		$this->quantidade[] = $quantidade;
	}

	public function setUsuarioId($usuario_id) {
		$this->usuario_id = $usuario_id;
	}

	public function setEstoqueId($estoque_id) {
		$this->estoque_id = $estoque_id;
	}
	
	
	/**
	 * Select da página inicial.
	 *
	 * @return array
	 */
	public static function listar() {
		return _15040DAO::listar();
	}
	
	/**
	 * Listar Requisições de Consumo.
	 *
	 * @param string $estab_perm
	 * @return array
	 */
	public static function listarRequisicao($estab_perm) {
		return _15040DAO::listarRequisicao($estab_perm);
	}
	
	/**
	 * Realiza a baixa.
	 *
	 * @param _15040 $obj
	 */
	public static function gravar(_15040 $obj) {
		return _15040DAO::gravar($obj);
	}
	
	/**
	 * Exibe dados da Baixa.
	 *
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		return _15040DAO::exibir($id);
	}
	
	/**
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param _15040 $obj
	 */
	public static function alterar(_15040 $obj) {
		return _15040DAO::alterar($obj);
	}
	
	/**
	 * Exclui dados do objeto na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluir($id) {
		return _15040DAO::excluir($id);
	}
	
	/**
	 * Paginação com scroll.
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
	 * @param int $pagina
	 * @param string $filtro
	 * @param string $estab_perm
	 * @param string $status
	 * @param string $estab
	 * @param string $data_ini
	 * @param string $data_fim
	 * @return array
	 */
	public static function paginacaoScroll($qtd_por_pagina, $pagina, $filtro, $estab_perm, $status, $estab, $data_ini, $data_fim) {
		return _15040DAO::paginacaoScroll($qtd_por_pagina, $pagina, $filtro, $estab_perm, $status, $estab, $data_ini, $data_fim);
	}
	
	/**
	 * Paginação com scroll (Baixa).
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
	 * @param int $pagina
	 * @param string $filtro
	 * @param string $estab_perm
	 * @param string $estab
	 * @param string $data_ini
	 * @param string $data_fim
	 * @return array
	 */
	public static function paginacaoScrollBaixa($qtd_por_pagina, $pagina, $filtro, $estab_perm, $estab, $data_ini, $data_fim) {
		return _15040DAO::paginacaoScrollBaixa($qtd_por_pagina, $pagina, $filtro, $estab_perm, $estab, $data_ini, $data_fim);
	}
	
	/**
     * Filtrar lista de requisições de consumo.
     * Função chamada via Ajax.
     *
     * @param string $filtro
	 * @param string $estab_perm
	 * @param string $status
	 * @param string $estab
	 * @param string $data_ini
	 * @param string $data_fim
     */
    public static function filtrar($filtro, $estab_perm, $status, $estab, $data_ini, $data_fim) {
		return _15040DAO::filtrar($filtro, $estab_perm, $status, $estab, $data_ini, $data_fim);
	}
	
	/**
     * Filtrar lista de baixas.
     * Função chamada via Ajax.
     *
     * @param string $filtro
	 * @param string $estab_perm
	 * @param string $estab
	 * @param string $data_ini
	 * @param string $data_fim
     */
    public static function filtrarBaixa($filtro, $estab_perm, $estab, $data_ini, $data_fim) {
		return _15040DAO::filtrarBaixa($filtro, $estab_perm, $estab, $data_ini, $data_fim);
	}
	
}