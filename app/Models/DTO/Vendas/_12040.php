<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12040DAO;

/**
 * Objeto _12040 - Registro de Pedidos
 */
class _12040
{
	private $cliente_id;
	private $cliente_descricao;
	private $representante_id;
	private $representante_descricao;
	
	function getClienteId() {
		return $this->cliente_id;
	}

	function getClienteDescricao() {
		return $this->cliente_descricao;
	}

	function getRepresentanteId() {
		return $this->representante_id;
	}

	function getRepresentanteDescricao() {
		return $this->representante_descricao;
	}

	function setClienteId($cliente_id) {
		$this->cliente_id = $cliente_id;
	}

	function setClienteDescricao($cliente_descricao) {
		$this->cliente_descricao = $cliente_descricao;
	}

	function setRepresentanteId($representante_id) {
		$this->representante_id = $representante_id;
	}

	function setRepresentanteDescricao($representante_descricao) {
		$this->representante_descricao = $representante_descricao;
	}

	/**
	 * Verificar se o usuário é um representante.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function verificarUsuarioEhRepresentante($param, $con) {
		return _12040DAO::verificarUsuarioEhRepresentante($param, $con);
	}

	/**
	 * Consultar representante do cliente.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarRepresentanteDoCliente($param, $con) {
		return _12040DAO::consultarRepresentanteDoCliente($param, $con);
	}

	/**
	 * Consultar pedidos.
	 * @param array $filtro
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarPedido($filtro, $con) {
		return _12040DAO::consultarPedido($filtro, $con);
	}

	/**
	 * Consultar pedidos.
	 * @param array $filtro
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarPedido2($filtro, $con) {
		return _12040DAO::consultarPedido2($filtro, $con);
	}

	/**
	 * Consultar itens de pedidos.
	 * @param array $filtro
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarPedidoItem($filtro, $con) {
		return _12040DAO::consultarPedidoItem($filtro, $con);
	}
	
	/**
	 * Consultar informações gerais.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarInfoGeral($param, $con) {
		return _12040DAO::consultarInfoGeral($param, $con);
	}

	/**
	 * Consultar tamanho com preço.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarTamanhoComPreco($param, $con) {
		return _12040DAO::consultarTamanhoComPreco($param, $con);
	}

	/**
	 * Consultar informações de perfil (quantidades e prazos) e 
     * quantidade mínima e múltipla do modelo de acordo com o tamanho.
	 * @param array $filtro
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarQtdEPrazoPorTamanho($filtro, $con) {
		return _12040DAO::consultarQtdEPrazoPorTamanho($filtro, $con);
	}

	/**
     * Consultar a quantidade mínima liberada para uma cor.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarQtdLiberada($param, $con) {
    	return _12040DAO::consultarQtdLiberada($param, $con);
    }

	/**
	 * Consultar se o número do pedido do cliente já existe.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarNumPedidoCliente($param, $con) {
		return _12040DAO::consultarNumPedidoCliente($param, $con);
	}

	/**
     * Gerar id do objeto.
     * @param _Conexao $con
     * @return integer
     */
	public static function gerarId($con) {
		return _12040DAO::gerarId($con);
	}

	public static function gravarPedido($dados, $con) {
		return _12040DAO::gravarPedido($dados, $con);
	}

	public static function alterarEmpresaEmailXml($dados, $con) {
		return _12040DAO::alterarEmpresaEmailXml($dados, $con);
	}

	public static function gravarPedidoItem($pedido, $pedidoItem, $con) {
		return _12040DAO::gravarPedidoItem($pedido, $pedidoItem, $con);
	}

	public static function excluirPedidoItem($pedidoItemExcluir, $con) {
		return _12040DAO::excluirPedidoItem($pedidoItemExcluir, $con);
	}

	public static function excluirPedido($pedido, $con) {
		return _12040DAO::excluirPedido($pedido, $con);
	}

	/**
     * Gerar chave para liberação de nova quantidade mínima para cor.
     * @access public
     * @param _Conexao $con
     * @return array
     */
	public static function gerarChave($con) {
		return _12040DAO::gerarChave($con);
	}

	/**
     * Gravar liberação de nova quantidade mínima para cor.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
	public static function gravarLiberacao($param, $con) {
		return _12040DAO::gravarLiberacao($param, $con);
	}

}