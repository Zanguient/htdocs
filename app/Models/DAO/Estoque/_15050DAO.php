<?php

namespace App\Models\DAO\Estoque;

use App\Models\DTO\Estoque\_15050;
use App\Models\Conexao\_Conexao;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto 15050 - Baixa de Estoque.
 */
class _15050DAO {
	
	/**
     * Select da página inicial.
     * 
     * @return array
     */
    public static function listar() {
		
		$con = new _Conexao();
			
		$sql = "";
		
		$args = array(
			':USU_ID' => Auth::user()->CODIGO
		);
		
		return $con->query($sql, $args);
    }
	
}
