<?php

namespace App\Models\DAO\Helper;

use App\Models\Conexao\_Conexao;
use Exception;

class ConsultaAllDAO
{	
    
	/**
	 * Verifica se um valor é valido.
	 *
	 * @param [string,inteiro] $valor
	 * @return array
	 */
    public static function validarValor($valor){
        
        if(!isset($valor)){
            return false; 
        }else{
            if($valor == ''){
                return false;
            }else{
                return true;
            }
        }
    }
    
	/**
	 * consulta all.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function ConsultaAll($filtro,$obj,$campos,$condicao,$condicao_campo,$imputs) {
        $con = new _Conexao();
        
		//include 'include/'.$obj.'.php';
		include ('../app/Models/DAO/'.$obj.'.php');
        
        return $ret;
	}
    
}