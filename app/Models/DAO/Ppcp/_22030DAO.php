<?php

namespace App\Models\DAO\Ppcp;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use Exception;

class _22030DAO
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
         
        if ( isset($param->RETORNO) && in_array('GP'           , $param->RETORNO) ) { $res = $res+['GP'            =>_22030DaoSelect::gp         ($param)]; } else
        if ( isset($param->RETORNO) && in_array('UP'           , $param->RETORNO) ) { $res = $res+['UP'            =>_22030DaoSelect::up         ($param)]; } else            
        if ( isset($param->RETORNO) && in_array('ESTACAO'      , $param->RETORNO) ) { $res = $res+['ESTACAO'       =>_22030DaoSelect::estacao    ($param)]; } else            
        if ( isset($param->RETORNO) && in_array('GP_UP_ESTACAO', $param->RETORNO) ) { $res = $res+['GP_UP_ESTACAO' =>_22030DaoSelect::gpUpEstacao($param)]; } else            
        if ( isset($param->RETORNO) && in_array('CONFORMACAO', $param->RETORNO)   ) { $res = $res+['ESTACAO'       =>_22030DaoSelect::conformacao($param)]; }  
	
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

class _22030DaoSelect
{
    /**
     * Consulta de Grupos Produção
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function gp($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $filtro  = isset($param->FILTRO ) ? "AND FILTRO   LIKE '%" . str_replace(' ','%', $param->FILTRO)          ."%'" : '';
        $status  = isset($param->STATUS ) ? "AND STATUS     IN  (" . Helpers::arrayToList($param->STATUS , 999999) . ")" : '';
        $familia = isset($param->FAMILIA) ? "AND FAMILIA_ID IN  (" . Helpers::arrayToList($param->FAMILIA, 999999) . ")" : '';
        $order   = isset($param->ORDER  ) ? "ORDER BY            " . Helpers::arrayToList($param->ORDER  , 'DESCRICAO')  : '';
        
        
        $sql =
        "
            SELECT
                ID,
                DESCRICAO,
                FAMILIA_ID,
                FAMILIA_DESCRICAO,
                FAMILIA_UM_ALTERNATIVA,
                PERFIL,
                CONTROLE_TALAO,
                STATUS,
                DIAS,
                VER_PECA_DISPONIVEL,
                TRIM(VER_PARES) VER_PARES,
                HABILITA_QUEBRA_TALAO_SKU

            FROM
               (SELECT 
                    X.*,
                   (ID || ' ' || DESCRICAO)FILTRO

                FROM
                   (SELECT
                        LPAD(G.ID,3,0)ID,
                        G.DESCRICAO,
                        LPAD(G.FAMILIA_ID, 3, '0') FAMILIA_ID,
                        F.DESCRICAO FAMILIA_DESCRICAO,
                        F.UNIDADEMEDIDA_ALTERNATIVO FAMILIA_UM_ALTERNATIVA,
                        G.PERFIL,
                        F.CONTROLE_TALAO,
                        G.STATUS,
                        G.DIAS,
                        trim(G.VER_PECA_DISPONIVEL) VER_PECA_DISPONIVEL,
                        trim(G.VER_PARES) VER_PARES,
                        trim(G.HABILITA_QUEBRA_TALAO_SKU) HABILITA_QUEBRA_TALAO_SKU
                    
                    FROM
                        TBGP G,
                        TBFAMILIA F

                    WHERE
                        F.CODIGO = G.FAMILIA_ID
                    )X
                )Y
            WHERE
                1=1
            /*@FILTRO*/
            /*@STATUS*/
            /*@FAMILIA*/

            /*@ORDER*/
        ";
        
        $args = [
            '@FILTRO'  => $filtro,
            '@STATUS'  => $status,
            '@FAMILIA' => $familia,
            '@ORDER'   => $order
        ];
        
        return $con->query($sql,$args);
    }
    
    /**
     * Consulta de Unidades Produtivas
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function up($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
		$filtro				= isset($param->FILTRO)				? "AND FILTRO   LIKE '%" . str_replace(' ','%', $param->FILTRO)          ."%'"					: '';
        $perfil_up          = isset($param->PERFIL_UP)          ? "AND X.UP_PERFIL = '$param->PERFIL_UP'"   : '';
        $gp					= isset($param->GP)					? "AND X.GP_ID      IN (" . Helpers::arrayToList($param->GP             ,       999999) . ")"	: '';
        $status				= isset($param->STATUS)				? "AND X.STATUS     IN (" . Helpers::arrayToList($param->STATUS         ,       999999) . ")"	: '';
        $familia			= isset($param->FAMILIA)			? "AND X.FAMILIA_ID IN (" . Helpers::arrayToList($param->FAMILIA        ,       999999) . ")"	: '';
        $calendario_data	= isset($param->CALENDARIO_DATA)	?                           Helpers::arrayToList($param->CALENDARIO_DATA, '1989.01.01')			: '1989.01.01';
		$order				= isset($param->ORDER)				? "ORDER BY            " . Helpers::arrayToList($param->ORDER  , 'DESCRICAO')					: '';
        
        $sql =
        "
            SELECT
                X.GP_ID,
                X.UP_ID,
                X.UP_DESCRICAO,
                X.UP_PERFIL,
                X.FAMILIA_ID,
                X.STATUS,
                X.CALENDARIO_DATA,
                X.CALENDARIO_MINUTOS,
                X.CALENDARIO_HORARIO

            FROM
                (
                SELECT
					(U.ID || ' ' || U.DESCRICAO) FILTRO,
                    A.GP_ID,
                    U.ID UP_ID,
                    U.DESCRICAO UP_DESCRICAO,
                    U.FAMILIA_ID,
                    U.STATUS STATUS,
                    U.PERFIL UP_PERFIL,
                    B.DATA CALENDARIO_DATA,
                    B.MINUTOS CALENDARIO_MINUTOS,
                    B.HORARIO CALENDARIO_HORARIO

                FROM    
                    TBUP U,
                    TBGP_UP A
                    LEFT JOIN
                    TBCALENDARIO_UP B ON 
                        A.GP_ID = B.GP_ID
                    AND A.UP_ID = B.UP_ID
                    AND B.DATA = :CALENDARIO_DATA

                WHERE
                    U.ID = A.UP_ID

                ORDER BY GP_ID, UP_DESCRICAO, UP_ID
                ) X
            WHERE
                1=1
			/*@FILTRO*/
            /*@PERFIL_UP*/
            /*@GP*/
            /*@STATUS*/
            /*@FAMILIA*/
			/*@ORDER*/
        ";
        
        $args = [
			'@FILTRO'			=> $filtro,
            '@PERFIL_UP'        => $perfil_up,
            '@GP'               => $gp,
            '@STATUS'           => $status,
            '@FAMILIA'          => $familia,
			'@ORDER'		    => $order,
            ':CALENDARIO_DATA'  => $calendario_data
        ];
        
        return $con->query($sql,$args);
    }
    
    /**
     * Consulta de Estações de Trabalho
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function estacao($param = [], _Conexao $con = null)
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
                LIST(S.PERFIL_SKU)PERFIL_SKU

            FROM
                TBSUB_UP S

            WHERE
                1=1
            /*@STATUS*/
            /*@UP*/   
            /*@PERFIL_SKU*/

            GROUP BY 1,2,3
        ";
        
        $args = [
            '@STATUS'     => $status,
            '@UP'         => $up,
            '@PERFIL_SKU' => $perfil_sku
        ];
        
        return $con->query($sql,$args);
    }

    /**
     * Consulta de Estações de Trabalho
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function conformacao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $status     = isset($param->STATUS    ) ? "AND S.STATUS     IN (" . Helpers::arrayToList($param->STATUS    , 999999) . ")" : '';
        $up         = isset($param->UP        ) ? "AND P.ID         IN (" . Helpers::arrayToList($param->UP        , 999999) . ")" : '';
        $perfil_sku = isset($param->PERFIL_SKU) ? "AND S.PERFIL_SKU IN (" . Helpers::arrayToList($param->PERFIL_SKU, 999999) . ")" : '';
        
        $sql =
        "
            SELECT
                LPAD(S.UP_ID,3,0) as ID,
                LIST(S.ID) as IDS,
                LPAD(S.UP_ID,3,0)UP_ID,
                c.descricao as DESCRICAO,
                LIST(S.PERFIL_SKU)PERFIL_SKU

            FROM
                TBSUB_UP S,
                TBUP P,
                TBCENTRO_DE_CUSTO C

            WHERE
                1=1
            /*@STATUS*/
            /*@UP*/   
            /*@PERFIL_SKU*/
            AND S.CCUSTO like P.CCUSTO||'%'
            AND C.CODIGO = S.CCUSTO
    
            GROUP BY 1,3,4
        ";
        
        $args = [
            '@STATUS'     => $status,
            '@UP'         => $up,
            '@PERFIL_SKU' => $perfil_sku
        ];
        
        return $con->query($sql,$args);
    }    
    
    /**
     * Consulta de Grupos Produção / Up / Estacao
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function gpUpEstacao($param = [], _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $filtro         = isset($param->FILTRO   ) ? "AND FILTRO   LIKE '%" . str_replace(' ','%', $param->FILTRO               ) ."%'" : '';
        $status         = isset($param->STATUS   ) ? "AND STATUS     IN  (" . Helpers::arrayToList($param->STATUS    , 999999   ) . ")" : '';
        $familia        = isset($param->FAMILIA  ) ? "AND FAMILIA_ID IN  (" . Helpers::arrayToList($param->FAMILIA   , 999999   ) . ")" : '';
        $gp             = isset($param->GP       ) ? "AND GP_ID      IN  (" . Helpers::arrayToList($param->GP        , 999999   ) . ")" : '';
        $up             = isset($param->UP       ) ? "AND UP_ID      IN  (" . Helpers::arrayToList($param->UP        , 999999   ) . ")" : '';
        $up_status      = isset($param->UP_STATUS) ? "AND UP_STATUS  IN  (" . Helpers::arrayToList($param->UP_STATUS , "'#'","'") . ")" : '';
        $estacao        = isset($param->ESTACAO  ) ? "AND ESTACAO    IN  (" . Helpers::arrayToList($param->ESTACAO   , 999999   ) . ")" : '';
        $estacao_perfil = isset($param->PERFIL   ) ? "AND PERFIL_SKU IN  (" . Helpers::arrayToList($param->PERFIL    ,    "'#'","'",'PERFIL') . ")" : '';
        $order          = isset($param->ORDER    ) ? "ORDER BY            " . Helpers::arrayToList($param->ORDER     , 'FAMILIA_DESCRICAO, GP_DESCRICAO, ESTACAO_DESCRICAO')  : '';
        
        
        $sql =
        "
            SELECT
                X.FAMILIA_ID,
                X.FAMILIA_DESCRICAO,
                X.FAMILIA_UM_ALTERNATIVA,
                X.FAMILIA_STATUS,
                X.GP_ID,
                X.GP_DESCRICAO,
                X.GP_STATUS,
                X.UP_ID,
                X.UP_DESCRICAO,
                X.UP_STATUS,
                X.ESTACAO,
                X.ESTACAO_DESCRICAO,
                X.ESTACAO_STATUS,
                X.FILTRO,     
                LIST(X.PERFIL_SKU) PERFIL_SKU,
                LIST(X.PERFIL_SKU_DESCRICAO) PERFIL_SKU_DESCRICAO,
                LIST(IIF(X.PROCESSAR_AUTOMATICAMENTE = '1', X.PERFIL_SKU ,NULL )) PERFIL_SKU_AUTO,
                LIST(IIF(X.PROCESSAR_AUTOMATICAMENTE = '1', X.PERFIL_SKU_DESCRICAO ,NULL)) PERFIL_SKU_DESCRICAO_AUTO,
                LIST(IIF(X.PROCESSAR_AUTOMATICAMENTE = '1', '<span style=\"color:red; font-weight:bold;\">' || X.PERFIL_SKU || '</span>',X.PERFIL_SKU),', ') PERFIL_SKU_HTML,
                LIST(IIF(X.PROCESSAR_AUTOMATICAMENTE = '1', '<span style=\"color:red; font-weight:bold;\">' || X.PERFIL_SKU_DESCRICAO || '</span>',X.PERFIL_SKU_DESCRICAO),', ') PERFIL_SKU_DESCRICAO_HTML
            FROM
                (
                SELECT
                    F.CODIGO    FAMILIA_ID,
                    F.DESCRICAO FAMILIA_DESCRICAO,
                    F.STATUS    FAMILIA_STATUS,
                    F.UNIDADEMEDIDA_ALTERNATIVO FAMILIA_UM_ALTERNATIVA,
                    G.ID        GP_ID,
                    G.DESCRICAO GP_DESCRICAO,      
                    G.STATUS    GP_STATUS,
                    U.ID        UP_ID,
                    U.DESCRICAO UP_DESCRICAO,       
                    U.STATUS    UP_STATUS,
                    S.ID        ESTACAO,
                    S.DESCRICAO ESTACAO_DESCRICAO,
                    S.PROCESSAR_AUTOMATICAMENTE,
                    S.PERFIL_SKU,
                    (SELECT FIRST 1 P.DESCRICAO FROM TBPERFIL P WHERE P.TABELA = 'SKU' AND P.ID = S.PERFIL_SKU) PERFIL_SKU_DESCRICAO,
                    S.STATUS    ESTACAO_STATUS,
    
                   (F.CODIGO    || ' ' ||
                    F.DESCRICAO || ' ' ||
                    G.ID        || ' ' ||
                    G.DESCRICAO || ' ' ||
                    U.ID        || ' ' ||
                    U.DESCRICAO || ' ' ||
                    S.ID        || ' ' ||
                    S.DESCRICAO)FILTRO
                FROM
                    TBUP U,
                    TBSUB_UP S,
                    TBGP G,
                    TBGP_UP GU,
                    TBFAMILIA F
                WHERE
                    U.ID = S.UP_ID
                AND U.ID = GU.UP_ID
                AND G.ID = GU.GP_ID
                AND F.CODIGO = G.FAMILIA_ID
                )X

            WHERE
                1=1
            /*@FAMILIA*/
            /*@GP*/
            /*@UP*/
            /*@UP_STATUS*/
            /*@ESTACAO*/
            /*@ESTACAO_PERFIL*/
            /*@STATUS_FAMILIA
            /*@STATUS_GP*/
            /*@STATUS_UP*/
            /*@STATUS_ESTACAO*/
            /*@FILTRO*/
                       
            GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14

            /*@ORDER*/
        ";
        
        $args = [
            '@FILTRO'         => $filtro,
            '@STATUS'         => $status,
            '@FAMILIA'        => $familia,
            '@GP'             => $gp,
            '@UP'             => $up,
            '@UP_STATUS'      => $up_status,
            '@ESTACAO'        => $estacao,
            '@ESTACAO_PERFIL' => $estacao_perfil,
            '@ORDER'          => $order
        ];
        
        return $con->query($sql,$args);
    }    
}

class _22030DaoInsert
{
    
}

class _22030DaoUpdate
{
    
}

class _22030DaoDelte
{
    
}