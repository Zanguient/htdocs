<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15010DAO;

/**
 * Objeto 15010 - Requisição de Consumo
 */
class _15010
{
	private $id;
	private $data;
	private $datahora;
	private $usuario_id;
	private $estabelecimento_id;
    private $documento;
    private $ccusto;
    private $turno;
    private $produto_id;
    private $quantidade;
    private $tamanho;
    private $observacao;
    private $saldo;
    private $status;
    private $flag;
    private $operacao;
    private $localizacao;
	
	public function getId() {
		return $this->id;
	}

	public function getData() {
		return $this->data;
	}

	public function getDatahora() {
		return $this->datahora;
	}

	public function getUsuarioId() {
		return $this->usuario_id;
	}

	public function getEstabelecimentoId() {
		return $this->estabelecimento_id;
	}

	public function getDocumento() {
		return $this->documento;
	}

	public function getCcusto() {
		return $this->ccusto;
	}

	public function getTurno() {
		return $this->turno;
	}

	public function getProdutoId() {
		return $this->produto_id;
	}

	public function getQuantidade() {
		return $this->quantidade;
	}

	public function getTamanho() {
		return $this->tamanho;
	}

	public function getObservacao() {
		return $this->observacao;
	}

	public function getSaldo() {
		return $this->saldo;
	}

	public function getStatus() {
		return $this->status;
	}
    
    public function getFlag() {
		return $this->flag;
	}
    
    public function getOperacao() {
		return $this->operacao;
	}
    
    public function getLocalizacao() {
		return $this->localizacao;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function setDatahora($datahora) {
		$this->datahora = $datahora;
	}

	public function setUsuarioId($usuario_id) {
		$this->usuario_id = $usuario_id;
	}

	public function setEstabelecimentoId($estabelecimento_id) {
		$this->estabelecimento_id = $estabelecimento_id;
	}

	public function setDocumento($documento) {
		$this->documento = $documento;
	}

	public function setCcusto($ccusto) {
		$this->ccusto = $ccusto;
	}

	public function setTurno($turno) {
		$this->turno = $turno;
	}

	public function setProdutoId($produto_id) {
		$this->produto_id[] = $produto_id;
	}

	public function setQuantidade($quantidade) {
		$this->quantidade[] = $quantidade;
	}

	public function setTamanho($tamanho) {
		$this->tamanho[] = $tamanho;
	}

	public function setObservacao($observacao) {
		$this->observacao[] = $observacao;
	}

	public function setSaldo($saldo) {
		$this->saldo[] = $saldo;
	}

	public function setStatus($status) {
		$this->status = $status;
	}
    
    public function setFlag($flag) {
		$this->flag = $flag;
	}
    
    public function setOperacao($operacao) {
		$this->operacao = $operacao;
	}
    
    public function setLocalizacao($localizacao) {
		$this->localizacao = $localizacao;
	}

		
	/**
	 * Select da página inicial.
	 *
	 * @param string $estab_perm
	 * @return array
	 */
	public static function listar($estab_perm) {
		return _15010DAO::listar($estab_perm);
	}
	
	/**
	 * Gerar id do objeto.
	 *
	 * @return integer
	 */
	public static function gerarId() {
		return _15010DAO::gerarId();
	}
	
	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param _15010 $obj
	 */
	public static function gravar(_15010 $obj) {
		return _15010DAO::gravar($obj);
	}
	
	/**
	 * Retorna dados do objeto na base de dados.
	 *
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		return _15010DAO::exibir($id);
	}
	
	/**
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param _15010 $obj
	 */
	public static function alterar(_15010 $obj) {
		return _15010DAO::alterar($obj);
	}
	
	/**
	 * Exclui dados do objeto na base de dados.
	 *
	 * @param int $id
	 */
	public static function excluir($id) {
		return _15010DAO::excluir($id);
	}
	
	/**
	 * Encerrar/desencerrar requisição.
	 *
	 * @param _15010 $obj
	 */
	public static function encerrar(_15010 $obj) {
		return _15010DAO::encerrar($obj);
	}
	
	/**
	 * Paginação com scroll.
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
     * @param int $pagina
	 * @param string $filtro
	 * @param string $estab_perm
	 * @param int $status
	 * @param int $estab
	 * @param string $data_ini
	 * @param string $data_fim
	 * @return array
	 */
	public static function paginacaoScroll($qtd_por_pagina, $pagina, $filtro, $estab_perm, $status, $estab, $data_ini, $data_fim) {
		return _15010DAO::paginacaoScroll($qtd_por_pagina, $pagina, $filtro, $estab_perm, $status, $estab, $data_ini, $data_fim);
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
		return _15010DAO::filtrar($filtro, $estab_perm, $status, $estab, $data_ini, $data_fim);
	}
	
	/**
     * Filtrar lista de requisições de consumo de acordo com o status e período requerido.
     * Função chamada via Ajax.
     *
	 * @param string $estab_perm
	 * @param string $status
	 * @param string $estab
     * @param string $data_ini
	 * @param string $data_fim
     */
//    public static function filtrarRefinado($estab_perm, $status, $estab, $data_ini, $data_fim) {
//		return _15010DAO::filtrarRefinado($estab_perm, $status, $estab, $data_ini, $data_fim);
//	}
    
    /**
     * Retorna as familias de produto que o usuário possui
     * @param string $tipo_permissao 1 = Requisitar produtos ; 2 = Realizar baixa de produtos
     * @param string $valor 1 = SIM ; 0 = NÃO - Se o valor não for passado como parametro, será retorna 1
     * @param integer $usuario_id Se o usuário não for passado como parametro, será retornado as permissões do usuário conectado ao sistema
     * @return array
     */
    public static function selectPermissao($tipo_permissao,$valor = null,$usuario_id = null) {
        return _15010DAO::selectPermissao($tipo_permissao,$valor,$usuario_id);
    }
}