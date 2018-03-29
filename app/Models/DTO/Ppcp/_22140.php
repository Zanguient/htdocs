<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22140DAO;

/**
 * Objeto _22140 - Painel de Programacao
 */
class _22140 extends _22140DAO
{
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }  

    public function getProgramacaoEstacao($dados) {
        return $this->selectProgramacaoEstacao([]);
    }

	public function getProgramacaoGp($dados) {
		return $this->selectProgramacaoGp([]);
	}
    
    public function postProgramacaoEstacao($dados) {
        return $this->spuProgramacaoEstacao((object)[
            'GP_ID_1'               => $dados->GP_ID,
            'UP_ID_1'               => $dados->UP_ID,
            'ESTACAO_1'             => $dados->ESTACAO,
            'GP_ID_2'               => $dados->GP_ID,
            'UP_ID_2'               => $dados->UP_ID,
            'ESTACAO_2'             => $dados->ESTACAO,
            'EM_PRODUCAO'           => $dados->EM_PRODUCAO,
            'DATAHORA_1'            => $dados->DATAHORA,
            'DATAHORA_2'            => $dados->DATAHORA,
            'ORDEM_DATA_REMESSA'    => $dados->ORDEM_DATA_REMESSA
        ]);
    }
    
    public function updateProgramacaoGpCalendario($dados) {
        
        $sql_1 =
        "  
            UPDATE OR INSERT INTO TbCalendario_Esteira (
                ESTEIRA_ID,
                DATA,
                HORARIO,
                MINUTOS
            ) VALUES (
                :GP_ID,
                :DATA,
                :HORARIO,
                NULL
            ) MATCHING (ESTEIRA_ID, DATA);        
        ";
              
        $sql =
        "
            UPDATE TBCALENDARIO_UP
            SET MINUTOS          = NULL,
                HORARIO_DESCANSO = NULL,
                HORARIO          = :HORARIO
            WHERE 
                GP_ID = :GP_ID 
            AND DATA  = :DATA;
        ";
        
        $args = [
            'GP_ID'   => $dados->GP_ID,
            'DATA'    => $dados->DATA,
            'HORARIO' => $dados->HORARIO,
        ];
        
        $this->con->query($sql_1,$args);  
        
        return $this->con->query($sql,$args);        
        
    }
    
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _22140DAO::listar($dados);
	}
//    
//    public static function selectEstacoesPorData($dados,$con) {
//        return _22140DAO::selectEstacoesPorData(obj_case($dados),$con);
//    }
//    
//    public static function selectCalendarioPorData($dados,$con) {
//        return _22140DAO::selectCalendarioPorData(obj_case($dados),$con);
//    }
//    
//    public static function selectTaloesPorData($dados,$con) {
//        return _22140DAO::selectTaloesPorData(obj_case($dados),$con);
//    }
//    
//    public static function selectTaloesPorDataHora($dados,$con) {
//        return _22140DAO::selectTaloesPorDataHora(obj_case($dados),$con);
//    }
//    
//    public static function selectDiasPeriodo($dados,$con) {
//        return _22140DAO::selectDiasPeriodo(obj_case($dados),$con);
//    }
//    
//    public static function selectMinutosDia($dados,$con) {
//        return _22140DAO::selectMinutosDia(obj_case($dados),$con);
//    }

}