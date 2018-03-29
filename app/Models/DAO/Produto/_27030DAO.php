<?php

namespace App\Models\DAO\Produto;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Produto\_27030;

/**
 * DAO do objeto _27030 - Cadastro de Cores
 */
class _27030DAO {

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
	
	/**
	 * Consultar cores.
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarCor($con) {
		
		$sql = "			
			Select 
                LPAD(A.CODIGO, 5, '0') CODIGO,
                A.DESCRICAO, 
                A.AMOSTRA,
                Trim(Coalesce(
                    (Select First 1 'Sob-Encomenda' From TbCor_Composicao CC 
                        Where CC.cor_composicao_id = A.Codigo 
                        and CC.cor_id = 77)
                    ,'Normal'
                )) CONDICAO

            FROM 
                TBCOR A

            WHERE
                A.STATUS = '1'

            Group By 
                A.CODIGO, A.DESCRICAO, A.AMOSTRA
		";
		
		return $con->query($sql);				
	}

	/**
	 * Consultar cores por modelo ou todas.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarCorPorModelo($param, $con) {
		
		$sql = "			
			Select 
				LPAD(A.CODIGO, 5, '0') CODIGO, 
				A.DATA, 
				A.DESCRICAO, 
				A.DISPONIBILIDADE, 
				A.Perfil,
				A.STATUS, 
				A.TONALIDADE, 
				A.TON_FORNECEDOR, 
				A.USUARIO_CODIGO, 
				A.ABREVIADO,
				A.Classe, 
				A.Observacao,

				(CASE WHEN A.TONALIDADE = 'C' THEN 'CLARO' ELSE 
				(CASE WHEN A.TONALIDADE = 'E' THEN 'ESCURO' ELSE '' END) END
				) TONALIDADE_DESCRICAO, 

				A.AMOSTRA,

				(SELECT FIRST 1 B.USUARIO FROM TBUSUARIO B WHERE A.USUARIO_CODIGO = B.CODIGO
				) USUARIO,

				(CASE WHEN A.STATUS = '1' THEN '' ELSE 'INATIVO' END
				) STATUS_DESCRICAO,

				A.Qtd_Cores,

				Trim(Coalesce(
			        (Select First 1 'Sob-Encomenda' From TbCor_Composicao CC 
			        	Where CC.cor_composicao_id = A.Codigo 
			            and CC.cor_id = 77)
			        ,'Normal'
			    )) CONDICAO,
			    
				Trim(Coalesce(
			    	(Select First 1 '0' From TbPedido_Item PI 
			        	Where PI.data_inclusao >= Current_Date - 180 
			            and PI.Cliente_Codigo = :CLIENTE_ID 
			            and PI.Cor_Id = A.Codigo)
			        ,'1'
				)) MAIS_PEDIDO

			FROM 
				TBCOR A

			WHERE
                A.STATUS = '1'

			Group By 
				A.CODIGO, A.DATA, A.DESCRICAO, A.DISPONIBILIDADE, A.STATUS, A.Perfil,
				A.TONALIDADE, A.TON_FORNECEDOR,
				A.USUARIO_CODIGO, A.ABREVIADO, A.Classe,  A.Observacao, A.AMOSTRA, A.Qtd_Cores

			Having 
				((Select First 1 M.Cor_Id From TbModelo_Cor M Where A.Codigo = M.Cor_Id and M.Modelo_Id = :MODELO_ID) > 0) or (:TODOS = 1)
		";
		
		$args = [
			':CLIENTE_ID'	=> $param->CLIENTE_ID,
			':MODELO_ID'	=> $param->MODELO_ID,
			':TODOS'		=> $param->RETORNA_TODOS
		];
		
		return $con->query($sql, $args);
				
	}
	
}