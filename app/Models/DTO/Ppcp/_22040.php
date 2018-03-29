<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22040DAO;

/**
 * Remessas<br/>
 * • Métodos de leitura e gravação de remessas
 */
class _22040
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    

    public function selectReposicao($param) {       
        
        $sql =
        "
            SELECT
                *

            FROM
                SPC_PROJECAO_REMESSA1(
                    :ESTABELECIMENTO_ID,
                    :FAMILIA_ID,
                    :PRODUTO_ID,
                    :TAMANHO
                )
        ";
        
        $args = [
            'ESTABELECIMENTO_ID'    => setDefValue($param->ESTABELECIMENTO_ID   , NULL),
            'FAMILIA_ID'            => setDefValue($param->FAMILIA_ID           , NULL),
            'PRODUTO_ID'            => setDefValue($param->PRODUTO_ID           , NULL),
            'TAMANHO'               => setDefValue($param->TAMANHO              , NULL),
        ]; 
        

        return $this->con->query($sql,$args); 
    }

    public function selectProducao($param) {       
        
        $sql =
        "
            SELECT R.REMESSA || ' / ' || FN_LPAD(T.REMESSA_TALAO_ID,4,0) DESCRICAO, PG.QUANTIDADE
              FROM TBPROGRAMACAO PG
         LEFT JOIN TBPEDIDO PD ON PD.REMESSA_ID = PG.REMESSA_ID
         LEFT JOIN VWREMESSA_TALAO T ON T.ID = PG.ID
         LEFT JOIN VWREMESSA R ON R.REMESSA_ID = PG.REMESSA_ID
             WHERE PG.TIPO IN ('A','D')
               AND PG.STATUS IN ('0','1','2')
               AND PD.REMESSA_ID IS NULL
               AND PG.ESTABELECIMENTO_ID = :ESTABELECIMENTO_ID
               AND PG.TAMANHO = :TAMANHO
               AND PG.PRODUTO_ID = :PRODUTO_ID
        ";
        
        $args = [
            'ESTABELECIMENTO_ID'    => setDefValue($param->ESTABELECIMENTO_ID   , NULL),
            'PRODUTO_ID'            => setDefValue($param->PRODUTO_ID           , NULL),
            'TAMANHO'               => setDefValue($param->TAMANHO              , NULL),
        ]; 
        

        return $this->con->query($sql,$args); 
    }

    public function selectPedido($param) {       
        
        $sql =
        "
            SELECT pe.PEDIDO || ' / ' || pis.PEDIDO_ITEM_CONTROLE DESCRICAO, PIS.QUANTIDADE
              FROM TBPEDIDO PE, TBPEDIDO_ITEM PI, TBPEDIDO_ITEM_SALDO PIS
             WHERE PE.PEDIDO = PI.PEDIDO
               AND PE.PEDIDO = PIS.PEDIDO
               AND PI.CONTROLE = PIS.PEDIDO_ITEM_CONTROLE
               AND PI.SITUACAO = 1
               AND PE.STATUS = '1'
               AND PE.SITUACAO = '1'
               AND COALESCE(PE.REMESSA_ID,0) = 0         
               AND PE.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO_ID
               AND PI.PRODUTO_CODIGO = :PRODUTO_ID     
               AND PI.TAMANHO = :TAMANHO
        ";
        
        $args = [
            'ESTABELECIMENTO_ID'    => setDefValue($param->ESTABELECIMENTO_ID   , NULL),
            'PRODUTO_ID'            => setDefValue($param->PRODUTO_ID           , NULL),
            'TAMANHO'               => setDefValue($param->TAMANHO              , NULL),
        ]; 
        

        return $this->con->query($sql,$args); 
    }

    public function selectEmpenhado($param) {       
        
        $sql =
        "
         SELECT R.REMESSA || ' / ' || FN_LPAD(E.TALAO,4,0) DESCRICAO, CAST(SUM(E.CONSUMO_KG-COALESCE(E.QUANTIDADE_CONFERENCIA,0)) AS NUMERIC(15,4)) QUANTIDADE
           FROM TBREMESSA_CONSUMO E LEFT JOIN VWREMESSA R ON R.REMESSA_ID = E.REMESSA
          WHERE E.CONFERENCIA = '0'
            AND COALESCE(E.COMPONENTE,'0') = '0'
            AND E.REMESSA >= -1           
            AND E.ESTABELECIMENTO_ID = :ESTABELECIMENTO_ID
            AND E.PRODUTO_ID  = :PRODUTO_ID
            AND E.TAMANHO = :TAMANHO
          GROUP BY 1
        ";
        
        $args = [
            'ESTABELECIMENTO_ID'    => setDefValue($param->ESTABELECIMENTO_ID   , NULL),
            'PRODUTO_ID'            => setDefValue($param->PRODUTO_ID           , NULL),
            'TAMANHO'               => setDefValue($param->TAMANHO              , NULL),
        ]; 
        

        return $this->con->query($sql,$args); 
    }
    
   /**
     * Retorna Listagem Principal
     * @param array $param
     * Campos de entrada:
     * <ul>
     *    <li>
     *      <b>RETORNO</b> retornos da consulta<br/>
     *       Disponíveis: <b>REMESSA</b>,<b>TALAO</b>,<b>TALAO_DETALHE</b>.
     *    </li>
     *    <li>
     *    <li>
     *      <b>FIRST</b> quantidade de registros a serem listados
     *    </li>
     *    <li>
     *    <li>
     *      <b>SKIP</b> quantidade de registros a serem saltados
     *    </li>
     *   <li> 
     *     <b>ESTABELECIMENTO_ID</b> array de estabelecimentos
     *   </li>
     *   <li>
     *     <b>FAMILIA_ID</b> array de familias
     *   </li>
     *   <li>
     *     <b>REMESSA_ID</b> array de id's de remessas
     *   </li>
     *   <li>
     *     <b>REMESSA</b> array de códigos de remessas
     *   </li>
     *   <li>
     *     <b>DATA</b> array de período array[0]: data inicial ; array[1] data final
     *   </li>
     *   <li>
     *     <b>STATUS</b> array de de status. 1 Em aberto 2 Produzido 3 Liberado 4 Encerrado
     *   </li>
     *   <li>
     *     <b>FILTRO</b> string de filtragem
     *   </li>
     * </ul>
     * @return type
     */    
    public static function listar($param = []) {
        $ret = _22040DAO::listar(obj_case($param));
        return $ret;
    }
    
    /**
     * reabrir talao 
     * @param array $param 
     */
    public static function reabrirTalao($param = [])
    {
       return _22040DAO::reabrirTalao(obj_case($param));      
    }
    
    public static function count($param = []) {
        return _22040DAO::count((object) $param);
    }

    /**
     * Gravar remessa
     * @param array $param
     * @return type
     */    
    public static function gravar($param = []) {
        return _22040DAO::gravar((object) $param);
    }
    
   /**
     * Gerar id da remessa
     * @param array $param
     * @return type
     */    
    public static function gerarId($param = []) {
        return _22040DAO::gerarId((object) $param);
    }
    
   /**
     * Gerar id do talão da remessa
     * @param array $param
     * @return type
     */    
    public static function gerarTalaoId($param = []) {
        return _22040DAO::gerarTalaoId((object) $param);
    }
    
   /**
     * Gerar id do talão detalhado da remessa
     * @param array $param
     * @return type
     */    
    public static function gerarTalaoDetalheId($param = []) {
        return _22040DAO::gerarTalaoDetalheId((object) $param);
    }
    
   /**
     * Gerar id da requisicao
     * @param array $param
     * @return type
     */    
    public static function gerarRequisicaoId($param = []) {
        return _22040DAO::gerarRequisicaoId((object) $param);
    }
    
   /**
     * Gerar id da requisicao
     * @param array $param
     * @return type
     */    
    public static function gerarReposicaoLaminacaoId($param = []) {
        return _22040DAO::gerarReposicaoLaminacaoId((object) $param);
    }
    
    /**
     * Retona informações do consumo da remessa de consumo
     * @param (object)array $param
     * Campos de entrada:
     * <ul>
     *    <li>
     *      <b>RETORNO</b> retornos da consulta<br/>
     *       Disponíveis: <b>GP</b>,<b>FAMILIA</b>,<b>PERFIL</b>,<b>NECESSIDADE</b>.
     *    </li>
     * </ul>
     * @return type
     */
    public static function remessaConsumo($param =[]) {
        return _22040DAO::remessaConsumo(obj_case($param));
    }
    
    /**
     * Retona informações da programação da remessa de consumo
     * @param type $param
     * @return type
     */
    public static function remessaProgramacao($param =[]) {
        return _22040DAO::remessaProgramacao((object) $param);
    }
    
    /**
     * Retona informações da programação da remessa de consumo
     * @param type $param
     * @return type
     */
    public static function remessaDefeito($param =[]) {
        return _22040DAO::remessaDefeito((object) $param);
    }
	
    /**
     * Verifica se a remessa a ser gerada já existe.
     * @param array $param
     * @return array
     */
    public static function verificarRemessaExiste($param = []) {
        return _22040DAO::verificarRemessaExiste((object) $param);
    }

    public static function atualizarCotaCliente($param = []) {
        return _22040DAO::atualizarCotaCliente(obj_case($param));
    }
}