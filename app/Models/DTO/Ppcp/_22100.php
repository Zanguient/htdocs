<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22100DAO;

/**
 * Objeto _22100 - Geracao de Remessas de Bojo
 */
class _22100
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _22100DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function selectAgrupamentoItens($dados, $con = null) {
		return _22100DAO::selectAgrupamentoItens(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectGpUpEstacao($dados, $con = null) {
		return _22100DAO::selectGpUpEstacao(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectConsumoMpAlocacao($dados, $con = null) {
		return _22100DAO::selectConsumoMpAlocacao(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectProdutoEstoque($dados, $con = null) {
		return _22100DAO::selectProdutoEstoque(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectFerramenta($dados, $con = null) {
		return _22100DAO::selectFerramenta(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectFerramentaAlocacoes($dados, $con = null) {
		return _22100DAO::selectFerramentaAlocacoes(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectCoresSimilares($dados, $con = null) {
		return _22100DAO::selectCoresSimilares(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectModeloTempo($dados, $con = null) {
		return _22100DAO::selectModeloTempo(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectUltimoTalaoEstacao($dados, $con = null) {
		return _22100DAO::selectUltimoTalaoEstacao(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectSkuDefeitoPercentual($dados, $con = null) {
		return _22100DAO::selectSkuDefeitoPercentual(obj_case($dados),$con);
	}
    
	/**
	 * Listar
	 */
	public static function selectLinhaRemessaHistorico($dados, $con = null) {
		return _22100DAO::selectLinhaRemessaHistorico(obj_case($dados),$con);
	}
    
    /**
     * Insere remessa
     */
	public static function insertRemessa($dados, $con = null) {
		return _22100DAO::insertRemessa(obj_case($dados),$con);
	}
    
    /**
     * Insere remessa
     */
	public static function insertRemessaTalao($dados, $con = null) {
		return _22100DAO::insertRemessaTalao(obj_case($dados),$con);
	}
    
    /**
     * Insere remessa
     */
	public static function insertRemessaTalaoDetalhe($dados, $con = null) {
		return _22100DAO::insertRemessaTalaoDetalhe(obj_case($dados),$con);
	}
    
    /**
     * Insere remessa
     */
	public static function insertPedidoAlocacao($dados, $con = null) {
		return _22100DAO::insertPedidoAlocacao(obj_case($dados),$con);
	}
    
    /**
     * Insere remessa
     */
	public static function insertProgramacao($dados, $con = null) {
		return _22100DAO::insertProgramacao(obj_case($dados),$con);
	}
    
    /**
     * Insere remessa
     */
	public static function insertPedidoBloqueio($dados, $con = null) {
		return _22100DAO::insertPedidoBloqueio(obj_case($dados),$con);
	}
    
    /**
     * Insere remessa
     */
	public static function insertPedidoDesbloqueio($dados, $con = null) {
		return _22100DAO::insertPedidoDesbloqueio(obj_case($dados),$con);
	}

	public static function selectPedidoBloqueioUsuario($dados, $con = null) {
		return _22100DAO::selectPedidoBloqueioUsuario(obj_case($dados),$con);
	}
    
	public static function spPedidoItemIntegridade($dados, $con = null) {
		return _22100DAO::spPedidoItemIntegridade(obj_case($dados),$con);
	}
    

    
}