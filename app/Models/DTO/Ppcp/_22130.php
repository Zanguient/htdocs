<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22130DAO;

/**
 * Objeto _22130 - Conformacao
 */
class _22130
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _22130DAO::getChecList($dados);
	}

	/**
	 * get Maquinas de uma up
	 */
	public static function getMaquina($dados) {
		return _22130DAO::getMaquina($dados);
	}

	/**
	 * justificar ineficiencia
	 */
	public static function justIneficiencia($dados,$con) {
		return _22130DAO::justIneficiencia($dados,$con);
	}

	/**
	 * get Conformacao de uma familia
	 */
	public static function getConformacao($dados) {
		return _22130DAO::getConformacao($dados);
	}

	/**
	 * Consulta uma matriz
	 */
	public static function getMatriz($dados) {
		return _22130DAO::getMatriz($dados);
	}

	/**
	 * Tempo disponivel e corrido UP
	 */
	public static function getTempo($filtro,$con) {
		return _22130DAO::getTempo($filtro,$con);
	}

	/**
	 * Consulta produção de uma estação
	 */
	public static function getProducao($filtro,$con) {
		return _22130DAO::getProducao($filtro,$con);
	}

	/**
	 * Consulta paradas de uma estacao, analitico
	 */
	public static function getParadas_a($filtro,$con) {
		return _22130DAO::getParadas_a($filtro,$con);
	}

	/**
	 * Consulta paradas de uma estacao, sintetico
	 */
	public static function getParadas_s($filtro,$con) {
		return _22130DAO::getParadas_s($filtro,$con);
	}

	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _22130DAO::listar($dados);
	}

	/**
	 * Iniciar Talao
	 */
	public static function iniciarTalao($dados,$con,$commit){
		return _22130DAO::iniciarTalao($dados,$con,$commit);
	}

	/**
	 * Setup Inicio
	 */
	public static function iniciarSetup($dados,$con){
		return _22130DAO::iniciarSetup($dados,$con);
	}

	/**
	 * Parar Talao
	 */
	public static function pararTalao($dados,$con,$commit){
		return _22130DAO::pararTalao($dados,$con,$commit);
	}


	/**
	 * Finalizar Talao
	 */
	public static function finalizarTalao($dados,$con,$commit){
		return _22130DAO::finalizarTalao($dados,$con,$commit);
	}

	/**
	 * Finalizar Talao
	 */
	public static function trocarEstacaoTalao($dados,$con,$commit){
		return _22130DAO::trocarEstacaoTalao($dados,$con,$commit);
	}


	/**
	 * Listar
	 */
	public static function getEstacoes($filtro,$con) {
		return _22130DAO::getEstacoes($filtro,$con);
	}
	

	/**
	 * getMeta
	 */
	public static function getMeta($filtro,$con) {
		return _22130DAO::getMeta($filtro,$con);
	}

	/**
	 * getTurnos
	 */
	public static function getTurnos($filtro,$con) {
		return _22130DAO::getTurnos($filtro,$con);
	}

	/**
	 * getMeta_t
	 */
	public static function getMeta_t($filtro,$con) {
		return _22130DAO::getMeta_t($filtro,$con);
	}

	/**
	 * getMeta_g
	 */
	public static function getMeta_g($filtro,$con) {
		return _22130DAO::getMeta_g($filtro,$con);
	}


    /**
	 * get Producao por turno
	 */
	public static function getProducao_t($filtro,$con){
		return _22130DAO::getProducao_t($filtro,$con);
	}

	/**
	 * parar estacao
	 */
	public static function pararEstacao($filtro,$con){
		return _22130DAO::pararEstacao($filtro,$con);
	}

    /**
	 * getMeta
	 */
	public static function getEficiencia_t($filtro,$con){
		return _22130DAO::getEficiencia_t($filtro,$con);
	}
    /**
	 * getMeta
	 */
	public static function getPerdas_t($filtro,$con){
		return _22130DAO::getPerdas_t($filtro,$con);
	}

    /**
	 * getProducao_g
	 */
	public static function getProducao_g($filtro,$con){
		return _22130DAO::getProducao_g($filtro,$con);
	}

    /**
	 * getEficiencia_g
	 */
	public static function getEficiencia_g($filtro,$con){
		return _22130DAO::getEficiencia_g($filtro,$con);
	}

    /**
	 * getPerdas_g
	 */
	public static function getPerdas_g($filtro,$con){
		return _22130DAO::getPerdas_g($filtro,$con);
	}

    /**
	 * getTaloes_producao
	 */
	public static function getTaloes_producao($filtro,$con){
		return _22130DAO::getTaloes_producao($filtro,$con);
	}

	/**
     * Consulta justificativas
     */
    public static function consultaJustificativa($flag,$con = null){
    	return _22130DAO::consultaJustificativa($flag,$con);
    }


	/**
     * Dados de pedido de um talão
     */
    public static function pedidosTalao($dados,$con){
    	return _22130DAO::pedidosTalao($dados,$con);
	}

	/**
     * Dados de espuma de um talão
     */
    public static function espumaTalao($dados,$con){
    	return _22130DAO::espumaTalao($dados,$con);
	}

    /**
     * Dados de matriz de um talão
     */
    public static function matrizTalao($dados,$con){
    	return _22130DAO::matrizTalao($dados,$con);
	}

    /**
     * Dados de um talão
     */
    public static function dadosTalao($dados,$con){
    	return _22130DAO::dadosTalao($dados,$con);
	}

    /**
     * Dados de produto de um talão
     */
    public static function skuTalao($dados,$con){
    	return _22130DAO::skuTalao($dados,$con);
	}

    /**
     * Dados de tecido de um talão
     */
    public static function tecidoTalao($dados,$con){
    	return _22130DAO::tecidoTalao($dados,$con);
	}

	/**
     * Dados de tecido da requisicao de um talão
     */
    public static function tecidoTalaoRequisicao($dados,$con){
    	return _22130DAO::tecidoTalaoRequisicao($dados,$con);
	}

	/**
     * Ferramentas Livres
     */
    public static function ferramentasLivres($dados,$con){
    	return _22130DAO::ferramentasLivres($dados,$con);
	}

	/**
     * Trocar Ferramentas
     */
    public static function trocarFerramenta($dados,$con){
    	return _22130DAO::trocarFerramenta($dados,$con);
	}

	/**
     * Componentes usados na linha do tempo de producao de um talao/remessa
     */
    public static function getComponentes($dados,$con){
    	return _22130DAO::getComponentes($dados,$con);
    }

    /**
     * Consultra minutos do procimo intervalo disponivel
     * @access public
     * @param {} $dados
     * @param {} $con
     * @return array
     */
    public static function jornadaIntervalo($dados,$con){
    	return _22130DAO::jornadaIntervalo($dados,$con);
    }

    /**
     * Gravar jornada minitos
     * @access public
     * @param {} $dados
     * @param {} $con
     * @return array
     */
    public static function jornadaGravar($dados){
    	return _22130DAO::jornadaGravar($dados);
    }

    /**
     * historico do Talao
     */
    public static function getHistoricoTalao($dados){
    	return _22130DAO::getHistoricoTalao($dados);
    }

    /**
     * get Composicao componente
     */
    public static function getComposicao($dados,$con){
    	return _22130DAO::getComposicao($dados,$con);
    }	

    

}