<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Admin\_11050;
use Exception;

/**
 * Acesso aos Dados de Modelos de Etiqueta para Impressão
 */
class _11050DAO
{	
	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar($param)
	{
		$con = new _Conexao();
        
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('ETIQUETA', $param->RETORNO) ) {
            $res = $res+['ETIQUETA' => _11050DaoSelect::etiqueta($param)];
        }
	
		return (object)$res;
	}
	
	/**
	 * Similar ao SHOW (EXIBIR) do LARAVEL
	 * Retorna dados do objeto na base de dados.
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id)
	{
		$con = new _Conexao();
	
		//
		
		return array();	
	} 
    
	/**
	 * Similar ao CREATE (CRIAR) do CRUD
	 * @param _11050 $obj
	 */
	public static function gravar(_11050 $obj)
	{
		$con = new _Conexao();
		try
		{
			//
	
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
	
	/**
	 * Similar ao UPDATE (ATUALIZAR) do CRUD
	 * Atualiza dados do objeto na base de dados.
	 * @param _11050 $obj
	 */
	public static function alterar(_11050 $obj)
	{
		$con = new _Conexao();
		try {
			 
			//
	
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
	
	/**
	 * Similar ao DESTROY (EXCLUIR) do CRUD
	 * Exclui dados do objeto na base de dados.
	 * @param int $id
	 */
	public static function excluir($id)
	{
		$con = new _Conexao();
		try {
			
			//
				
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}	
}

class _11050DaoSelect
{
    /**
     * Consulta projeção de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function etiqueta($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
   
        $id     = array_key_exists('ID'    , $param) ? "AND ID       IN (" . arrayToList($param->ID, 9999999999999) . ")" : '';
        $filtro = array_key_exists('FILTRO', $param) ? "AND FILTRO LIKE '%" . str_replace(' ', '%', $param->FILTRO) . "%'" : '';
                
        $sql =
        "
            SELECT
                ID,
                DESCRICAO,
                SCRIPT,
                TIPO,
                MULTIPLICADOR
            FROM

                (SELECT
                    ID,
                    DESCRICAO,
                    SCRIPT,
                    TIPO,
                    MULTIPLICADOR,
                   (ID || ' ' ||
                    DESCRICAO || ' ' ||
                    TIPO) FILTRO

                FROM
                    TBETIQUETAS)X

            WHERE
                1=1
                /*@ID*/
                /*@FILTRO*/
        ";

        $args = [
            '@ID'     => $id,
            '@FILTRO' => $filtro
        ];

        return $con->query($sql,$args);
        
    }
}

class _11050DaoInsert
{
    /**
     * Registra o histórico da programação
     * @param type $param
     * @param _Conexao $con
     */
    public static function programacaoHistorico($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            
        ";
        
        $args = [
            
        ];
		
        $con->execute($sql, $args);
    }
}

class _11050DaoUpdate
{
    /**
     * Bloqueia a Estação (à coloca em uso)
     * @param type $param
     * @param _Conexao $con
     */
    public static function estacaoBloqueio($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        
        $sql =
        "

        ";
        
        $args = [
            
        ];

        $con->execute($sql, $args);
    }
}

class _11050DaoDelete
{
    
}
