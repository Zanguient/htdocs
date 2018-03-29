<?php

namespace App\Models\DAO\Ppcp;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use Exception;

class _22070DAO
{
	/**
	 * Similar ao CREATE (CRIAR) do CRUD
	 * @param _13050 $param
	 */
	public static function gravar($param)
	{
        $con = new _Conexao();
		try
		{           
            /**
             * Separa pela data os talões de destino no banco de dados 
             */
            foreach ($param->TALOES_DESTINO as $args) {
                _22070DaoUpdate::seperarTaloes    ($args, $con);
                _22070DaoUpdate::reprogramaUpTalao($args, $con);
                _22070DaoUpdate::reprogramaData($args, $con);
            }
            
            /**
             * Reprograma os talões de destino
             */
            foreach ($param->REPROGRAMAR_DESTINO as $args) {                
                _22070DaoUpdate::reprogramarTaloes($args, $con);
            }
            
            /**
             * Reprograma os talões de origem 
             */
            foreach ($param->REPROGRAMAR_ORIGEM as $args) {                
                _22070DaoUpdate::reprogramarTaloes($args, $con);
            } 
                       
            $con->commit();
		}
        catch (Exception $e)
        {
			$con->rollback();
			throw $e;
		}
	}
} 

class _22070DaoSelect
{
    public static function consultaReprogramado($param = [], _Conexao $con = null)
    {
        $sql =
        "
            SELECT
                A.ID,
                PRODUTO_ID,
                TAMANHO,
                QUANTIDADE,
                TEMPO
            FROM
                TBPROGRAMACAO A
            WHERE
                A.ESTABELECIMENTO_ID = :ESTABELECIMENTO_ID
            AND A.TIPO               = :PROGRAMACAO_TIPO
            AND A.GP_ID              = :GP_ID
            AND A.UP_ID              = :UP_ID
            AND A.ESTACAO            = :ESTACAO
            AND (A.DATAHORA_INICIO >=  :DATAHORA_INICIO OR A.DATAHORA_INICIO < '01/01/2001 23:59:59')
            AND A.STATUS IN ('0')
            AND A.QUANTIDADE > 0

            ORDER BY
                A.DATAHORA_INICIO
        ";

        $args = [
            ':ESTABELECIMENTO_ID'   => $param->ESTABELECIMENTO_ID,
            ':PROGRAMACAO_TIPO'     => $param->PROGRAMACAO_TIPO,
            ':GP_ID'                => $param->GP_ID,
            ':UP_ID'                => $param->UP_ID,
            ':ESTACAO'              => $param->ESTACAO,
            ':DATAHORA_INICIO'      => $param->DATAHORA_INICIO,
        ];

        return $con->query($sql,$args);
    }
}

class _22070DaoInsert
{    
//    public static function remessa($param = [], _Conexao $con = null)
//    {
//        $con = $con ? $con : new _Conexao;
//
//        $sql =
//        "
//            INSERT INTO VWREMESSA (
//                ESTABELECIMENTO_ID,
//                REMESSA_ID,
//                REMESSA,
//                FAMILIA_ID,
//				COMPONENTE,
//                DATA,
//                STATUS,
//                PERFIL,
//                WEB
//            ) VALUES (
//               :ESTABELECIMENTO_ID,
//               :REMESSA_ID,
//               :REMESSA,
//               :FAMILIA_ID,
//			   :COMPONENTE,
//               :DATA,
//               :STATUS,
//               :PERFIL,
//               :WEB
//            );
//        ";
//        $args = [
//            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
//            ':REMESSA_ID'         => $param->ID,
//            ':REMESSA'            => $param->REMESSA,
//            ':FAMILIA_ID'         => $param->FAMILIA_ID,
//			':COMPONENTE'		  => $param->COMPONENTE,
//            ':DATA'               => date('Y.m.d'),
//            ':STATUS'             => 1,
//            ':PERFIL'             => 1,
//            ':WEB'                => 1
//        ];
//
//        $con->execute($sql, $args);
//    }
}

class _22070DaoUpdate
{
    public static function reprogramaUpTalao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE VWREMESSA_TALAO T SET
                T.UP_ID   = :UP_ID,
                T.ESTACAO = :ESTACAO
            WHERE
                T.ID = :TALAO_ID
        ";

        $args = [
            ':UP_ID'    => $param->UP_ID,
            ':ESTACAO'  => $param->ESTACAO,
            ':TALAO_ID' => $param->TALAO_ID,
        ];

        $con->execute($sql, $args);
    }
    
    public static function reprogramaData($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $sql =
        "
            UPDATE TBPROGRAMACAO P SET
                P.DATA = :DATA_INICIO
            WHERE
                P.TIPO               = 'A'
            AND P.TABELA_ID          = :TALAO_ID
            
        ";

        $args = [
            ':DATA_INICIO' => $param->DATA_INICIO,
            ':TALAO_ID'    => $param->TALAO_ID,
        ];

        $con->execute($sql, $args);
    }
    
    public static function seperarTaloes($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE
                TBPROGRAMACAO P
            SET
                P.DATAHORA_INICIO = :DATAHORA_INICIO,
                P.UP_ID           = :UP_ID,
                P.ESTACAO         = :ESTACAO
            WHERE
                P.ESTABELECIMENTO_ID = :ESTABELECIMENTO_ID
            AND P.ID                 = :PROGRAMACAO_ID
            AND P.TIPO               = :PROGRAMACAO_TIPO
            AND P.TABELA_ID          = :TABELA_ID
            AND P.STATUS            IN ('0','1')
        ";

        $args = [
            ':DATAHORA_INICIO'      => $param->DATAHORA_INICIO,
            ':UP_ID'                => $param->UP_ID,
            ':ESTACAO'              => $param->ESTACAO,
            ':ESTABELECIMENTO_ID'   => $param->ESTABELECIMENTO_ID,
            ':PROGRAMACAO_ID'       => $param->PROGRAMACAO_ID,
            ':PROGRAMACAO_TIPO'     => $param->PROGRAMACAO_TIPO,
            ':TABELA_ID'            => $param->TALAO_ID,
        ];

        $con->execute($sql, $args);
    }
    
    public static function reprogramarTaloes($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            EXECUTE PROCEDURE SPU_REPROGRAMACAO1(
                :ESTABELECIMENTO_ID,
                :PROGRAMACAO_TIPO,
                :GP_ID,
                :UP_ID,
                :ESTACAO,
                :DATAHORA_INICIO
            );
        ";

        $args = [
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':PROGRAMACAO_TIPO'   => $param->PROGRAMACAO_TIPO,
            ':GP_ID'              => $param->GP_ID,
            ':UP_ID'              => $param->UP_ID,
            ':ESTACAO'            => $param->ESTACAO,
            ':DATAHORA_INICIO'    => $param->DATAHORA_INICIO,
        ];

        $con->execute($sql, $args);
    }
}

class _22070DaoDelte
{

}