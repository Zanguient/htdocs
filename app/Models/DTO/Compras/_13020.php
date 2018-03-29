<?php

namespace App\Models\DTO\Compras;

use App\Models\DAO\Compras\_13020DAO;

class _13020
{
	private $requisicao_id;
	private $licitacao_id;
    private $licitacao_descricao;
    private $usuario_id;
	private $datahora;
	private $data_validade;
	private $observacao;
	private $orcamento_id;
	private $empresa_id;
	private $empresa_email;
	private $empresa_fone;
	private $empresa_contato;
	private $validade_proposta;
	private $requisicao_item_id;
	private $produto_id;
	private $produto_descricao;
	private $produto_info;
	private $tamanho;
	private $um;
	private $quantidade;
	private $valor_unitario;	
	private $operacao_codigo;
	private $operacao_ccusto;
	private $operacao_ccontabil;	
	private $produto_licitacao; //utilizado ao alterar para verificar se o produto já existe na licitação ou está sendo atualizado.
	private $empresa_excluir; //utilizado para excluir empresa
	private $produto_excluir; //utilizado para excluir produto



	public function getRequisicaoId() {
		return $this->requisicao_id;
	}
	public function setRequisicaoId($requisicao_id) {
		$this->requisicao_id[] = $requisicao_id;
	}
	public function getLicitacaoId() {
		return $this->licitacao_id;
	}
	public function setLicitacaoId($licitacao_id) {
		$this->licitacao_id = $licitacao_id;
	}
    public function getLicitacaoDescricao() {
		return $this->licitacao_descricao;
	}
	public function setLicitacaoDescricao($licitacao_descricao) {
		$this->licitacao_descricao = $licitacao_descricao;
	}
	public function getUsuarioId() {
		return $this->usuario_id;
	}
	public function setUsuarioId($usuario_id) {
		$this->usuario_id = $usuario_id;
	}
	public function getDatahora() {
		return $this->datahora;
	}
	public function setDatahora($datahora) {
		$this->datahora = $datahora;
	}
	public function getDataValidade() {
		return $this->data_validade;
	}
	public function setDataValidade($data_validade) {
		$this->data_validade = $data_validade;
	}
	public function getObservacao() {
		return $this->observacao;
	}
	public function setObservacao($observacao) {
		$this->observacao = $observacao;
	}
	public function getOrcamentoId() {
		return $this->orcamento_id;
	}
	public function setOrcamentoId($orcamento_id) {
		$this->orcamento_id[] = $orcamento_id;
	}
	public function getEmpresaId() {
		return $this->empresa_id;
	}
	public function setEmpresaId($empresa_id) {
		$this->empresa_id[] = $empresa_id;
	}
	public function getEmpresaEmail() {
		return $this->empresa_email;
	}
	public function setEmpresaEmail($empresa_email) {
		$this->empresa_email = $empresa_email;
	}
	public function getEmpresaFone() {
		return $this->empresa_fone;
	}
	public function setEmpresaFone($empresa_fone) {
		$this->empresa_fone = $empresa_fone;
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
	public function getRequisicaoItemId() {
		return $this->requisicao_item_id;
	}
	public function setRequisicaoItemId($requisicao_item_id) {
		$this->requisicao_item_id[] = $requisicao_item_id;
	}
	public function getProdutoId() {
		return $this->produto_id;
	}
	public function setProdutoId($produto_id) {
		$this->produto_id[] = $produto_id;
	}
	public function getProdutoDescricao() {
		return $this->produto_descricao;
	}
	public function setProdutoDescricao($produto_descricao) {
		$this->produto_descricao[] = $produto_descricao;
	}
	public function getProdutoInfo() {
		return $this->produto_info;
	}
	public function setProdutoInfo($produto_info) {
		$this->produto_info[] = $produto_info;
	}
	public function getTamanho() {
		return $this->tamanho;
	}
	public function setTamanho($tamanho) {
		$this->tamanho[] = $tamanho;
	}
	public function getUm() {
		return $this->um;
	}
	public function setUm($um) {
		$this->um[] = $um;
	}
	public function getQuantidade() {
		return $this->quantidade;
	}
	public function setQuantidade($quantidade) {
		$this->quantidade[] = $quantidade;
	}
	public function getValorUnitario() {
		return $this->valor_unitario;
	}
	public function setValorUnitario($valor_unitario) {
		$this->valor_unitario[] = $valor_unitario;
	}
	public function getProdutoLicitacao() {
		return $this->produto_licitacao;
	}
	public function setProdutoLicitacao($produto_licitacao) {
		$this->produto_licitacao[] = $produto_licitacao;
	}
	public function getEmpresaExcluir() {
		return $this->empresa_excluir;
	}
	public function setEmpresaExcluir($empresa_excluir) {
		$this->empresa_excluir[] = $empresa_excluir;
	}
	public function getProdutoExcluir() {
		return $this->produto_excluir;
	}
	public function setProdutoExcluir($produto_excluir) {
		$this->produto_excluir[] = $produto_excluir;
	}
	public function getOperacaoCodigo() {
		return $this->operacao_codigo;
	}

    public function setOperacaoCodigo($operacao_codigo) {
		$this->operacao_codigo[] = $operacao_codigo;
	}
	
	public function getOperacaoCcusto() {
		return $this->operacao_ccusto;
	}

    public function setOperacaoCcusto($operacao_ccusto) {
		$this->operacao_ccusto[] = $operacao_ccusto;
	}
	
	public function getOperacaoCcontabil() {
		return $this->operacao_ccontabil;
	}

    public function setOperacaoCcontabil($operacao_ccontabil) {
		$this->operacao_ccontabil[] = $operacao_ccontabil;
	}
		
	
	/**
	 * Select da página inicial.
	 *
	 * @return array
	 */
	public static function listar() {
		return _13020DAO::listar();
	}
	
	/**
	 * Gerar id do objeto.
	 *
	 * @return integer
	 */
	public static function gerarId() {
		return _13020DAO::gerarId();
	}
	
	/**
	 * Gerar id do orçamento.
	 *
	 * @return integer
	 */
	public static function gerarIdOrcamento() {
		return _13020DAO::gerarIdOrcamento();
	}
	
	
	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param _13020 $obj
	 */
	public static function gravar(_13020 $obj) {
		return _13020DAO::gravar($obj);
	}
	
	/**
	 * Retorna dados do objeto na base de dados.
	 *
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		return _13020DAO::exibir($id);
	}
	
	/**
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param _13020 $obj
	 */
	public static function alterar(_13020 $obj) {
		return _13020DAO::alterar($obj);
	}
	
	/**
	 * Exclui dados do objeto na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluir($id) {
		return _13020DAO::excluir($id);
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
		return _13020DAO::paginacaoScroll($qtd_por_pagina, $pagina);
	}
	
	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraObj($filtro) {
		return _13020DAO::filtraObj($filtro);
	}
	
	/**
	 * Lista requisições pendentes no banco de dados.
	 *
	 * @return array
	 */
	public static function listaRequisicaoPendente() {
		return _13020DAO::listaRequisicaoPendente();
	}		
	
	/**
	 * Exclui empresa do orçamento na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluirEmpresa($id) {
		_13020DAO::excluirEmpresa($id);
	}
	
	/**
	 * Exclui produto do orçamento na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluirProduto($req_id, $lic_id) {
		_13020DAO::excluirProduto($req_id, $lic_id);
	}
    
    public static function exibirOc($lic_id){
        return _13020DAO::exibirOc($lic_id);
    }
	
	/**
	 * Editar dados do fornecedor.
	 * 
	 * @param _13020 $obj
	 */
	public static function editarDadosFornec(_13020 $obj) {
		return _13020DAO::editarDadosFornec($obj);
	}

}