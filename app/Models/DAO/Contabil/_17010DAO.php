<?php

namespace App\Models\DAO\Contabil;

use App\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
use App\Models\DTO\Contabil\_17010;
use App\Models\Conexao\_Conexao;

class _17010DAO
{	
	
	/**
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar()
	{
// 		return DB::select('
// 					select first 20 r.ID, r.URGENCIA, r.DATA,
//     				 (select first 1 list(i.OC) OC from TBREQUISICAO_OC_ITEM i where r.ID = i.REQUISICAO_ID),
//     				 (select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID),
//     			   	 (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO)
// 					from TBREQUISICAO_OC r
//     				order by r.ID DESC
//     		  ');
	}
	
	/**
	 * Gerar id do objeto.
	 * @return integer
	 */
	public static function gerarId()
	{
// 		return DB::select('select gen_id(GTBREQUISICAO_OC, 1) ID from RDB$DATABASE');
	}
	
	/**
	 * Inserir dados do objeto na base de dados.
	 * @param _1000 $obj
	 */
	public static function gravar(_1000 $obj)
	{
		
// 		$sql_req = 'insert into TBREQUISICAO_OC'.
// 					' (ID, CCUSTO, USUARIO_GESTOR_ID, USUARIO_ID, URGENCIA, EMPRESA_ID, EMPRESA_DESCRICAO, EMPRESA_FONE, EMPRESA_EMAIL, EMPRESA_CONTATO, DATA, DATA_UTILIZACAO)'.
// 					' values (:id, :ccusto, :usuario_gestor_id, :usuario_id, :urgencia, :empresa_id, :empresa_desc, :empresa_fone, :empresa_email, :empresa_contato, :data, :data_utilizacao)';
		 
// 		$args_req = array(
// 				':id' 					=> $obj->getId(),
// 				':ccusto' 				=> $obj->getCcusto(),
// 				':usuario_gestor_id'	=> $obj->getUsuarioGestorId(),
// 				':usuario_id'			=> $obj->getUsuarioId(),
// 				':urgencia' 			=> $obj->getUrgencia(),
// 				':empresa_id' 			=> $obj->getEmpresaId(),
// 				':empresa_desc'			=> $obj->getEmpresaDescricao(),
// 				':empresa_fone'			=> $obj->getEmpresaFone(),
// 				':empresa_email'		=> $obj->getEmpresaEmail(),
// 				':empresa_contato'		=> $obj->getEmpresaContato(),
// 				':data' 				=> $obj->getData(),
// 				':data_utilizacao' 		=> $obj->getDataUtilizacao()
// 		);
		 
// 		DB::insert($sql_req, $args_req);
		
		
// 		$i = 0;
		 
// 		foreach ($obj->getProdutoId() as $prod_id ) {
				
// 			$sql_item = 'insert into TBREQUISICAO_OC_ITEM'.
// 						' (REQUISICAO_ID, PRODUTO_ID, PRODUTO_DESCRICAO, UM, TAMANHO, QUANTIDADE, VALOR_UNITARIO)'.
// 						' values(:req_id, :prod_id, :prod_desc, :um, :tam, :qtd, :vlr)';
		
// 			$args_item = array(
// 					':req_id' 	 => $obj->getId(),
// 					':prod_id' 	 => $prod_id,
// 					':prod_desc' => $obj->getProdutoDescricao()[$i],
// 					':um'		 => $obj->getUm()[$i],
// 					':tam'		 => $obj->getTamanho()[$i],
// 					':qtd'		 => $obj->getQuantidade()[$i],
// 					':vlr'		 => $obj->getValorUnitario()[$i]
// 			);
		
// 			DB::insert($sql_item, $args_item);
		
// 			$i++;
		
// 		}
		
	}
	
	/**
	 * Retorna dados do objeto na base de dados.
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id)
	{
		
// 		$sql = '
// 	    		select r.ID, r.CCUSTO, r.USUARIO_GESTOR_ID, r.USUARIO_ID,
// 				       r.URGENCIA, r.EMPRESA_ID, r.EMPRESA_DESCRICAO, r.EMPRESA_FONE, r.EMPRESA_EMAIL,
//     				   r.EMPRESA_CONTATO, r.DATA, r.DATA_UTILIZACAO,
// 					   (select first 1 u.NOME GESTOR from TBUSUARIO u where u.CODIGO = r.USUARIO_GESTOR_ID),
// 					   (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO)
// 				from TBREQUISICAO_OC r
// 				where r.ID = :id
//     	';
		 
// 		$args = array(':id' => $id);
		 
// 		$dado = DB::select($sql, $args);
		
		
// 		$sql = '
// 	    		select i.ID REQ_ITEM_ID, i.REQUISICAO_ID, i.OC, i.PRODUTO_ID, i.PRODUTO_DESCRICAO,
// 				       i.UM, i.TAMANHO, i.QUANTIDADE, i.VALOR_UNITARIO
// 				from TBREQUISICAO_OC_ITEM i
// 				where i.REQUISICAO_ID = :id
//     	';
		
// 		$args = array(':id' => $id);
		
// 		$dado_itens = DB::select($sql, $args);
		
// 		return array('dado' => $dado, 'dado_itens' => $dado_itens);
		
	}
	
	/**
	 * Atualiza dados do objeto na base de dados.
	 * 
	 * @param _1000 $obj
	 */
	public static function alterar(_1000 $obj)
	{
	
// 		$sql = '
//     			update TBREQUISICAO_OC
//     		 	set CCUSTO = :ccusto, USUARIO_GESTOR_ID = :gestor_id,
// 				    URGENCIA = :urg, EMPRESA_ID = :emp_id, EMPRESA_DESCRICAO = :emp_desc,
//     			    EMPRESA_FONE = :emp_fone, EMPRESA_EMAIL = :emp_email,
//     				EMPRESA_CONTATO = :emp_cont, DATA = :data, DATA_UTILIZACAO = :data_ut
//     			where ID = :id
//     	';
		 
// 		$args = array(
// 				':ccusto' 	 => $obj->getCcusto(),
// 				':gestor_id' => $obj->getUsuarioGestorId(),
// 				':urg'		 => $obj->getUrgencia(),
// 				':emp_id'	 => $obj->getEmpresaId(),
// 				':emp_desc'	 => $obj->getEmpresaDescricao(),
// 				':emp_fone'	 => $obj->getEmpresaFone(),
// 				':emp_email' => $obj->getEmpresaEmail(),
// 				':emp_cont'	 => $obj->getEmpresaContato(),
// 				':data'		 => $obj->getData(),
// 				':data_ut'	 => $obj->getDataUtilizacao(),
// 				':id'		 => $obj->getId()
// 		);
		 
// 		DB::update($sql, $args);
		
		
// 		$i = 0;
		
// 		foreach ($obj->getProdutoId() as $prod_id ) {
		
// 			if( empty($obj->getReqItemId()[$i]) ) {
				 
// 				$sql_item = '
//     					insert into TBREQUISICAO_OC_ITEM
//     					(REQUISICAO_ID, PRODUTO_ID, PRODUTO_DESCRICAO, UM, TAMANHO, QUANTIDADE, VALOR_UNITARIO)
//     					values(:req_id, :prod_id, :prod_desc, :um, :tam, :qtd, :vlr)
//     			';
				 
// 				$args_item = array(
// 						':req_id' 	 => $obj->getId(),
// 						':prod_id' 	 => $prod_id,
// 						':prod_desc' => $obj->getProdutoDescricao()[$i],
// 						':um'		 => $obj->getUm()[$i],
// 						':tam'		 => $obj->getTamanho()[$i],
// 						':qtd'		 => $obj->getQuantidade()[$i],
// 						':vlr'		 => $obj->getValorUnitario()[$i]
// 				);
				 
// 				DB::insert($sql_item, $args_item);
				 
// 			}
// 			else {
				 
// 				$sql_item = '
// 	    				update TBREQUISICAO_OC_ITEM
// 	    				set PRODUTO_ID = :prod_id, PRODUTO_DESCRICAO = :prod_desc,
// 	    					UM = :um, TAMANHO = :tam, QUANTIDADE = :qtd, VALOR_UNITARIO = :vlr
// 	    				where REQUISICAO_ID = :req_id and ID = :id
// 	    		';
		
// 				$args_item = array(
// 						':prod_id' 	 => $prod_id,
// 						':prod_desc' => $obj->getProdutoDescricao()[$i],
// 						':um'		 => $obj->getUm()[$i],
// 						':tam'		 => $obj->getTamanho()[$i],
// 						':qtd'		 => $obj->getQuantidade()[$i],
// 						':vlr'		 => $obj->getValorUnitario()[$i],
// 						':req_id' 	 => $obj->getId(),
// 						':id'		 => $obj->getReqItemId()[$i]
// 				);
		
// 				DB::update($sql_item, $args_item);
				 
// 			}
			 
// 			$i++;
			 
// 		}		
	
	}
	
	/**
	 * Exclui dados do objeto na base de dados.
	 * @param int $id
	 */
	public static function excluir($id)
	{
		
// 		$sql = 'delete from TBREQUISICAO_OC where ID = :id';
// 		$args = array(':id' => $id);
		
// 		DB::delete($sql, $args);
		
// 		$sql = 'delete from TBREQUISICAO_OC_ITEM where REQUISICAO_ID = :id';
// 		$args = array(':id' => $id);
		 
// 		DB::delete($sql, $args);
		
	}

	
	/**
	 * Pesquisa empresa de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCContabil($filtro, $analitica) {
		$con = new _Conexao();
		return  self::exibirCContabil($con, $filtro, $analitica);
	}
	
	/**
	 * Paginação com scroll.
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
	 * @param int $pagina
	 * @return array
	 */
	public static function paginacaoScroll($qtd_por_pagina, $pagina) {
	
// 		return DB::select('
// 					select first '. $qtd_por_pagina .' skip '. $pagina .' r.ID, r.URGENCIA, r.DATA,
// 	 				 (select first 1 list(i.OC) OC from TBREQUISICAO_OC_ITEM i where r.ID = i.REQUISICAO_ID),
// 	 				 (select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID),
// 	 				 (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO)
// 					from TBREQUISICAO_OC r
// 	    			order by r.ID DESC
// 	    	   ');
	
	}
	
	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraObj($filtro) {
	
// 		return DB::select('
// 					select first 20 r.ID, r.URGENCIA, r.DATA,
// 					 (select first 1 list(i.OC) OC from TBREQUISICAO_OC_ITEM i where r.ID = i.REQUISICAO_ID),
// 					 (select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID),
// 				   	 (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO)
// 					from TBREQUISICAO_OC r
// 					where (r.ID like \'%'.$filtro.'%\') 
// 					   or (r.CCUSTO like \'%'.$filtro.'%\') 
//     				order by r.ID DESC
// 	    	   ');
	
	}
	
	public static function exibirCContabil(_Conexao $con, $filtro = false, $analitica = false) {
		$first  	= 50;
		$filtro 	= '%' . Helpers::removeAcento($filtro, '%', 'upper') . '%';
		$analitica	= $analitica ? $analitica : '%';
		
		$sql = /** @lang text */
		"
           SELECT FIRST :FIRST
                X.CONTA,
                X.DESCRICAO,
                X.MASK,
                X.ANALITICA
            FROM
                (SELECT
                    C.CONTA,
                    FN_CCONTABIL_MASK(C.CONTA) MASK,
                    UPPER(C.DESCRICAO)DESCRICAO,
                    C.ANALITICA,

                    (F_REMOVE_ACENTOS(
                        C.CONTA
                    || ' ' ||
                        FN_CCONTABIL_MASK(C.CONTA)
                    || ' ' ||
                        UPPER(C.DESCRICAO)
                    ))FILTRO



                FROM
                    TBCONTACONTABIL C

                WHERE
                    C.ANALITICA LIKE :ANALITICA

                )X
            WHERE
                X.FILTRO LIKE :FILTRO
		";
		
		$args = array(
			':FIRST'		=> $first,
			':FILTRO'		=> $filtro,
			':ANALITICA'	=> $analitica
		);
		
		return $con->query($sql, $args);
	}
	
}

?>