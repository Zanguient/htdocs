<?php

namespace App\Models\DTO\Opex;

use App\Models\DAO\Opex\_25600DAO;
use Illuminate\Support\Facades\Response;

class _25600 {

    /**
     * Função para consultar centro de custo
     * @access public
     * @param string $filtro
     * @return array
     */
    public static function pesquisaCCusto($filtro) {
        return _13010DAO::pesquisaCCusto($filtro);
    }
    
    /**
     * Store do Objeto 25600
     * @access public
     * @param string $bsc
     * @param string $data
     * @param string $ccusto
     * @param array  $dados
     * @return array
     */
    public static function store($dados,$bsc,$data,$ccusto) {
        return _25600DAO::store($dados,$bsc,$data,$ccusto);
    }

    /**
     * Função para listar indicadores  
     * @access public
     * @param string $ccusto
     * @param string $data
     * @return array
     * @static
     */
    public static function consultarIndicadores($ccusto, $data) {
        return _25600DAO::consultarIndicadores($ccusto, $data);
    }

    /**
     * Função para listar valore de um registro de indicador  
     * @access public
     * @param integer $id
     * @return array
     * @static
     */
    public static function consultarRegistroIndicadores($id) {
        return _25600DAO::consultarRegistroIndicadores($id);
    }
    
    /**
     * Função para alterar nota de um indicador  
     * @access public
     * @param integer $id
     * @param integer $valor
     * @param integer $indicador
     * @param integer $plano
     * @param integer $idDetalhe
     * @param string  $descPlano
     * @return array
     * @static
     */
    public static function alterarIndicador($id,$valor,$indicador,$plano,$idDetalhe,$descPlano,$idIndicador) {
        return _25600DAO::alterarIndicador($id,$valor,$indicador,$plano,$idDetalhe,$descPlano,$idIndicador);
    }
    
    /**
     * Função para listar faixas de um indicador  
     * @access public
     * @param integer $id
     * @return array
     * @static
     */
    public static function listaFaixas($id) {
        $tag = $id.'listaFaixas';
        
        if (!\Cache::has($tag)) {
            $Ret = _25600DAO::filtrarFaixa($id);

            \Cache::put($tag,$Ret,1);
       }

       $Dados = \Cache::get($tag);
            
        return $Dados;
    }
    
    /**
     * Função para descrição de uma nota que não é verde  
     * @access public
     * @param integer $indicador
     * @param integer $idDetalhe
     * @param integer $valor
     * @return array
     * @static
     */
    public static function consultaDescricaoFaixa($indicador,$idDetalhe,$valor) {

        $tag = $indicador.'_'.$idDetalhe.'_'.$valor.'consultaDescricaoFaixa';
        
        if (!\Cache::has($tag)) {
            $Ret = _25600DAO::consultaDescricaoFaixa($indicador,$idDetalhe,$valor);

            \Cache::put($tag,$Ret,30);
       }

       $Dados = \Cache::get($tag);
            
        return $Dados;
        
    }
    
    /**
     * Função para descrição de uma nota que não é verde   
     * @access public
     * @param integer $indicador
     * @param integer $idDetalhe
     * @param integer $valor
     * @return array
     * @static
     */
    public static function consultaDescricaoFaixas($indicador,$idDetalhe) {
        
        $tag = $indicador.'_'.$idDetalhe.'consultaDescricaoFaixas';
        
        if (!\Cache::has($tag)) {
            $Ret = _25600DAO::consultaDescricaoFaixas($indicador,$idDetalhe);

            \Cache::put($tag,$Ret,30);
       }

       $Dados = \Cache::get($tag);
            
        return $Dados;
    }
    
    

}
