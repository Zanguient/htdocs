<?php

namespace App\Models\DTO\Financeiro;

use App\Models\DAO\Financeiro\_20120DAO;

/**
 * Objeto _20120 - Registro de Producao - Div. Bojo Colante
 */
class _20120
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      

    public function selectUnidadeMedida($param) {

        $sql = "
            SELECT    
                ID,
                DESCRICAO,
                SIGLA,
                TRIM(PODE_FRACIONAR) PODE_FRACIONAR,
                TRIM(CASE PODE_FRACIONAR
                WHEN 'S' THEN 'SIM'
                WHEN 'N' THEN 'NÃO'
                END) PODE_FRACIONAR_DESCRICAO

            FROM
                TBUNIDADEMEDIDA U
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
                :UNIDADEMEDIDA_ID
            ) MATCHING (
                ID
            );
        ";
        
        $args = [
            'ID'               => $param->ID,
            'DESCRICAO'        => $param->DESCRICAO,
            'DATA_INICIAL'     => $param->DATA_INICIAL,
            'DATA_FINAL'       => $param->DATA_FINAL,
            'UNIDADEMEDIDA_ID' => $param->UNIDADEMEDIDA_ID,
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