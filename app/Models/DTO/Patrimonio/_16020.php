<?php

namespace App\Models\DTO\Patrimonio;

use App\Models\DAO\Patrimonio\_16020DAO;

/**
 * Objeto _16020 - Registro de Producao - Div. Bojo Colante
 */
class _16020
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
    

    
    public function selectTipo($param = null) {
        
        $sql = "
            SELECT
                T.ID,
                T.DESCRICAO,
                T.VIDA_UTIL,
                T.TAXA_DEPRECIACAO,
                TRIM(T.TIPO_GASTO) TIPO_GASTO,
                T.TAXA_DEPRECIACAO * 100 TAXA_DEPRECIACAO_CALC,
                T.CCONTABIL,
                FN_CCONTABIL_MASK(T.CCONTABIL) CCONTABIL_MASK,
                FN_DESCRICAO('TBCONTACONTABIL',T.CCONTABIL,NULL,'CONTA')CCONTABIL_DESCRICAO,
                T.CCONTABIL_DEBITO,
                FN_CCONTABIL_MASK(T.CCONTABIL_DEBITO) CCONTABIL_DEBITO_MASK,
                FN_DESCRICAO('TBCONTACONTABIL',T.CCONTABIL_DEBITO,NULL,'CONTA')CCONTABIL_DEBITO_DESCRICAO

            FROM
                TBIMOBILIZADO_TIPO T
            WHERE TRUE
            /*@ID*/
        ";
  
        
        $args = [
            '@ID' =>  array_key_exists('ID', $param) ? "AND I.ID = $param->ID" : ''
        ];    
        
        return $this->con->query($sql,$args);
    } 
            
    public function updateInsertTipo($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBIMOBILIZADO_TIPO');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBIMOBILIZADO_TIPO (
                ID, 
                DESCRICAO,
                VIDA_UTIL,
                TAXA_DEPRECIACAO,
                TIPO_GASTO,
                CCONTABIL,
                CCONTABIL_DEBITO
            ) VALUES (
                :ID, 
                :DESCRICAO,
                :VIDA_UTIL,
                :TAXA_DEPRECIACAO,
                :TIPO_GASTO,
                :CCONTABIL,
                :CCONTABIL_DEBITO
            ) MATCHING (
                ID
            );
        ";
        
        $args = [
            'ID'               => $param->ID,
            'DESCRICAO'        => $param->DESCRICAO,
            'VIDA_UTIL'        => $param->VIDA_UTIL,
            'TAXA_DEPRECIACAO' => $param->TAXA_DEPRECIACAO, 
            'TIPO_GASTO'       => $param->TIPO_GASTO,
            'CCONTABIL'        => $param->CCONTABIL,
            'CCONTABIL_DEBITO' => $param->CCONTABIL_DEBITO
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteTipo($param) {
        
        $sql = "
            DELETE FROM TBIMOBILIZADO_TIPO WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
}