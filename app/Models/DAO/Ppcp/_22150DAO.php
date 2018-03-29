<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _22150 - Painel de Ferramentas
 */
class _22150DAO {

    
    public static function selectPainel($param, _Conexao $con = null)
    {
        return $con->query("SELECT * FROM SPC_FERRAMENTA_PAINEL");
    }
    
    public static function selectHistorico($param, _Conexao $con = null)
    {
        $sql = "
            SELECT * FROM SPC_FERRAMENTA_HISTORICO(:FERRAMENTA_ID);
        ";
        
        $args = [
            'FERRAMENTA_ID' => $param->FERRAMENTA_ID,
        ];
        
        return $con->query($sql,$args);
        
    }
    
    public static function selectFerramentaProgramada($param, _Conexao $con = null)
    {
        return $con->query("SELECT * FROM SPC_FERRAMENTA_PROGRAMADA");
    }

    public static function selectFerramentaDisponivel($param, _Conexao $con = null)
    {
        $sql = "
            SELECT * FROM SPC_FERRAMENTA_DISPONIVEL(:FERRAMENTA_ID,:DATAHORA_INICIO);
        ";
        
        $args = [
            'FERRAMENTA_ID'   => $param->FERRAMENTA_ID,
            'DATAHORA_INICIO' => $param->DATAHORA_INICIO,
        ];
        
        return $con->query($sql,$args);
    }
	
    
    public static function selectFerramenta($param, _Conexao $con = null)
    {
//        $ferramenta_status  = array_key_exists('STATUS'           , $param) ? "AND STATUS    = $param->STATUS"            : '';
        $ferramenta_id      = array_key_exists('FERRAMENTA_ID'    , $param) ? "AND ID        = $param->FERRAMENTA_ID"     : '';
        $ferramenta_barras  = array_key_exists('FERRAMENTA_BARRAS', $param) ? "AND CODBARRAS = '$param->FERRAMENTA_BARRAS'" : '';
        $ferramenta_status  = array_key_exists('FERRAMENTA_STATUS', $param) ? "AND STATUS    = '$param->FERRAMENTA_STATUS'" : '';
        
        $sql = "
            SELECT *
              FROM TBFERRAMENTARIA
             WHERE 1=1
                /*@STATUS*/
                /*@FERRAMENTA_ID*/
                /*@FERRAMENTA_BARRAS*/
        ";
        
        $args = [
            '@STATUS'            => $ferramenta_status,
            '@FERRAMENTA_ID'     => $ferramenta_id,
            '@FERRAMENTA_BARRAS' => $ferramenta_barras,
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function updateFerramentaProgramacao($param, _Conexao $con = null) {
        
        $sql = "
            EXECUTE PROCEDURE SPU_FERRAMENTA_ALTERAR(:FERRAMENTA_ID, :DEST_FERRAMENTA_ID, :DATAHORA_INICIO, :OPERADOR_ID);
        ";
        
        $args = [
            'FERRAMENTA_ID'      => $param->FERRAMENTA_ID,
            'DEST_FERRAMENTA_ID' => $param->DEST_FERRAMENTA_ID,
            'DATAHORA_INICIO'    => $param->DATAHORA_INICIO,
            'OPERADOR_ID'        => $param->OPERADOR_ID,
        ];
        
        return $con->query($sql,$args);
    }

    public static function updateFerramenta($param, _Conexao $con = null)
    {
        $sql = "
            UPDATE TBFERRAMENTARIA F
               SET F.GP_ID       = :GP_ID,
                   F.UP_ID       = :UP_ID,
                   F.ESTACAO     = :ESTACAO,
                   F.OPERADOR_ID = :OPERADOR_ID,
                   F.SITUACAO    = :SITUACAO,
                   F.TALAO_ID    = :TALAO_ID
             WHERE F.ID          = :FERRAMENTA_ID     
        ";
        
        $args = [
            'GP_ID'         => $param->GP_ID       ,
            'UP_ID'         => $param->UP_ID       ,
            'ESTACAO'       => $param->ESTACAO     ,
            'OPERADOR_ID'   => $param->OPERADOR_ID ,
            'SITUACAO'      => $param->SITUACAO    , 
            'TALAO_ID'      => $param->TALAO_ID    , 
            'FERRAMENTA_ID' => $param->FERRAMENTA_ID,
        ];
        
        return $con->query($sql,$args);
    }
	
	
}