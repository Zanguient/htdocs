<?php

namespace App\Models\DAO\Ppcp;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22031;
use Illuminate\Support\Facades\Auth;
use Exception;

class _22031DAO
{	
	
	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar($param)
	{
		$res = [];
		
        if (isset($param->RETORNO) && in_array('UP', $param->RETORNO)) {
			
            $res = $res + ['UP' => _22031DaoSelect::up($param)]; 
        } 
        else if ( isset($param->RETORNO) && in_array('UP_ESTACAO' , $param->RETORNO) ) {
            
            $res = $res + ['UP_ESTACAO'  => _22031DaoSelect::upEstacao($param)];
        }
        
        return (object) $res;
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
	 * @param _13050 $obj
	 */
	public static function gravar(_13050 $obj)
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
	 * @param _13050 $obj
	 */
	public static function alterar(_13050 $obj)
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

class _22031DaoSelect
{
    /**
     * Consulta de Grupos Produção
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function up($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $status  = isset($param->STATUS ) ? "AND U.STATUS     IN (" . Helpers::arrayToList($param->STATUS , 999999) . ")" : '';
        $familia = isset($param->FAMILIA) ? "AND U.FAMILIA_ID IN (" . Helpers::arrayToList($param->FAMILIA, 999999) . ")" : '';
        
        $sql =
        "
            SELECT
                LPAD(U.ID,3,0)ID,
                U.DESCRICAO

            FROM
                TBUP U

            WHERE
                1=1
            /*@STATUS*/
            /*@FAMILIA*/          
        ";
        
        $args = [
            '@STATUS'  => $status,
            '@FAMILIA' => $familia
        ];
        
        return $con->query($sql,$args);
    }
}

class _22031DaoInsert
{
    
}

class _22031DaoUpdate
{
    
}

class _22031DaoDelte
{
    
}