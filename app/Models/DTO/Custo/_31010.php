<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31010DAO;

/**
 * Objeto _31010 - Custos Gerenciais
 */
class _31010
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _31010DAO::getChecList($dados);
	}
	
	/**
     * Listar
     */
    public static function listar($dados) {
        return _31010DAO::listar($dados);
    }

    /**
     * Listar
     */
    public static function FaturamentoFamilia($filtro,$con) {
        return _31010DAO::FaturamentoFamilia($filtro,$con);
    }

    /**
     * Listar
     */
    public static function ConsultarPrecoVenda($filtro,$con) {
        return _31010DAO::ConsultarPrecoVenda($filtro,$con);
    }

    /**
     * Consultar Incentivo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function consultarIncentivo($filtro,$con) {
        return _31010DAO::consultarIncentivo($filtro,$con);
    }

    /**
     * Consultar Incentivo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function consultarProduto($filtro,$con) {
        return _31010DAO::consultarProduto($filtro,$con);
    }

    /**
     * Consultar Incentivo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function consultarDensidade($filtro,$con) {
        return _31010DAO::consultarDensidade($filtro,$con);
    }
    

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarDetalheDespesa($filtro,$con){
        return _31010DAO::ConsultarDetalheDespesa($filtro,$con);
    }

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function gravarSimulacao($filtro,$con){
        return _31010DAO::gravarSimulacao($filtro,$con);
    }

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function excluirSimulacao($filtro,$con){
        return _31010DAO::excluirSimulacao($filtro,$con);
    }

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Simulacao($filtro,$con){
        return _31010DAO::Simulacao($filtro,$con);
    }

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarSimulacao($filtro,$con){
        return _31010DAO::ConsultarSimulacao($filtro,$con);
    }

    /**
     * Consulta os tipos de mercado
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function custoPadrao($filtro,$con){
        return _31010DAO::custoPadrao($filtro,$con);
    }

    /**
     * Consulta os itens do tipos de mercado
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function custoPadraoItem($filtro,$con){
        return _31010DAO::custoPadraoItem($filtro,$con);
    }

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarAbsorcao($filtro,$con){
        return _31010DAO::ConsultarAbsorcao($filtro,$con);
    }

    /**
     * Consultar Detalhamento de Depesas
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function DetalharDespesa($filtro,$con){
        return _31010DAO::DetalharDespesa($filtro,$con);
    }

    

    /**
     * Consultar Despesas
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarDespesas($filtro,$con){
        return _31010DAO::ConsultarDespesas($filtro,$con);
    }

    /**
     * Consultar Estacações
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarEstacoes($filtro,$con){
        return _31010DAO::ConsultarEstacoes($filtro,$con);
    }

    /**
     * Consultar Detalhamento Absorcao Proprio
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarProprio($filtro,$con){
        return _31010DAO::ConsultarProprio($filtro,$con);
    }

    /**
     * Consultar Configuracoes de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarConfiguracao($filtro,$con){
        return _31010DAO::ConsultarConfiguracao($filtro,$con);
    }
	
    

	/**
     * Consultar modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
    	return _31010DAO::Consultar($filtro,$con);
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarCor($filtro,$con) {
    	return _31010DAO::ConsultarCor($filtro,$con);
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarFicha($filtro,$con) {
        return _31010DAO::ConsultarFicha($filtro,$con);
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarFicha2($filtro,$con) {
        return _31010DAO::ConsultarFicha2($filtro,$con);
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarFichaTempo($filtro,$con) {
        return _31010DAO::ConsultarFichaTempo($filtro,$con);
    }

    

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarPerfil($filtro,$con) {
    	return _31010DAO::ConsultarPerfil($filtro,$con);
    }
    

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarTamanho($filtro,$con) {
        return _31010DAO::ConsultarTamanho($filtro,$con);
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarTamanho2($filtro,$con) {
        return _31010DAO::ConsultarTamanho2($filtro,$con);
    }

    /**
     * Consultar Tempo de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarTempo($filtro,$con) {
        return _31010DAO::ConsultarTempo($filtro,$con);
    }

    /**
     * Consultar mao de Obra de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarMaoDeObra($filtro,$con) {
        return _31010DAO::ConsultarMaoDeObra($filtro,$con);
    }

    


}