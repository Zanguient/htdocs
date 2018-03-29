<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31040DAO;

/**
 * Objeto _31040 - Registro de Producao - Div. Bojo Colante
 */
class _31040
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
        
    public function selectRateioTipoDetalhe($param) {
        
        $sql = "
            SELECT
                D.ID,
                TRIM('A'||D.CCUSTO) CCUSTOA,
                D.CCUSTO,
                FN_CCUSTO_MASK(D.CCUSTO) CCUSTO_MASK,
                FN_CCUSTO_DESCRICAO(D.CCUSTO) CCUSTO_DESCRICAO,
                FN_LPAD(D.TIPO_ID,2,0) TIPO_ID,
                T.DESCRICAO TIPO_DESCRICAO,
                D.VALOR,
                (SELECT FIRST 1 U.SIGLA
                  FROM TBUNIDADEMEDIDA U
                 WHERE U.ID = T.UNIDADEMEDIDA_ID) UM,
                (SELECT FIRST 1 U.DESCRICAO
                  FROM TBUNIDADEMEDIDA U
                 WHERE U.ID = T.UNIDADEMEDIDA_ID) UM_DESCRICAO

            FROM
                TBRATEAMENTO_TIPO_DETALHE D,
                TBRATEAMENTO_TIPO T

            WHERE
                T.ID = D.TIPO_ID
        ";
        
        $args = [
        ];    
        
        return $this->con->query($sql,$args);
    } 
       
    public function selectRateioTipo($param) {
        
        $sql = "
            SELECT
                FN_LPAD(T.ID,2,0) TIPO_ID,
                T.DESCRICAO TIPO_DESCRICAO

            FROM
                TBRATEAMENTO_TIPO T
            WHERE
                T.ID < 1000
        ";
        
        $args = [
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
        
    public function updateInsertRateioTipo($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBRATEAMENTO_TIPO_DETALHE');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBRATEAMENTO_TIPO_DETALHE (
                ID, 
                CCUSTO,
                TIPO_ID,
                VALOR
            ) VALUES (
                :ID, 
                :CCUSTO,
                :TIPO_ID,
                :VALOR
            ) MATCHING (
                ID
            );
        ";
        
        $args = [
            'ID'      => $param->ID,
            'CCUSTO'  => $param->CCUSTO,
            'TIPO_ID' => $param->TIPO_ID,
            'VALOR'   => $param->VALOR
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteRateioTipo($param) {
        
        $sql = "
            DELETE FROM TBRATEAMENTO_TIPO_DETALHE WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
}