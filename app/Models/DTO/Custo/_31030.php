<?php

namespace App\Models\DTO\Custo;

use App\Models\DAO\Custo\_31030DAO;

/**
 * Objeto _31030 - Registro de Producao - Div. Bojo Colante
 */
class _31030
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
        
    public function selectRateioCContabil($param) {
        
        $sql = "
            SELECT
                CC.DESCRICAO CCONTABIL_DESCRICAO,
                FN_CCONTABIL_MASK(CC.CONTA) CCONTABIL_MASK,
                CC.CONTA CCONTABIL,
                C.ID,
                C.CONTA,
                C.VALOR_ORIGEM,    
                FN_LPAD(C.TIPO_ID,2,0) TIPO_ID,
                FN_DESCRICAO('TBRATEAMENTO_TIPO',C.TIPO_ID) TIPO_DESCRICAO,
                C.REGRA_RATEAMENTO,
                CASE C.REGRA_RATEAMENTO
                WHEN 1 THEN '01 - DEFINIDO PELA CONSULTA(ORIGEM)'
                WHEN 2 THEN '02 - FIXO (TBRATEAMENTO_CONTABIL_CCUSTO)'
                WHEN 3 THEN '03 - COLABORADOR'
                WHEN 4 THEN '04 - COLABORADOR/TRANSPORTE'
                WHEN 5 THEN '05 - COLABORADOR/REFEICAO'
                WHEN 6 THEN '06 - AREA'
                WHEN 7 THEN '07 - SETORES BALANCIM HIDRAULICO'
                END REGRA_RATEAMENTO_DESCRICAO,

                CASE C.VALOR_ORIGEM
                WHEN 1 THEN '01 - LANCAMENTO DE ESTOQUE'
                WHEN 2 THEN '02 - LANCAMENTO CONTABIL'
                WHEN 3 THEN '03 - INDEFINIDO'
                WHEN 4 THEN '04 - LANCAMENTO CONTABIL SEM CONSIDERAR O CENTRO DE CUSTO'
                WHEN 5 THEN '05 - DEPRECIACAO'
                END VALOR_ORIGEM_DESCRICAO,
                                   
                C.RATEAMENTO_GRUPO,
                CASE C.RATEAMENTO_GRUPO
                WHEN 1 THEN '01 - CUSTO DE MÃO DE OBRA INDIRETA'
                ELSE 'INDEFINIDO' END RATEAMENTO_GRUPO_DESCRICAO

            FROM
                TBRATEAMENTO_CONTA_CONTABIL C,
                TBCONTACONTABIL CC

            WHERE TRUE
            AND CC.CONTA = C.CONTA
        ";
        
        $args = [
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
        
    public function updateInsertRateioCContabil($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBRATEAMENTO_CON_CONT');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBRATEAMENTO_CONTA_CONTABIL (
                ID, 
                CONTA, 
                VALOR_ORIGEM, 
                REGRA_RATEAMENTO, 
                RATEAMENTO_GRUPO,
                TIPO_ID
            ) VALUES (
                :ID, 
                :CCONTABIL, 
                :VALOR_ORIGEM, 
                :REGRA_RATEAMENTO, 
                :RATEAMENTO_GRUPO,
                :TIPO_ID
            ) MATCHING (
                ID
            );
        ";
        
        $args = [
            'ID'                => $param->ID,
            'CCONTABIL'         => $param->CCONTABIL,
            'VALOR_ORIGEM'      => $param->VALOR_ORIGEM,
            'REGRA_RATEAMENTO'  => $param->REGRA_RATEAMENTO,
            'RATEAMENTO_GRUPO'  => $param->RATEAMENTO_GRUPO,
            'TIPO_ID'           => setDefValue($param->TIPO_ID, null)
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteRateioCContabil($param) {
        
        $sql = "
            DELETE FROM TBRATEAMENTO_CONTA_CONTABIL WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
}