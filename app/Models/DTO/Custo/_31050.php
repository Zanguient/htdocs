<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31050DAO;

/**
 * Objeto _31050 - Registro de Producao - Div. Bojo Colante
 */
class _31050
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
         
    public function selectRateioTipo($param) {
        
        $sql = "
            SELECT
                FN_LPAD(ID,2,0) ID,
                DESCRICAO DESCRICAO,
                DATA_INICIAL,
                DATA_FINAL,
                UNIDADEMEDIDA_ID UM_ID,
                (SELECT FIRST 1 SIGLA
                   FROM TBUNIDADEMEDIDA U
                  WHERE U.ID = T.UNIDADEMEDIDA_ID) UM,
                (SELECT FIRST 1 DESCRICAO
                   FROM TBUNIDADEMEDIDA U
                  WHERE U.ID = T.UNIDADEMEDIDA_ID) UM_DESCRICAO


            FROM
                TBRATEAMENTO_TIPO T
        ";
        
        $args = [
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
        
    public function updateInsertRateioTipo($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBRATEAMENTO_TIPO');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBRATEAMENTO_TIPO (
                ID, 
                DESCRICAO,
                DATA_INICIAL,
                DATA_FINAL,
                UNIDADEMEDIDA_ID
            ) VALUES (
                :ID, 
                :DESCRICAO,
                :DATA_INICIAL,
                :DATA_FINAL,
                :UM_ID
            ) MATCHING (
                ID
            );
        ";
        
        $args = [
            'ID'            => $param->ID,
            'DESCRICAO'     => $param->DESCRICAO,
            'DATA_INICIAL'  => $param->DATA_INICIAL,
            'DATA_FINAL'    => $param->DATA_FINAL, 
            'UM_ID'         => $param->UM_ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteRateioTipo($param) {
        
        $sql = "
            DELETE FROM TBRATEAMENTO_TIPO WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
}