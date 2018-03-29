<?php
/**
 * DTO do objeto de Historico
 * 
 * Os metodos dete objeto serverm apara gerar uma consulta apartir de parametros
 * cada parametro gera uma caracteristica da consulta
 * 
 * @version 1.0
 * @package Helper
 * @author Anderson Sousa <anderson@delfa.com.br>
 * @example Classe generica
 */

namespace App\Models\DTO\Helper;

use App\Models\DAO\Helper\HistoricoDAO;

/**
 * Histórico
 */
class Historico
{
    
    
    /**
    * Retorna o historico apartir de uma tebela e uma id
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
     * @param string $tabela tabela do registro que deseja consultar o historico
     * @param int $id ID do registro que deseja consultar o historico
    * @return array
    * @api
    */
	public static function GetHistorico($tabela, $id)
    {
        return HistoricoDAO::GetHistorico($tabela, $id);
	}
    
	public static function getHistorico2($param)
    {
        return HistoricoDAO::getHistorico2($param);
	}

    /**
    * Grava o registro de historico apartir de uma tebela e uma id
    * 
    * @version 1.0
    * @author Anderson Sousa <anderson@delfa.com.br>
     * @param string $tabela tabela do registro que deseja registrar no historico
     * @param int $id ID do registro que deseja registrar no historico
     * @param string $descricao Descrição do registro que deseja registrar no historico
     * @param _Conexao $con objeto PDO
    * @return array
    * @api
    */
    public static function setHistorico($tabela, $id, $descricao, $con = null) {
        HistoricoDAO::setHistorico($tabela, $id, $descricao, $con);
	}


}