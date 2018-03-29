<?php

namespace App\Models\DTO\Compras;

use App\Models\DAO\Compras\_13010DAO;

class _13010
{
	private $id;
	private $estabelecimento_id;
	private $ccusto;
    private $editavel;
	private $vinculo;
	private $licitacao_id;
	private $usuario_gestor_id;
	private $usuario_gestor_email;
	private $usuario_id;
	private $urgencia;
	private $necessita_licitacao;
	private $empresa_id;
	private $empresa_descricao;
	private $empresa_fone;
	private $empresa_email;
	private $empresa_contato;
	private $data;
	private $data_utilizacao;
	private $req_item_id;
	private $oc;
	private $produto_id;
	private $produto_descricao;
	private $um;
	private $tamanho;
	private $quantidade;
	private $valor_unitario;
    private $arquivo_id;
	private $descricao;
	private $observacao_item;
	private $operacao_codigo;
	private $operacao_ccusto;
	private $operacao_ccontabil;

	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getEstabelecimentoId() {
		return $this->estabelecimento_id;
	}
	public function setEstabelecimentoId($estabelecimento_id) {
		$this->estabelecimento_id = $estabelecimento_id;
	}
	public function getCcusto() {
		return $this->ccusto;
	}
	public function setCcusto($ccusto) {
		$this->ccusto = $ccusto;
	}
	public function setEditavel($editavel) {
		$this->editavel = $editavel;
	}

    public function getEditavel() {
		return $this->editavel;
	}
	
	public function getVinculo() {
		return $this->vinculo;
	}
	
	public function setVinculo($v) {
		$this->vinculo = $v;
	}
	
	public function getLicitacaoId() {
		return $this->licitacao_id;
	}
	public function setLicitacaoId($licitacao_id) {
		$this->licitacao_id = $licitacao_id;
	}
	public function getUsuarioGestorId() {
		return $this->usuario_gestor_id;
	}
	public function setUsuarioGestorId($usuario_gestor_id) {
		$this->usuario_gestor_id = $usuario_gestor_id;
	}
	public function getUsuarioGestorEmail() {
		return $this->usuario_gestor_email;
	}
	public function setUsuarioGestorEmail($usuario_gestor_email) {
		$this->usuario_gestor_email = $usuario_gestor_email;
	}
	public function getUsuarioId() {
		return $this->usuario_id;
	}
	public function setUsuarioId($usuario_id) {
		$this->usuario_id = $usuario_id;
	}
	public function getUrgencia() {
		return $this->urgencia;
	}
	public function setUrgencia($urgencia) {
		$this->urgencia = $urgencia;
	}
	public function getNecessitaLicitacao() {
		return $this->necessita_licitacao;
	}
	public function setNecessitaLicitacao($necessita_licitacao) {
		$this->necessita_licitacao = $necessita_licitacao;
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
	public function getEmpresaFone() {
		return $this->empresa_fone;
	}
	public function setEmpresaFone($empresa_fone) {
		$this->empresa_fone = $empresa_fone;
	}
	public function getEmpresaEmail() {
		return $this->empresa_email;
	}
	public function setEmpresaEmail($empresa_email) {
		$this->empresa_email = $empresa_email;
	}
	public function getEmpresaContato() {
		return $this->empresa_contato;
	}
	public function setEmpresaContato($empresa_contato) {
		$this->empresa_contato = $empresa_contato;
	}
	public function getData() {
		return $this->data;
	}
	public function setData($data) {
		$this->data = $data;
	}
	public function getDataUtilizacao() {
		return $this->data_utilizacao;
	}
	public function setDataUtilizacao($data_utilizacao) {
		$this->data_utilizacao = $data_utilizacao;
	}
	public function getReqItemId() {
		return $this->req_item_id;
	}
	public function setReqItemId($req_item_id) {
		$this->req_item_id[] = $req_item_id;
	}
	public function getOc() {
		return $this->oc;
	}
	public function setOc($oc) {
		$this->oc = $oc;
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
	public function getUm() {
		return $this->um;
	}
	public function setUm($um) {
		$this->um[] = $um;
	}
	public function getTamanho() {
		return $this->tamanho;
	}
	public function setTamanho($tamanho) {
		$this->tamanho[] = $tamanho;
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

    public function getArquivoID() {
		return $this->arquivo_id;
	}

    public function setArquivoID($id) {
		$this->arquivo_id[] = $id;
	}
	
	public function getDescricao() {
		return $this->descricao;
	}

    public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	
	public function getObservacaoItem() {
		return $this->observacao_item;
	}

    public function setObservacaoItem($observacao_item) {
		$this->observacao_item[] = $observacao_item;
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
	public static function listar($p197) {
		return _13010DAO::listar($p197);
	}

    /**
	 * Select da página inicial.
	 *
	 * @return array
	 */
	public static function listarTamanho($id) {
		return _13010DAO::listarTamanho($id);
	}
	
	/**
	 * Gerar id do objeto.
	 *
	 * @return integer
	 */
	public static function gerarId() {
		return _13010DAO::gerarId();
	}
	
	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param _13010 $obj
	 */
	public static function gravar(_13010 $obj) {
		return _13010DAO::gravar($obj);
	}
	
	/**
	 * Retorna dados do objeto na base de dados.
	 *
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		return _13010DAO::exibir($id);
	}
	
	/**
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param int $id
	 */
	public static function alterar(_13010 $obj) {
	   return _13010DAO::alterar($obj);
	}
	
	/**
	 * Exclui dados do objeto na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluir($id) {
		return _13010DAO::excluir($id);
	}
	
	/**
	 * Pesquisa centro de custo de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCusto($filtro) {
		return _13010DAO::pesquisaCCusto($filtro);
	}
	
	/**
	 * Pesquisa gestores de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaGestor($filtro) {
		return _13010DAO::pesquisaGestor($filtro);
	}
	
	/**
	 * Pesquisa produto de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaProduto($filtro) {
		return _13010DAO::pesquisaProduto($filtro);
	}
	
	/**
	 * Paginação com scroll.
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
	 * @param int $pagina
	 * @return array
	 */
	public static function paginacaoScroll($qtd_por_pagina, $pagina, $status, $p197) {
		return _13010DAO::paginacaoScroll($qtd_por_pagina, $pagina, $status, $p197);
	}
	
	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraObj($filtro, $status, $p197) {

       return _13010DAO::filtraObj($filtro, $status, $p197);

//       if (!\Cache::has($filtro)) {
//            $Ret = _13010DAO::DadosOC($filtro);
//
//            \Cache::put($filtro,$Ret,1);
//       }
//
//       $Dados = \Cache::get($filtro);
//
//       return $Dados;
	}

	/**
	 * Exclui produto.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 */
	public static function excluiProduto($item) {
		return _13010DAO::excluiProduto($item);
	}
	
	/**
	 * Exclui arquivo.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 */
	public static function excluiArquivo($item) {
		_13010DAO::excluiArquivo($item);
	}
	
	/**
	 * Exclui arquivo.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 */
	public static function downloadArquivo($item) {
		$Ret =  _13010DAO::downloadArquivo($item);
		return $Ret;
	}
	
	/**
	 * Exclui produto.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 */
	public static function EnviaArquivo($vin,$file_type,$file_tmp,$file_name,$file_size,$binario,$Tabela) {

		_13010DAO::enviaArquivo($vin,$file_type,$file_tmp,$file_name,$file_size,$binario,$Tabela);
	}

    /**
	 * Filtra Objetos
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 */
	public static function DadosOC($Filtro) {

		return _13010DAO::DadosOC($Filtro);
	}
}

?>