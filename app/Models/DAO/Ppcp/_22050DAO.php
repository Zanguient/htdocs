<?php

namespace App\Models\DAO\Ppcp;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22050;
use Illuminate\Support\Facades\Auth;
use Exception;

class _22050DAO
{	
	
	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar($param)
	{
        $res = [];
        
        /**
         * Retorna as operações
         */
        if ( isset($param->RETORNO) && in_array('OPERACAO', $param->RETORNO) ) 
        { 
            $res = $res+['OPERACAO' =>_22050DaoSelect::operacao($param)];
        }
        
        /**
         * Retorna as operações que o operador possui
         */
        if ( isset($param->RETORNO) && in_array('OPERADOR', $param->RETORNO) ) 
        { 
            $res = $res+['OPERADOR' =>_22050DaoSelect::operador($param)];
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

class _22050DaoSelect
{
    /**
     * Consulta de Grupos Produção
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function operacao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $first       = isset($param->FIRST          ) ? "FIRST " . $param->FIRST : '';
        $skip        = isset($param->SKIP           ) ? "SKIP  " . $param->SKIP  : '';        
        $operacao_id = isset($param->OPERACAO_ID    ) ? "AND ID     IN (" . arrayToList($param->OPERACAO_ID    , 999999999) . ")" : '';
        
        $sql =
        "
            SELECT /*@FIRST*/ /*@SKIP*/
                ID,
                GRUPO,
                PARAMETRO,
                VALOR_EXT,
                COMENTARIO,
                MENU

            FROM
                TBCONTROLE_O O

            WHERE
                1=1
                /*@OPERACAO_ID*/
        ";
        
        $args = [
            '@FIRST'        => $first,
            '@SKIP'         => $skip,
            '@OPERACAO_ID'  => $operacao_id,
        ];
        
        return $con->query($sql,$args);
    }
    
    /**
     * Consulta de Grupos Produção
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function operador($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $first       = isset($param->FIRST          ) ? "FIRST " . $param->FIRST : '';
        $skip        = isset($param->SKIP           ) ? "SKIP  " . $param->SKIP  : '';        
        $operador_id = isset($param->OPERADOR_ID    ) ? "AND OPERADOR_ID     IN (" . arrayToList($param->OPERADOR_ID    , 999999999) . ")" : '';
        $up_id		 = isset($param->UP_ID          ) ? "AND UP_ID           IN (" . arrayToList($param->UP_ID			, 999999999) . ")" : '';
        $barras      = isset($param->OPERADOR_BARRAS) ? "AND OPERADOR_BARRAS IN (" . arrayToList($param->OPERADOR_BARRAS, "'#'","'") . ")" : '';
        $familia_id  = isset($param->FAMILIA_ID     ) ? "AND FAMILIA_ID      IN (" . arrayToList($param->FAMILIA_ID     , 999999999) . ")" : '';
        $operacao_id = isset($param->OPERACAO_ID    ) ? "AND OPERACAO_ID     IN (" . arrayToList($param->OPERACAO_ID    , 999999999) . ")" : '';
        $valor_ext   = isset($param->VALOR_EXT      ) ? "AND VALOR_EXT       IN (" . arrayToList($param->VALOR_EXT      , "'#'","'") . ")" : '';
        $status      = isset($param->STATUS         ) ? "AND STATUS          IN (" . arrayToList($param->STATUS         , "'#'","'") . ")" : '';
        
        $sql =
        "
            SELECT /*@FIRST*/ /*@SKIP*/
                OPERADOR_ID,
                OPERADOR_NOME,
                OPERADOR_BARRAS,
                FAMILIA_ID,
                FAMILIA_DESCRICAO,
                OPERACAO_ID,
                OPERACAO_DESCRICAO,
                OPERACAO_COMENTARIO,
                VALOR_EXT,
                STATUS,
                UP_ID
            FROM (
                SELECT
                    X.OPERADOR_ID,
                    X.OPERADOR_NOME,
                    X.OPERADOR_BARRAS,
                    X.FAMILIA_ID,
                    X.FAMILIA_DESCRICAO,
                    X.OPERACAO_ID,
                    X.OPERACAO_DESCRICAO,
                    X.OPERACAO_COMENTARIO,
                    X.VALOR_EXT,
                    X.STATUS,
                    D.SETOR_ID UP_ID
    
                FROM
                    (SELECT
                        C.CODIGO OPERADOR_ID,
                        C.NOME OPERADOR_NOME,
                        C.CODIGO_BARRAS OPERADOR_BARRAS,
                        C.FAMILIA_CODIGO FAMILIA_ID,
                        (SELECT FIRST 1 DESCRICAO FROM TBFAMILIA WHERE CODIGO = C.FAMILIA_CODIGO) FAMILIA_DESCRICAO,
                        B.ID OPERACAO_ID,
                        B.PARAMETRO OPERACAO_DESCRICAO,
                        B.COMENTARIO OPERACAO_COMENTARIO,
                        A.VALOR_EXT,
                        C.STATUS
    
                    FROM
                        TBCONTROLE_OPERADOR A,
                        TBCONTROLE_O B,
                        TBOPERADOR C
                    WHERE B.ID   = A.ID
                    AND C.CODIGO = A.OPERADOR_ID)X
                    LEFT JOIN TBOPERADOR_SETOR D ON (D.OPERADOR_ID = X.OPERADOR_ID))Z
    
                WHERE
                    1=1     
                    /*@OPERADOR_ID*/
                    /*@UP_ID*/
                    /*@BARRAS*/
                    /*@FAMILIA_ID*/
                    /*@OPERACAO_ID*/
                    /*@VALOR_EXT*/
                    /*@STATUS*/
        ";
        
        $args = [
            '@FIRST'        => $first,
            '@SKIP'         => $skip,
            '@OPERADOR_ID'  => $operador_id,
            '@UP_ID'        => $up_id,
            '@BARRAS'       => $barras,
            '@FAMILIA_ID'   => $familia_id,
            '@OPERACAO_ID'  => $operacao_id,
            '@VALOR_EXT'    => $valor_ext,
            '@STATUS'       => $status,
        ];
        
        return $con->query($sql,$args);
    }
}

class _22050DaoInsert
{
    
}

class _22050DaoUpdate
{
    
}

class _22050DaoDelte
{
    
}