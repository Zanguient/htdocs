<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22150DAO;

/**
 * Objeto _22150 - Painel de Ferramentas
 */
class _22150
{
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _22150DAO::getChecList($dados);
	}
	
    public static function selectPainel($dados,$con) {
        return _22150DAO::selectPainel(obj_case($dados),$con);
    }
	
    public static function selectHistorico($dados,$con) {
        return _22150DAO::selectHistorico(obj_case($dados),$con);
    }
	
    public static function selectFerramentaProgramada($dados,$con) {
        return _22150DAO::selectFerramentaProgramada(obj_case($dados),$con);
    }
	
    public static function selectFerramenta($dados,$con) {
        return _22150DAO::selectFerramenta(obj_case($dados),$con);
    }
	
    public static function selectFerramentaDisponivel($dados,$con) {
        return _22150DAO::selectFerramentaDisponivel(obj_case($dados),$con);
    }
	
    public static function updateFerramentaProgramacao($dados,$con) {
        return _22150DAO::updateFerramentaProgramacao(obj_case($dados),$con);
    }
	
    public static function updateFerramenta($dados,$con) {
        return _22150DAO::updateFerramenta(obj_case($dados),$con);
    }

}