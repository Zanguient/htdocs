<?php

namespace App\Models\DAO\Helper;

use Exception;
use App\Models\Conexao\_Conexao;

class HistoricoDAO
{


	/**
	 * listar o Historico.
	 * @return array
	 */
	public static function GetHistorico($tabela, $id)
	{
    	try{

            $con = new _Conexao();

    		$sql = "

                SELECT
                    H.DATAHORA,
                    H.HISTORICO,
                    H.IP,
                    H.VERSAO,
                    COALESCE(H.USUARIO,(SELECT FIRST 1 U.USUARIO FROM TBUSUARIO U WHERE U.CODIGO = H.USUARIO_CODIGO))USUARIO
                FROM
                    TBHISTORICO H

                WHERE
                    H.TABELA = :TABELA
                AND H.TABELA_ID = :ID ORDER BY H.ID DESC
            ";

            $args = array(':TABELA' => $tabela, ':ID' => $id);

            $retorno = $con->query($sql,$args);

            $resposta = array('0' => 'sucesso');

            $Ret = array('retorno'   => $retorno,'resposta'	=> $resposta);

		} catch(ValidationException $e1) {
			$Ret = array('resposta'	=> array('0' => 'erro', '1' => $e1->getMessage()));
		} catch(Exception $e2) {
		    $Ret = array('resposta'	=> array('0' => 'erro', '1' => $e2->getMessage()));
		}

       return $Ret;

	}
    
	public static function getHistorico2($param)
	{

        $con = new _Conexao();



        $sql = "
            SELECT
                H.DATAHORA,
                FN_TIMESTAMP_TO_STRING(h.DATAHORA) DATAHORA_TEXT,
                H.HISTORICO,
                H.IP,
                H.VERSAO,
                TRIM(COALESCE(H.USUARIO,(SELECT FIRST 1 U.USUARIO FROM TBUSUARIO U WHERE U.CODIGO = H.USUARIO_CODIGO)))USUARIO
            FROM
                TBHISTORICO H

            WHERE
                H.TABELA = :TABELA
            AND H.TABELA_ID = :TABELA_ID ORDER BY H.ID DESC
        ";

        $args = [
            'TABELA'    => $param->TABELA,
            'TABELA_ID' => $param->TABELA_ID
        ];    

        return $con->query($sql,$args);

	}

    /**
	 * grava um historico.
	 * @return array
	 */
	public static function setHistorico($tabela, $id, $descricao, _Conexao $con = null)
	{
        $autocommit = $con ? false : true;
        $con = $con ? $con : new _Conexao;
        
		try
		{
            $sql = '
                INSERT INTO TBHISTORICO (
                    TABELA, 
                    TABELA_ID, 
                    USUARIO, 
                    IP, 
                    HISTORICO
                ) VALUES (
                    :TABELA,
                    :ID,
                    (SELECT FIRST 1 MON$USER FROM MON$ATTACHMENTS WHERE MON$ATTACHMENT_ID = CURRENT_CONNECTION),
                    (SELECT FIRST 1 MON$REMOTE_ADDRESS FROM MON$ATTACHMENTS WHERE MON$ATTACHMENT_ID = CURRENT_CONNECTION),
                    :HISTORICO
                );
            ';

            $args = [
                ':TABELA'    => $tabela, 
                ':ID'        => $id, 
                ':HISTORICO' => $descricao
            ];
            
            $con->execute($sql, $args);
            
            if ($autocommit) {
                $con->commit();
            }  
		}
        catch (Exception $e)
        {
			$con->rollback();
			throw $e;
		}

	}


}