<?php

namespace App\Models\DTO\Chamados;

use App\Models\DAO\Chamados\_26021DAO;

/**
 * Objeto _26021 - Pesquisa de satisfação do cliente
 */
class _26021
{
	
	/**
	 * Consultar pesquisas.
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarPesquisa($param, $con) {
		return _26021DAO::consultarPesquisa($param, $con);
	}

	/**
	 * Consultar respostas de uma pesquisa.
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarResposta($param, $con) {
		return _26021DAO::consultarResposta($param, $con);
	}


	/**
	 * Consultar modelo de pesquisas (26020).
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloPesquisa($con) {
		return _26021DAO::consultarModeloPesquisa($con);
	}

	/**
	 * Consultar perguntas do modelo de pesquisas (26020).
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloPesquisaPergunta($param, $con) {
		return _26021DAO::consultarModeloPesquisaPergunta($param, $con);
	}

	/**
	 * Consultar alternativas das perguntas do modelo de pesquisas (26020).
	 * @access public
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloPesquisaPerguntaAlternativa($param, $con) {
		return _26021DAO::consultarModeloPesquisaPerguntaAlternativa($param, $con);
	}

	/**
	 * Consultar clientes.
	 * @access public
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarCliente($con) {
		return _26021DAO::consultarCliente($con);
	}

	/**
     * Gerar id da pesquisa.     *
     * @access public
     * @param _Conexao $con
     * @return array
     */
	public static function gerarIdPesquisa($con) {
		return _26021DAO::gerarIdPesquisa($con);
	}

	/**
	 * Gravar pesquisa.
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarPesquisa($dado, $con) {
		return _26021DAO::gravarPesquisa($dado, $con);
	}

	/**
	 * Gravar resposta.
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarResposta($dado, $con) {
		return _26021DAO::gravarResposta($dado, $con);
	}

	/**
	 * Excluir pesquisa.
	 * @access public
	 * @param json $dado
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluir($dado, $con) {
		return _26021DAO::excluir($dado, $con);
	}

}