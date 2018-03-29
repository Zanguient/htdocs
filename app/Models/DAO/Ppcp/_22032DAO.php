<?php

namespace App\Models\DAO\Ppcp;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22032;
use Illuminate\Support\Facades\Auth;
use Exception;

class _22032DAO
{	
	
	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar($param)
	{
		return _22032DaoSelect::et($param);
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

class _22032DaoSelect
{
    /**
     * Consulta de Grupos Produção
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function et($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $status     = isset($param->STATUS    ) ? "AND S.STATUS     IN (" . Helpers::arrayToList($param->STATUS    , 999999) . ")" : '';
        $up         = isset($param->UP        ) ? "AND S.UP_ID      IN (" . Helpers::arrayToList($param->UP        , 999999) . ")" : '';
        $perfil_sku = isset($param->PERFIL_SKU) ? "AND S.PERFIL_SKU IN (" . Helpers::arrayToList($param->PERFIL_SKU, 999999) . ")" : '';
        
        $sql =
        "
            SELECT
                LPAD(S.ID,3,0)ID,
                LPAD(S.UP_ID,3,0)UP_ID,
                S.DESCRICAO, 
                S.PERFIL_SKU

            FROM
                TBSUB_UP S

            WHERE
                1=1
            /*@STATUS*/
            /*@UP*/   
            /*@PERFIL_SKU*/
        ";
        
        $args = [
            '@STATUS'     => $status,
            '@UP'         => $up,
            '@PERFIL_SKU' => $perfil_sku
        ];
        
        return $con->query($sql,$args);
    }
}

class _22032DaoInsert
{
    
}

class _22032DaoUpdate
{
    
}

class _22032DaoDelte
{
    
}