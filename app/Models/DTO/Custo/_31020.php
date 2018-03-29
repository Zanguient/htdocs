<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31020DAO;

/**
 * Objeto _31020 - Registro de Producao - Div. Bojo Colante
 */
class _31020
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
        
    public function selectRateioCcusto($param) {
        
        $sql = "
            SELECT
                FN_CCUSTO_DESCRICAO(CC.CODIGO) CCUSTO_DESCRICAO,
                FN_CCUSTO_MASK(CC.CODIGO) CCUSTO_MASK,
                C.ID,
                CC.CODIGO CCUSTO,
                C.PERC_RATEAMENTO,
                C.ABRANGENCIA,
                C.VALOR_ORIGEM,
                FN_LPAD(C.TIPO_ID,2,0) TIPO_ID,
                FN_DESCRICAO('TBRATEAMENTO_TIPO',C.TIPO_ID) TIPO_DESCRICAO,
                C.RATEAMENTO_GRUPO,
                C.ORDEM,

                CASE C.VALOR_ORIGEM
                WHEN 1 THEN '01 - SALÁRIOS'
                WHEN 2 THEN '02 - OUTROS'
                END VALOR_ORIGEM_DESCRICAO,

                CASE C.RATEAMENTO_GRUPO
                WHEN 1 THEN '01 - CUSTO DE MÃO DE OBRA INDIRETA'
                ELSE 'INDEFINIDO' END RATEAMENTO_GRUPO_DESCRICAO,

                IIF(POSITION('*',C.CCUSTO) > 1,1,0) HIERARQUIA

            FROM
                TBRATEAMENTO_CCUSTO C,
                VWCENTRO_DE_CUSTO CC

            WHERE TRUE
            AND CC.CODIGO LIKE REPLACE(C.CCUSTO,'*','')
        ";
        
        $args = [
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function selectCCustoAbsorcao($param) {
        
        $sql = "
            SELECT FN_CCUSTO_MASK(A.CCUSTO) CCUSTO_MASK,
                   TRIM('A'||A.CCUSTO) CCUSTO_MASKA,
                   FN_CCUSTO_DESCRICAO(A.CCUSTO) CCUSTO_DESCRICAO,
                   A.*
              FROM TBRATEAMENTO_ABSORCAO A
             WHERE A.CCUSTO_ABSORCAO = :CCUSTO
        ";
        
        $args = [
            'CCUSTO' => $param->CCUSTO
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
        
    public function updateInsertRateioCcusto($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBRATEAMENTO_CCUSTO');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBRATEAMENTO_CCUSTO (
                ID,
                CCUSTO,
                TIPO_ID,
                ABRANGENCIA,
                VALOR_ORIGEM,
                RATEAMENTO_GRUPO,
                ORDEM
            ) VALUES (
                :ID,
                :CCUSTO,
                :TIPO_ID,
                :ABRANGENCIA,
                :VALOR_ORIGEM,
                :RATEAMENTO_GRUPO,
                :ORDEM
            ) MATCHING (
                ID
            );
        ";
        
        $args = [
            'ID'                => $param->ID,
            'CCUSTO'            => $param->CCUSTO,
            'TIPO_ID'           => $param->TIPO_ID,
            'ABRANGENCIA'       => $param->ABRANGENCIA,
            'VALOR_ORIGEM'      => $param->VALOR_ORIGEM,
            'RATEAMENTO_GRUPO'  => $param->RATEAMENTO_GRUPO,
            'ORDEM'             => $param->ORDEM
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteRateioCcusto($param) {
        
        $sql = "
            DELETE FROM TBRATEAMENTO_CCUSTO WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function updateInsertCCustoAbsorcao($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBRATEAMENTO_ABSORCAO');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBRATEAMENTO_ABSORCAO (
                ID,
                CCUSTO,
                CCUSTO_ABSORCAO,
                PERC_ABSORCAO,
                RATEAMENTO_GRUPO
            ) VALUES (
                :ID,
                :CCUSTO,
                :CCUSTO_ABSORCAO,
                :PERC_ABSORCAO,
                :RATEAMENTO_GRUPO
            ) MATCHING (
                ID
            );
        ";
        
        $args = [
            'ID'                => $param->ID,
            'CCUSTO'            => $param->CCUSTO,
            'CCUSTO_ABSORCAO'   => $param->CCUSTO_ABSORCAO,
            'PERC_ABSORCAO'     => $param->PERC_ABSORCAO,
            'RATEAMENTO_GRUPO'  => $param->RATEAMENTO_GRUPO,
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteCCustoAbsorcao($param) {
        
        $sql = "
            DELETE FROM TBRATEAMENTO_ABSORCAO WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
}