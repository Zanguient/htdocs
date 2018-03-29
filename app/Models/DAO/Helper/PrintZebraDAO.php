<?php

namespace App\Models\DAO\Helper;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;

class PrintZebraDAO
{
	/**
	 * Gerar id do arquivo.
	 * 
	 * @return array
	 */
	public static function getPrints() {
        
        $con = new _Conexao();
        
        if( !empty(\Auth::user()->USUARIO) ) {
            
            $codigo = Auth::user()->CODIGO; 

            $sql = "SELECT I.ID, I.DESCRICAO, I.CODIGO,iif( coalesce(u.impessora_id,0) = I.id, 1,0 ) as FLAG FROM TBIMPRESSORAS I, tbusuario u where u.codigo = " . $codigo;

            return $con->query($sql);
            
        }else{
            
            return [];
            
        }
            
         
		
	}
	
}
