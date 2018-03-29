<?php
namespace App\Models\DTO\Helper;

use App\Models\DAO\Helper\PrintZebraDAO;
use App\Models\Conexao\_Conexao;

class PrintZebra
{
	
	/**
	 * Consulta Descrição das impressoras
	 * 
	 * @return array
	 */
	public static function getPrints() {
        return PrintZebraDAO::getPrints();
	}
	
}