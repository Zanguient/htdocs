<?php

namespace App\Models\DAO\Compras;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Compras\_13021;
use App\Models\DTO\Helper\Arquivo;
use Exception;

class _13021DAO
{	
	
	/**
	 * Verifica a validade da licitação
	 *
	 * @param integer Id do Orçamento
	 * @return array
	 */
	public static function verifValLicit($orc_id) {

		$con = new _Conexao();

		$sql = '
			select l.DATA_VALIDADE
			from TBLICITACAO l
			inner join TBORCAMENTO o on o.LICITACAO_ID = l.ID
			where o.ID = :orc_id
		';

		$args = array(':orc_id' => $orc_id);
		
		return array('validade' => $con->query($sql, $args));

	}
	
	/**
	 * Exibe orçamento.
	 *
	 * @param int $orc_id
	 * @return array
	 */
	public static function exibirOrcamento($orc_id) {

		$con = new _Conexao();
		$con_files = new _Conexao('FILES');

		$licitacao		= self::exibirLic($con, $orc_id);
		$orcamento		= self::exibirOrc($con, $orc_id);
		$orcamento_item = self::exibirOrcItem($con, $orc_id);			
		$arquivo		= Arquivo::exibirArquivoObj($con_files, $orcamento[0]->VINCULO_ID, 'ORCAMENTO');

		return array(
			'licitacao'		 => $licitacao,
			'orcamento' 	 => $orcamento,
			'orcamento_item' => $orcamento_item,
			'arquivo'		 => $arquivo
		);
	
	}
	
	/**
	 * Consulta licitação.
	 * Função complementar à 'exibirOrcamento'.
	 * 
	 * @param _Conexao $con
	 * @param int $orc_id
	 * @return array
	 */
	public static function exibirLic(_Conexao $con, $orc_id) {
		
		$sql1 = "
			SELECT 
				LPAD(L.ID, 5, '0') ID, L.OBSERVACAO, LPAD(L.USUARIO_ID, 3, '0') USUARIO_ID,
				(SELECT IIF(U.NOME = '', U.USUARIO, U.NOME) USUARIO_DESCRICAO FROM TBUSUARIO U WHERE U.CODIGO = L.USUARIO_ID) USUARIO_DESCRICAO,
				(SELECT U.EMAIL FROM TBUSUARIO U WHERE U.CODIGO = L.USUARIO_ID) USUARIO_EMAIL
			FROM TBLICITACAO L
			INNER JOIN TBORCAMENTO O ON O.LICITACAO_ID = L.ID
			WHERE O.ID = :ORC_ID
		";

		$args1 = array(':ORC_ID' => $orc_id);

		return $con->query($sql1, $args1);
		
	}
	
	/**
	 * Consulta orçamento.
	 * Função complementar à 'exibirOrcamento'.
	 * 
	 * @param _Conexao $con
	 * @param int $orc_id
	 * @return array
	 */
	public static function exibirOrc(_Conexao $con, $orc_id) {
		
		$sql1 = "
			select
				lpad(o.ID, 5, '0') ORCAMENTO_ID, o.EMPRESA_ID, o.CONTATO, o.VALIDADE_PROPOSTA, o.PRAZO_ENTREGA,
				o.FRETE, o.FRETE_VALOR, o.PAGAMENTO_FORMA, o.PAGAMENTO_CONDICAO, o.OBSERVACAO, o.VINCULO_ID,
				(select e.RAZAOSOCIAL EMPRESA_DESCRICAO from TBEMPRESA e where e.CODIGO = o.EMPRESA_ID)
			from TBORCAMENTO o
			where o.ID = :orc_id
			order by 1
		";

		$args1 = array(':orc_id' => $orc_id);

		return $con->query($sql1, $args1);
		
	}
	
	/**
	 * Consulta itens do orçamento.
	 * Função complementar à 'exibirOrcamento'.
	 * 
	 * @param _Conexao $con
	 * @param int $orc_id
	 * @return array
	 */
	public static function exibirOrcItem(_Conexao $con, $orc_id) {
		
		$sql2 = "
			select
				lpad(i.ID, 5, '0') ORCAMENTO_ITEM_ID, lpad(i.PRODUTO_ID, 6, '0') PRODUTO_ID,
				(select p.DESCRICAO PRODUTO_DESCRICAO from TBPRODUTO p where p.CODIGO = i.PRODUTO_ID),
				i.UM, i.TAMANHO, i.QUANTIDADE, i.VALOR_UNITARIO, i.PERCENTUAL_IPI, i.OBS_PRODUTO, i.PRODUTO_INFO
			from TBORCAMENTO_ITEM i
			where i.ORCAMENTO_ID = :orc_id
			order by 1
		";

		$args2 = array(':orc_id' => $orc_id);

		return $con->query($sql2, $args2);
		
	}

	/**
	 * Atualiza dados do objeto na base de dados.
	 * 
	 * @param _13021 $obj
	 */
	public static function alterar(_13021 $obj)
	{
		$con = new _Conexao();
        $con_files = new _Conexao('FILES');
        
		try {

			self::alterarOrc($obj, $con);
			self::alterarItem($obj, $con);
			
			Arquivo::alterarVinculoObj($con_files, $obj);
			Arquivo::excluirArquivo($con_files, $obj);

			$con->commit();
			$con_files->commit();
		
        } catch (Exception $e) {
			$con->rollback(); 
			$con_files->rollback();
			throw $e;
		}

	}
	
	/**
	 * Atualiza dados do orçamento na base de dados.
	 * Função complementar à 'Alterar'.
	 *
	 * @param _13021 $obj
	 * @param _Conexao $con
	 */
	public static function alterarOrc(_13021 $obj, _Conexao $con) {
		
		$sql1 = "
			update TBORCAMENTO o
			set o.FRETE = :frete,
				o.FRETE_VALOR = :frete_valor,
				o.PAGAMENTO_CONDICAO = :pag_cond,
				o.PAGAMENTO_FORMA = :pag_forma,
				o.VALIDADE_PROPOSTA = :val,
				o.PRAZO_ENTREGA = :prazo,
				o.CONTATO = :contato,
				o.OBSERVACAO = :obs,
				o.VINCULO_ID = :vinc,
				o.STATUS_RESPOSTA = :stts_res
			where o.ID = :orc_id
		";
		
		$args1 = array(
			':frete' 		=> $obj->getFrete(),
			':frete_valor'	=> $obj->getFreteValor(),
			':pag_cond' 	=> $obj->getPagCondicao(),
			':pag_forma' 	=> $obj->getPagForma(),
			':val'			=> $obj->getValidadeProposta(),
			':prazo' 		=> $obj->getPrazoEntrega(),
			':contato' 		=> $obj->getEmpresaContato(),
			':obs' 			=> $obj->getObservacao(),
			':orc_id' 		=> $obj->getOrcamentoId(),
			':vinc'			=> $obj->getVinculo(),
			':stts_res'		=> $obj->getStatusResposta()
		);
		
		$con->execute($sql1, $args1);
	}
	
	/**
	 * Atualiza dados do produto do orçamento na base de dados.
	 * Função complementar à 'Alterar'.
	 *
	 * @param _13021 $obj
	 */
	public static function alterarItem(_13021 $obj, _Conexao $con) {

		//Produto
		$i = 0;
		foreach($obj->getProdutoId() as $prod_id) {

			$sql2 = "
				update TBORCAMENTO_ITEM i
				set i.VALOR_UNITARIO = :valor, i.PERCENTUAL_IPI = :ipi, i.OBS_PRODUTO = :obs_prod
				where i.ORCAMENTO_ID = :orc_id and i.PRODUTO_ID = :prod_id
			";

			$args2 = array(
				':valor' 	=> $obj->getValorUnitario()[$i],
				':ipi' 		=> $obj->getPercentualIpi()[$i],
				':obs_prod'	=> $obj->getObsProduto()[$i],
				':orc_id' 	=> $obj->getOrcamentoId(),
				':prod_id' 	=> $prod_id
			);

			$con->execute($sql2, $args2);

			$i++;
		}

	}
	
}
?>