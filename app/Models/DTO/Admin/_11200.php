<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11200DAO;

/**
 * Objeto _11200 - Registro de Producao - Div. Bojo Colante
 */
class _11200
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
    

    
    public function selectPerfil($param1) {
        
        $sql = "
            SELECT
                P.TABELA PERFIL_TABELA,
                CAST(P.ID AS VARCHAR(10)) PERFIL_TABELA_ID,
                P.DESCRICAO PERFIL_DESCRICAO

            FROM
                TBPERFIL P
            WHERE TRUE
            /*@FILTRO*/
            /*@TABELA*/
            /*@TABELA_ID*/
        ";
        
        $param = (object)[];
        
        if ( isset($param1->FILTRO) && trim($param1->FILTRO) != '' ) {
            $param->FILTRO = " LIKE UPPER('%$param1->FILTRO%')";
        }

        if ( isset($param1->TABELA) && trim($param1->TABELA) != '' ) {
            $param->TABELA = " = '" . $param1->TABELA . "'";
        }

        if ( isset($param1->TABELA_ID) ) {
            $param->TABELA_ID = " = '" . $param1->TABELA_ID . "'";
        }
         
        $filtro    = array_key_exists('FILTRO'   , $param) ? "AND UPPER(P.DESCRICAO) $param->FILTRO" : '';        
        $tabela    = array_key_exists('TABELA'   , $param) ? "AND P.TABELA           $param->TABELA   " : '';
        $tabela_id = array_key_exists('TABELA_ID', $param) ? "AND P.ID               $param->TABELA_ID" : '';
        
        $args = [
            '@FILTRO'    => $filtro,
            '@TABELA'    => $tabela,
            '@TABELA_ID' => $tabela_id,
        ];    
        
        return $this->con->query($sql,$args);
    } 
    

}