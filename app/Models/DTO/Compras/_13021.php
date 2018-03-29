<?php

namespace App\Models\DTO\Compras;

use App\Models\DAO\Compras\_13021DAO;

class _13021
{
	private $orcamento_id;
	private $empresa_id;
	private $empresa_descricao;
	private $empresa_contato;
	private $validade_proposta;
	private $prazo_entrega;
	private $frete;
	private $frete_valor;
	private $pag_forma;
	private $pag_condicao;
	private $produto_id;
	private $produto_descricao;
	private $quantidade;
	private $valor_unitario;
	private $percentual_ipi;
	private $observacao;
	private $vinculo;
	private $tabela;
	private $arquivo_excluir; //arquivos que devem ser excluídos
	private $obs_produto;
	private $status_resposta;


	public function getOrcamentoId() {
		return $this->orcamento_id;
	}
	public function setOrcamentoId($orcamento_id) {
		$this->orcamento_id = $orcamento_id;
	}
	public function getEmpresaId() {
		return $this->empresa_id;
	}
	public function setEmpresaId($empresa_id) {
		$this->empresa_id = $empresa_id;
	}
	public function getEmpresaDescricao() {
		return $this->empresa_descricao;
	}
	public function setEmpresaDescricao($empresa_descricao) {
		$this->empresa_descricao = $empresa_descricao;
	}
	public function getEmpresaContato() {
		return $this->empresa_contato;
	}
	public function setEmpresaContato($empresa_contato) {
		$this->empresa_contato = $empresa_contato;
	}
	public function getValidadeProposta() {
		return $this->validade_proposta;
	}
	public function setValidadeProposta($validade_proposta) {
		$this->validade_proposta = $validade_proposta;
	}
	public function getPrazoEntrega() {
		return $this->prazo_entrega;
	}
	public function setPrazoEntrega($prazo_entrega) {
		$this->prazo_entrega = $prazo_entrega;
	}
	public function getFrete() {
		return $this->frete;
	}
	public function setFrete($frete) {
		$this->frete = $frete;
	}
	public function getFreteValor() {
		return $this->frete_valor;
	}
	public function setFreteValor($frete_valor) {
		$this->frete_valor = $frete_valor;
	}
	public function getPagForma() {
		return $this->pag_forma;
	}
	public function setPagForma($pag_forma) {
		$this->pag_forma = $pag_forma;
	}
	public function getPagCondicao() {
		return $this->pag_condicao;
	}
	public function setPagCondicao($pag_condicao) {
		$this->pag_condicao = $pag_condicao;
	}
	public function getProdutoId() {
		return $this->produto_id;
	}
	public function setProdutoId($produto_id) {
		$this->produto_id[] = $produto_id;
	}
	public function getProdutoDescricao() {
		return $this>produto_descricao;
	}
	public function setProdutoDescricao($produto_descricao) {
		$this->produto_descricao = $produto_descricao;
	}
	public function getQuantidade() {
		return $this->quantidade;
	}
	public function setQuantidade($quantidade) {
		$this->quantidade = $quantidade;
	}
	public function getValorUnitario() {
		return $this->valor_unitario;
	}
	public function setValorUnitario($valor_unitario) {
		$this->valor_unitario[] = $valor_unitario;
	}
	public function getPercentualIpi() {
		return $this->percentual_ipi;
	}
	public function setPercentualIpi($percentual_ipi) {
		$this->percentual_ipi[] = $percentual_ipi;
	}
	public function getObservacao() {
		return $this->observacao;
	}
	public function setObservacao($observacao) {
		$this->observacao = $observacao;
	}	
	public function getVinculo() {
		return $this->vinculo;
	}
	public function setVinculo($vinculo) {
		$this->vinculo = $vinculo;
	}
	public function getTabela() {
		return $this->tabela;
	}
	public function setTabela($tabela) {
		$this->tabela = $tabela;
	}
	public function getArquivoExcluir() {
		return $this->arquivo_excluir;
	}
	public function setArquivoExcluir($arquivo_excluir) {
		$this->arquivo_excluir[] = $arquivo_excluir;
	}
	public function getObsProduto() {
		return $this->obs_produto;
	}
	public function setObsProduto($obs_produto) {
		$this->obs_produto[] = $obs_produto;
	}
	public function getStatusResposta() {
		return $this->status_resposta;
	}
	public function setStatusResposta($status_resposta) {
		$this->status_resposta = $status_resposta;
	}
	
	/**
	 * Altera dados do objeto na base de dados.
	 * 
	 * @param _13021 $obj
	 */
	public static function alterar(_13021 $obj) {
		return _13021DAO::alterar($obj);
	}
	
	/**
	 * Altera produto na base de dados.
	 * 
	 * @param _13021 $obj
	 */
	public static function alterarProduto(_13021 $obj) {
		_13021DAO::alterarProduto($obj);
	}
	
	/**
	 * Verifica a validade da licitação
	 *
	 * @param integer Id do Orçamento
	 * @return array
	 */
	public static function verifValLicit($orc_id) {
		return _13021DAO::verifValLicit($orc_id);
	}
	
	/**
	 * Exibe orçamento.
	 *
	 * @param int $orc_id
	 * @return array
	 */
	public static function exibirOrcamento($orc_id) {
		return _13021DAO::exibirOrcamento($orc_id);
	}	
	
}

?>