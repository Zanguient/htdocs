<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _22021 - Relatório de peças disponíveis para consumo
 */
class _22021DAO {
	
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
	
	public static function relatorioPecaDisponivelTalao($param = []) {
		
		$con = new _Conexao();
		
		$estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param)	? " $param->ESTABELECIMENTO_ID"	: '';
		$gp_id				= array_key_exists('GP_ID', $param)					? ", $param->GP_ID"					: '';
		$periodo			= array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param)
								?	"AND (
										IIF(S.O_DATAHORA_INICIO IS NULL
											, PR.DATA BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM' --periodo para remessa de requisicao
											, S.O_DATAHORA_INICIO BETWEEN '$param->DATA_INI 00:00:00' AND '$param->DATA_FIM 23:59:59'
										)
									)"
								: '';
		$status				= array_key_exists('STATUS', $param) ? ", $param->STATUS" : '';
		$somente_sobra		= array_key_exists('SOMENTE_SOBRA', $param) 
								?	"AND S.O_SALDO <> COALESCE(S.O_RENDIMENTO, 0)
									AND COALESCE(S.O_RENDIMENTO, 0) > 0"	
								: '';
		
		$sql = "
			SELECT 
				S.O_DATAHORA_INICIO,
				S.O_UP_ID,
				(SELECT U.DESCRICAO FROM TBUP U WHERE U.ID = S.O_UP_ID) UP_DESCRICAO,
				S.O_ESTACAO,
				(SELECT R.REMESSA FROM VWREMESSA R WHERE R.REMESSA_ID = S.O_REMESSA_ID) REMESSA,
				S.O_REMESSA_ID,
				LPAD(S.O_REMESSA_TALAO_ID, 2, '0') O_REMESSA_TALAO_ID,
				S.O_REMESSA_DATA,
				S.O_PRODUTO_ID,
				(SELECT P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = S.O_PRODUTO_ID) PRODUTO_DESCRICAO,
				S.O_TAMANHO_DESC_CONSUMO,
				--IIF(S.O_TALAO_ID IS NULL, '', LPAD(S.O_TALAO_ID, 2, '0')) O_TALAO_ID,
				S.O_TALAO_ID,
				S.O_REMESSA_PECA,
				LPAD(S.O_PECA_ID, 7, '0') O_PECA_ID,
				S.O_TIPO,
				S.O_QUANTIDADE,
				S.O_SALDO,
				S.O_QUANTIDADE_ALOCACAO,
				(S.O_QUANTIDADE_ALOCACAO - S.O_QUANTIDADE) SALDO_RESTANTE_PECA
				
				FROM
					SPC_RELATORIO_PECA_DISPONIVEL(
						/*@ESTABELECIMENTO_ID*/
						/*@GP_ID*/
						/*@STATUS*/
					) S
					LEFT JOIN TBPROGRAMACAO PR ON PR.TABELA_ID = S.O_ID AND PR.TIPO = 'A'
					
				WHERE
					1=1
					/*@PERIODO*/
					/*@SOMENTE_SOBRA*/
				
				ORDER BY
					UP_DESCRICAO,
					S.O_ESTACAO,
					PRODUTO_DESCRICAO,
					S.O_QUANTIDADE,
					S.O_DATAHORA_INICIO
		";
		
		$args = [
			'@ESTABELECIMENTO_ID'	=> $estabelecimento_id,
			'@GP_ID'				=> $gp_id,
			'@PERIODO'				=> $periodo,
			'@SOMENTE_SOBRA'		=> $somente_sobra,
			'@STATUS'				=> $status			
		];
		
		return $con->query($sql, $args);
	}
	
}