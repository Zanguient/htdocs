<?php

namespace App\Models\DAO\Produto;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Produto\_27020;

/**
 * DAO do objeto _27020 - Cadastro de Modelos
 */
class _27020DAO {

	/**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function listar($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = '';

            $args = array(
                ':id' => $dados->getId(),
            );

            $ret = $con->query($sql, $args);

            $con->commit();
			
			return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    public static function consultarModelo($con) {

    	$sql = "
    		SELECT
			    M.CODIGO,
			    M.DESCRICAO
			FROM
			    TBMODELO M
			WHERE
			    M.STATUSEXCLUSAO = '1'
			AND M.STATUS = '1'
    	";

    	return $con->query($sql);
    }
	
	/**
	 * Consultar modelo por cliente.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarModeloPorCliente($param, $con) {
		
		$sql = "SELECT
			    j.*,
			    COALESCE(
			        (SELECT FIRST 1 '0' FROM TBPEDIDO_ITEM PI 
			            WHERE PI.DATA_INCLUSAO >= CURRENT_DATE - 180 
			            AND PI.CLIENTE_CODIGO = :CLIENTE_3 
			            AND PI.MODELO_CODIGO = j.MODELO_CODIGO)
			        ,'1'
			    ) MAIS_PEDIDO

			    FROM
			        (SELECT
			                LPAD(Y.MODELO_CODIGO, 5, '0') MODELO_CODIGO, 
			                Y.MODELO_DESCRICAO, 
			                Y.FAMILIA_CODIGO,
			                LPAD(Y.COR_CODIGO, 3, '0') COR_CODIGO, 
			                Y.COR_DESCRICAO,
			                Y.GRADE

			            FROM(

			                Select
			                    X.MODELO_CODIGO, X.MODELO_DESCRICAO, X.FAMILIA_CODIGO, X.COR_CODIGO, X.COR_DESCRICAO,
			                    REPLACE(TRIM(X.GRADE), ' ', ', ') GRADE

			                From (
			                    Select
			                        A.MODELO_CODIGO,
			                        (Select First 1 B.DESCRICAO from TBMODELO B Where A.MODELO_CODIGO = B.CODIGO) Modelo_Descricao,
			                        M.FAMILIA_CODIGO,
			                        0 Cor_Codigo,
			                        Cast('TODAS' as VarChar(20)) Cor_Descricao,

			                        iif(M.t01='1',G.t01||' ','')||
			                        iif(M.t02='1',G.t02||' ','')||
			                        iif(M.t03='1',G.t03||' ','')||
			                        iif(M.t04='1',G.t04||' ','')||
			                        iif(M.t05='1',G.t05||' ','')||
			                        iif(M.t06='1',G.t06||' ','')||
			                        iif(M.t07='1',G.t07||' ','')||
			                        iif(M.t08='1',G.t08||' ','')||
			                        iif(M.t09='1',G.t09||' ','')||
			                        iif(M.t10='1',G.t10||' ','')||
			                        iif(M.t11='1',G.t11||' ','')||
			                        iif(M.t12='1',G.t12||' ','')||
			                        iif(M.t13='1',G.t13||' ','')||
			                        iif(M.t14='1',G.t14||' ','')||
			                        iif(M.t15='1',G.t15||' ','')||
			                        iif(M.t16='1',G.t16||' ','')||
			                        iif(M.t17='1',G.t17||' ','')||
			                        iif(M.t18='1',G.t18||' ','')||
			                        iif(M.t19='1',G.t19||' ','')||
			                        iif(M.t20='1',G.t20||' ','') Grade

			                    From TbCLIENTE_MODELO_PRECO A, TbModelo M, TBGRADE G
			                    Where A.CLIENTE_CODIGO = :CLIENTE_1
			                      and A.Modelo_Codigo  = M.Codigo
			                      and M.Status = '1'
			                      AND G.CODIGO = M.GRADE_CODIGO

			                ) X
			                
			            ) Y

			            group by 1,2,3,4,5,6
			        ) j
			            Order By 7,2,1,4,3

		";
		
		$args = [
			':CLIENTE_1' => $param->CLIENTE_ID,
			//':CLIENTE_2' => $param->CLIENTE_ID,
			':CLIENTE_3' => $param->CLIENTE_ID
		];
		
		return $con->query($sql, $args);				
	}

	/**
	 * Ver arquivo de amostra.
	 * @param json $param
	 * @param _Conexao $conFile
	 * @return array
	 */
	public static function verArquivo($param, $conFile) {

		$sql = '
			SELECT 
				A.ARQUIVO,
				A.CONTEUDO,
				A.TAMANHO,
				A.EXTENSAO
			FROM 
				TBARQUIVO A
			WHERE 
				A.ID = (SELECT FIRST 1 V.ARQUIVO_ID 
						FROM TBVINCULO V 
						WHERE 
							V.SEQUENCIA = 1 
						AND V.ID = :ITEM)
		';

		$args = [
			':ITEM' => $param->MODELO_CODIGO
			// ':ITEM' => $param->modeloId
		];

		return $conFile->query($sql, $args);
	}
	
}