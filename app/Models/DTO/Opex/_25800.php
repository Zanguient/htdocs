<?php

namespace App\Models\DTO\Opex;

use App\Models\DAO\Opex\_25800DAO;

/**
* Classe de do objeto 25800
*/
class _25800
{
	
    /**
     * Função que codifica uma string 
     * @access public
     * @param string $string
     * @return string
    */
    public static function Encrypt($string) {
            return _25800DAO::encrypt($string);
    }
    
    /**
     * Função que listar os criterios de um indicador (ultimo registrado + notas) 
     * @access public
     * @param string $string
     * @return string
    */
    public static function consultaFaixasIndicador($id,$gp) {
            return _25800DAO::consultaFaixasIndicador($id,$gp);
    }
    
    
    
    /**
     * Função que decodifica uma string 
     * @access public
     * @param string $string
     * @return string
    */
    public static function Decrypt($string) {
            return _25800DAO::dcrypt($string);
    }
    
    /**
     * Função que consulta os indicadores de produção
     * @access public
     * @param string $dados
     * @return string
    */
    public static function consultaprod($dados) {
            return _25800DAO::consultaprod($dados);
    }
    
    /**
     * Função que consulta dados do bsc
     * @access public
     * @param string $dados
     * @param string $gp fabricas separadas por virdula
     * @param int $dia se 1 trata semana mes dia, se 2 trada dia
     * @return string
    */
    public static function consultabsc($dados,$gp,$dia,$data) {
            return _25800DAO::consultabsc($dados,$gp,$dia,$data);
    }
    
    /**
     * grava resultado do calculo dos trofeis
     * @access public
     * @param int $t1 ouro
     * @param int $t2 prata
     * @param int $t3 bronze
     * @param string $mes mes de calculo
     * @return string
     * @static
    */
    public static function gravatrofeu($t1,$t2,$t3,$mes,$ano,$et) {
            return _25800DAO::gravatrofeu($t1,$t2,$t3,$mes,$ano,$et);
    }
    
    /**
     * consulta todo os trofeis de uma fabrica durante um ano
     * @access public
     * @param int $ano 
     * @param int $estab Estabelecimento
     * @param int $gp 
     * @return string
     * @static
    */
    public static function consultatrofeuallgp($ano,$estab,$gp){
        return _25800DAO::consultatrofeuallgp($ano,$estab,$gp);
    }
    
    /**
     * consulta horarios do ranking
     * @access public
     * @param int $familia
     * @return string
     * @static
    */
    public static function horasRanking($familia){
        return _25800DAO::horasRanking($familia);
    }
    
    /**
     * consulta data de produção por familia
     * @access public
     * @param [] $familia
     * @return string
     * @static
    */
    public static function dataproducao($familia){
        return _25800DAO::dataproducao($familia);
    }
    
    /**
     * consulta descrição dos grupos de produção
     * @access public
     * @param int $id
     * @return string
     * @static
    */
    public static function descfabrica($id){
        return _25800DAO::descfabrica($id);
    }
    
    /**
     * Função que consulta dados do bsc
     * @access public
     * @param string $dados
     * @param string $gp fabricas separadas por virdula
     * @param int $dia se 1 trata semana mes dia, se 2 trada dia
     * @return string
    */
    public static function selectTodasGPS() {
            return _25800DAO::selectTodasGPS();
    }
    
    /**
     * consulta trofeis
     * @access public
     * @param int $mes
     * @param int $estab Estabelecimento
     * @return string
     * @static
    */
    public static function consultatrofeu($mes,$ano,$estab) {
            return _25800DAO::consultatrofeu($mes,$ano,$estab);
    }
    
    
    /**
     * Função que consulta um INDICADOR
     * @access public
     * @param int $id
     * @return string
    */
    public static function consultaIndicador($id) {
            return _25800DAO::consultaIndicador($id);
    }
    
    
    /**
     * Lista horarios de troca do CEPO 
     * @access public
     * @param int $estab ID do estabelecimento
     * @return string
     * @static
    */
    public static function listaHCEPO($estab) {
            return _25800DAO::listaHCEPO($estab);
    }
    
    /**
     * lista gps em ordem
     * @access public
     * @param int $id lista de gps (ids)
     * @return string
    */
    public static function selectListGP($id) {
            return _25800DAO::selectListGP($id);
    }
    
    /**
     * letreiro
     * @access public
     * @param int $id lista de gps (ids)
     * @return string
    */
    public static function letreiro($id,$ccusto,$estba) {
            return _25800DAO::letreiro($id,$ccusto,$estba);
    }    
    
	
}