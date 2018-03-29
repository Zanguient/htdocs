<?php

namespace App\Models\DTO\Opex;

use App\Models\DAO\Opex\_25900DAO;

/**
 * _25900 - Opex
 */
class _25900
{
	/**
	 * •
     * @param [] $dados
	 */
	public static function getChecList($dados) {
		return _25900DAO::getChecList($dados);
	}
    
    /**
	 * Consultar Setores
     * @param [] $dados
     * @return array
	 */
    public static function consultarSetor($dados) {
        return _25900DAO::consultarSetor($dados);
	}
    
    /**
	 * Consultar Perspectivas
     * @param [] $dados
     * @return array
	 */
    public static function consultarPerspectiva($dados) {
        return _25900DAO::consultarPerspectiva($dados);
    }
    
    /**
	 * Consultar Areas
     * @param [] $dados
     * @return array
	 */
    public static function consultarArea($dados) {
        return _25900DAO::consultarArea($dados);
	}
    
    /**
	 * Consultar um indicador
     * @param [] $dados
     * @return array
	 */
    public static function filtarIndicador($dados) {
        return _25900DAO::filtarIndicador($dados);
	}
    
    /**
     * Configuracoes de uma Areas do BSC Conf 
     * @access public
     * @param int $area_id
     * @return array
     */
    public static function consultarAreaConf($area_id) {
        return _25900DAO::consultarAreaConf($area_id);
	}
    
    /**
     * Consulta setores de um ou mais grupos 
     * @access public
     * @param String $grupoList
     * @param String $setorList
     * @return array
     */
    public static function consultarSetoresConf($grupoList,$setorList){
        return _25900DAO::consultarSetoresConf($grupoList,$setorList);
	}
    
    /**
     * Consulta os indicadores de uma perspectiva 
     * @access public
     * @param int $perspectiva_id
     * @return array
     */
    public static function consultarPerspectivaConf($perspectiva_id){
        return _25900DAO::consultarPerspectivaConf($perspectiva_id);
	}
    
    /**
     * Consulta descricao dos setores 
     * @access public
     * @param int $setorList
     * @return array
     */
    public static function consultarDescricao($setorList,$grupoList){
        return _25900DAO::consultarDescricao($setorList,$grupoList);
	}
    
    /**
     * Consulta os valores dos indicadores  
     * @access public
     * @param array $dados
     * @return array
     */
    public static function consultarIndicadores($dados){
        return _25900DAO::consultarIndicadores($dados);
	}
    
    /**
     * Consulta data de producao  
     * @access public
     * @param string $fanilha
     * @return array
     */
    public static function consultarDataProd($fanilha){
        return _25900DAO::consultarDataProd($fanilha);
    }

    /**
     * Consulta faixa indicador  
     * @access public
     * @param string $fanilha
     * @return array
     */
    public static function consultarIndicadorFaixa($id){
        return _25900DAO::consultarIndicadorFaixa($id);
    }
    
    /**
     * Consulta agrupamentos  
     * @access public
     * @param string $perpectiva_id
     * @return array
     */
    public static function consultarAgrupamentos($perpectiva_id){
        return _25900DAO::consultarAgrupamentos($perpectiva_id);
	}
    
    /**
     * Esecuta o sql de um componente  
     * @access public
     * @param string $sql
     * @return array
     */
    public static function execComponente($sql,$ccusto,$flag){
        return _25900DAO::execComponente($sql,$ccusto,$flag);
	}
    
    
}