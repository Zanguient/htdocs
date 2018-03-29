<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31060DAO;

/**
 * Objeto _31060 - Registro de Producao - Div. Bojo Colante
 */
class _31060
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
         
    public function selectRegra($param) {
        
        $sql = "
            SELECT
                ID,
                FN_LPAD(SEQUENCIA,2,0) SEQUENCIA,
                FN_LPAD(C.FAMILIA_PRODUCAO,3,0) FAMILIA_PRODUCAO,
                FN_DESCRICAO('TBFAMILIA',C.FAMILIA_PRODUCAO) FAMILIA_PRODUCAO_DESCRICAO,
                FN_LPAD(C.FAMILIA_ID,3,0) FAMILIA_ID,
                FN_DESCRICAO('TBFAMILIA',C.FAMILIA_ID) FAMILIA_DESCRICAO,
                FN_LPAD(NULLIF(C.GP_ID,'*'),3,0) GP_ID,
                IIF(C.GP_ID='*',1,0) GP_TODOS,
                FN_DESCRICAO('TBGP',NULLIF(C.GP_ID,'*')) GP_DESCRICAO,
                TRIM(NULLIF(PERFIL_UP,'*')) PERFIL_UP,
                IIF(PERFIL_UP='*',1,0) PERFIL_UP_TODOS,
                (SELECT FIRST 1 P.DESCRICAO
                   FROM TBPERFIL P
                  WHERE P.TABELA = 'UP'
                    AND P.ID     = C.PERFIL_UP) PERFIL_UP_DESCRICAO,
                UP_PADRAO1,
                FN_DESCRICAO('TBUP',C.UP_PADRAO1) UP_PADRAO1_DESCRICAO,
                UP_PADRAO2,
                FN_DESCRICAO('TBUP',C.UP_PADRAO2) UP_PADRAO2_DESCRICAO,
                TRIM(CALCULO_REBOBINAMENTO) CALCULO_REBOBINAMENTO,
                TRIM(CASE CALCULO_REBOBINAMENTO
                WHEN 0 THEN 'NÃO'
                WHEN 1 THEN 'SIM'
                END) CALCULO_REBOBINAMENTO_DESCRICAO,
                TRIM(CALCULO_CONFORMACAO) CALCULO_CONFORMACAO,
                TRIM(CASE CALCULO_CONFORMACAO
                WHEN 0 THEN 'NÃO'
                WHEN 1 THEN 'SIM'
                END) CALCULO_CONFORMACAO_DESCRICAO,
                REPLACE(C.CCUSTO,'*','') CCUSTO,
                FN_CCUSTO_MASK(REPLACE(C.CCUSTO,'*','')) CCUSTO_MASK,
                FN_CCUSTO_DESCRICAO(REPLACE(C.CCUSTO,'*','')) CCUSTO_DESCRICAO,
                IIF(POSITION('*',C.CCUSTO) > 0,1,0) CCUSTO_HIERARQUIA,
                FATOR,
                STATUS,
                REMESSAS_DEFEITO

            FROM
                TBREGRA_CALCULO_CUSTO C
        ";
        
        $args = [
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
        
    public function updateInsertRegra($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBREGRA_CALCULO_CUSTO');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBREGRA_CALCULO_CUSTO (
                ID,
                FAMILIA_PRODUCAO,
                SEQUENCIA,
                FAMILIA_ID,
                GP_ID,
                PERFIL_UP,
                UP_PADRAO1,
                UP_PADRAO2,
                CALCULO_REBOBINAMENTO,
                CALCULO_CONFORMACAO,
                CCUSTO,
                FATOR,
                STATUS,
                REMESSAS_DEFEITO
            ) VALUES (
                :ID,
                :FAMILIA_PRODUCAO,
                :SEQUENCIA,
                :FAMILIA_ID,
                :GP_ID,
                :PERFIL_UP,
                :UP_PADRAO1,
                :UP_PADRAO2,
                :CALCULO_REBOBINAMENTO,
                :CALCULO_CONFORMACAO,
                :CCUSTO,
                :FATOR,
                :STATUS,
                :REMESSAS_DEFEITO
            ) MATCHING ( ID );
        ";
        
        $args = [
            'ID'                    => $param->ID,                  
            'FAMILIA_PRODUCAO'      => $param->FAMILIA_PRODUCAO,    
            'SEQUENCIA'             => $param->SEQUENCIA,           
            'FAMILIA_ID'            => $param->FAMILIA_ID,          
            'GP_ID'                 => $param->GP_ID,               
            'PERFIL_UP'             => $param->PERFIL_UP,           
            'UP_PADRAO1'            => $param->UP_PADRAO1,          
            'UP_PADRAO2'            => $param->UP_PADRAO2,          
            'CALCULO_REBOBINAMENTO' => $param->CALCULO_REBOBINAMENTO,
            'CALCULO_CONFORMACAO'   => $param->CALCULO_CONFORMACAO, 
            'CCUSTO'                => $param->CCUSTO,              
            'FATOR'                 => $param->FATOR,               
            'STATUS'                => $param->STATUS,              
            'REMESSAS_DEFEITO'      => $param->REMESSAS_DEFEITO,    
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteRegra($param) {
        
        $sql = "
            DELETE FROM TBREGRA_CALCULO_CUSTO WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
}