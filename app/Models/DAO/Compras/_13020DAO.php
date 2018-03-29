<?php

namespace App\Models\DAO\Compras;

use App\Models\DTO\Compras\_13020;
use App\Models\Conexao\_Conexao;
use Exception;

class _13020DAO {

    /**
     * Select da página inicial.
     * 
     * @return array
     */
    public static function listar() {
		
		$con = new _Conexao();
			
		$sql = "
            SELECT FIRST 30
                DISTINCT
                LPAD(l.ID, 5, '0') ID,
                L.DESCRICAO,
                L.DATAHORA,
                L.DATA_VALIDADE,

               (SELECT U.NOME
                FROM   TBUSUARIO U
                WHERE  U.CODIGO = l.USUARIO_ID) REQUERENTE,

               (SELECT LIST(DISTINCT F.DESCRICAO, ', ')
                FROM   TBREQUISICAO_OC_ITEM I,
                       TBPRODUTO P,
                       TBFAMILIA F
                WHERE  P.CODIGO = I.PRODUTO_ID
                  AND  F.CODIGO = P.FAMILIA_CODIGO
                  AND  I.LICITACAO_ID = L.ID
                ORDER BY 1)FAMILIAS

            FROM TBLICITACAO L

            ORDER BY 1 DESC
		";
		
		return $con->query($sql);
    }

    /**
     * Gerar id do objeto.
     * 
     * @return integer
     */
    public static function gerarId() {
        
		$con = new _Conexao();
		
		$sql = 'select gen_id(GTBLICITACAO, 1) ID from RDB$DATABASE';
		
		return $con->query($sql);
		
    }

    /**
     * Gerar id do orçamento.
     * 
     * @return array
     */
    public static function gerarIdOrcamento() {
        
		$con = new _Conexao();
		
		$sql = 'select gen_id(GTBORCAMENTO, 1) ID from RDB$DATABASE';
		
		return $con->query($sql)[0]->ID;
		
    }

    /**
     * Inserir dados do objeto na base de dados.
     * 
     * @param _13020 $obj
	 * @return array
     */
    public static function gravar(_13020 $obj) {

		$con = new _Conexao();
		//$con_slave = new _Conexao('SLAVE');
		
		try {
			
			self::gravarLic($obj, $con);
			self::gravarOrc($obj, $con);
			
//			self::gravarLic($obj, $con, $con_slave);
//			self::gravarOrc($obj, $con, $con_slave);
//			
			$con->commit();
		//	$con_slave->commit();
			
		} catch(Exception $e) {
			$con->rollback();
		//	$con_slave->rollback();
			throw $e;
		}
		
    }
	
	/**
	 * Gravar licitação.
	 * Função complementar à 'gravar'.
	 * 
	 * @param _13020 $obj
	 * @param _Conexao $con
	 */
	public static function gravarLic(_13020 $obj, _Conexao $con) {

		$sql = '
            INSERT INTO TBLICITACAO (
				ID,
				DESCRICAO,
				USUARIO_ID,
				DATAHORA,
				DATA_VALIDADE,
				OBSERVACAO
            ) VALUES (
				:ID,
				:DESCRICAO,
				:USUARIO_ID,
				:DATAHORA,
				:DATA_VALIDADE,
				:OBSERVACAO
            )
		';

		$args = array(
			':ID'               => $obj->getLicitacaoId(),
			':DESCRICAO'        => $obj->getLicitacaoDescricao(),
			':USUARIO_ID'       => $obj->getUsuarioId(),
			':DATAHORA'         => 'NOW',
			':DATA_VALIDADE'    => $obj->getDataValidade(),
			':OBSERVACAO'       => $obj->getObservacao()
		);

		$con->execute($sql, $args);
		//$con_slave->execute($sql, $args);

	}
	
	/**
	 * Gravar orçamento.
	 * Função complementar à 'gravar'.
	 * 
	 * @param _13020 $obj
	 * @param _Conexao $con
	 */
	public static function gravarOrc(_13020 $obj, _Conexao $con) {
		
		//orcamento
		$i = 0;
		foreach ($obj->getEmpresaId() as $emp_id) {

			$sql = 'insert into TBORCAMENTO (ID, LICITACAO_ID, EMPRESA_ID) values (:id, :lic_id, :emp_id)';

			$args = array(
				':id'		=> $obj->getOrcamentoId()[$i],
				':lic_id'	=> $obj->getLicitacaoId(),
				':emp_id'	=> $emp_id,
			);

			$con->execute($sql, $args);
			//$con_slave->execute($sql, $args);

			//item de orçamento
			self::gravarOrcItem($obj, $con, $i);
//			self::gravarOrcItem($obj, $con, $con_slave, $i);

			$i++;
		}
	}
	
	/**
	 * Gravar item de orçamento.
	 * Função complementar à 'gravarOrc'.
	 * 
	 * @param _13020 $obj
	 * @param _Conexao $con
	 * @param int $i
	 */
	public static function gravarOrcItem(_13020 $obj, _Conexao $con, $i) {
		
		//itens do orcamento
		$j = 0;
		foreach ($obj->getProdutoId() as $prod_id) {

			$sql1 = '
				insert into TBORCAMENTO_ITEM (ORCAMENTO_ID, PRODUTO_ID, UM, TAMANHO, QUANTIDADE, REQUISICAO_ID, PRODUTO_INFO, OPERACAO_CODIGO, OPERACAO_CCUSTO, OPERACAO_CCONTABIL)
				values (:orc_id, :prod_id, :um, :tam, :qtd, :req_id, :prod_info, :operacao, :oper_ccusto, :oper_ccont)
			';

			$args1 = array(
				':orc_id'		=> $obj->getOrcamentoId()[$i],
				':prod_id'		=> $prod_id,
				':prod_info'	=> $obj->getProdutoInfo()[$j],
				':um'			=> $obj->getUm()[$j],
				':tam'			=> $obj->getTamanho()[$j],
				':qtd'			=> $obj->getQuantidade()[$j],
				':req_id'		=> $obj->getRequisicaoId()[$j],
				':operacao'		=> $obj->getOperacaoCodigo()[$j],
				':oper_ccusto'	=> $obj->getOperacaoCcusto()[$j],
				':oper_ccont'	=> $obj->getOperacaoCcontabil()[$j]
			);

			$con->execute($sql1, $args1);
			//$con_slave->execute($sql1, $args1);			
			
			//atualizar licitação na tabela TBREQUISICAO_OC_ITEM
			self::gravarOrcItemLic($obj, $con, $j, $prod_id);

			$j++;
		}
	}
	
	/**
	 * Gravar (atualizar) licitação do item de orçamento na requisição.
	 * Função complementar à 'gravarOrcItem'.
	 * 
	 * @param _13020 $obj
	 * @param _Conexao $con
	 * @param int $j
	 */
	public static function gravarOrcItemLic(_13020 $obj, _Conexao $con, $j, $prod_id) {
		
		$sql2 = '
			update TBREQUISICAO_OC_ITEM r
			set r.LICITACAO_ID = :lic_id
			where r.REQUISICAO_ID = :req_id
			and r.PRODUTO_ID = :prod_id
		';

		$args2 = array(
			':lic_id'  => $obj->getLicitacaoId(),
			':req_id'  => $obj->getRequisicaoId()[$j],
			':prod_id' => $prod_id
		);

		$con->execute($sql2, $args2);
		
	}

	/**
     * Retorna dados do objeto na base de dados.
     * 
     * @param int $id
     * @return array
     */
    public static function exibir($id) {
       
		$con = new _Conexao();

		return array(
			'licitacao'			=> self::exibirLic($id, $con),
			'requisicao'		=> self::exibirReq($id, $con),
			'orcamento'			=> self::exibirOrc($id, $con),
			'orcamento_item'	=> self::exibirOrcItem($id, $con),
            'oc'                => self::exibirOc($id, $con)
		);
	
    }
	
	/**
	 * Retorna licitação.
	 * Função complementar à 'exibir'.
	 * 
	 * @param int $id
	 * @param _Conexao $con
	 * @return array
	 */
	public static function exibirLic($id, _Conexao $con) {
		
		$sql1 = "
            SELECT
                LPAD(l.ID, 5, '0') LICITACAO_ID,
                L.DESCRICAO,
                L.USUARIO_ID,
                L.DATAHORA,
                L.DATA_VALIDADE,
                L.OBSERVACAO,
                (SELECT FIRST 1 (SELECT FIRST 1 OC FROM TBORCAMENTO_ITEM I WHERE I.ORCAMENTO_ID = O.ID)OC
                 FROM  TBORCAMENTO O
                 WHERE O.LICITACAO_ID = L.ID)OC
            FROM
                TBLICITACAO L
            WHERE
                L.ID = :ID
		";

		$args1 = array(':ID' => $id);

		return $con->query($sql1, $args1);
	}
	
	/**
	 * Retorna requisições com orçamento.
	 * Função complementar à 'exibir'.
	 * 
	 * @param int $id
	 * @param _Conexao $con
	 * @return array
	 */
	public static function exibirReq($id, _Conexao $con) {
		
		$sql3 = "
			select distinct
				lpad(r.ID, 5, '0') REQUISICAO_ID, r.URGENCIA, r.DATA, 
				coalesce(r.DATA_UTILIZACAO, '0') DATA_UTILIZACAO,
				(select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID)
			from TBREQUISICAO_OC r
			inner join TBREQUISICAO_OC_ITEM i on i.REQUISICAO_ID = r.ID
			where i.LICITACAO_ID = :lic_id
			order by 1 desc
		";

		$args3 = array(':lic_id' => $id);

		return $con->query($sql3, $args3);
			
	}
	
	/**
	 * Retorna orçamento.
	 * Função complementar à 'exibir'.
	 * 
	 * @param int $id
	 * @param _Conexao $con
	 * @return array
	 */
	public static function exibirOrc($id, _Conexao $con) {
		
		$sql2 = "
			select lpad(o.ID, 5, '0') ORCAMENTO_ID, o.ID ORCAMENTO_ENCRYPT, lpad(o.EMPRESA_ID, 5, '0') EMPRESA_ID, o.VALIDADE_PROPOSTA, o.OBSERVACAO,
				e.RAZAOSOCIAL, e.EMAIL, e.FONE, e.CONTATO, e.CIDADE, e.UF
			from TBORCAMENTO o
			inner join TBEMPRESA e on e.CODIGO = o.EMPRESA_ID
			where o.LICITACAO_ID = :lic_id
			order by 1 DESC
		";

		$args2 = array(':lic_id' => $id);

		return $con->query($sql2, $args2);
			
	}
	
	/**
	 * Retorna item de requisição.
	 * Função complementar à 'exibir'.
	 * 
	 * @param int $id
	 * @param _Conexao $con
	 * @return array
	 */
	public static function exibirOrcItem($id, _Conexao $con) {
		
		$sql = "
            SELECT
                DISTINCT
                LPAD(R.ID, 5, '0') REQUISICAO_ID,
                R.URGENCIA,
                R.DATA,
                COALESCE(R.DATA_UTILIZACAO, '0') DATA_UTILIZACAO,
                (SELECT FIRST 1 U.NOME from TBUSUARIO U WHERE U.CODIGO = R.USUARIO_ID) USUARIO,
                LPAD(I.ID, 5, '0') REQUISICAO_ITEM_ID,
                I.LICITACAO_ID,
                LPAD(I.PRODUTO_ID, 6, '0') PRODUTO_ID,
                I.PRODUTO_DESCRICAO,
                I.UM,
                I.TAMANHO,
                I.QUANTIDADE,
                I.VALOR_UNITARIO,
				I.OBSERVACAO,
				I.OPERACAO_CODIGO,
				I.OPERACAO_CCUSTO,
				I.OPERACAO_CCONTABIL

            FROM
                TBREQUISICAO_OC R
                INNER JOIN TBREQUISICAO_OC_ITEM I
                ON I.REQUISICAO_ID = R.ID
            WHERE
                I.LICITACAO_ID = :LICITACAO_ID

            ORDER BY
                1 DESC
		";

		$args = array(':LICITACAO_ID' => $id);

		return $con->query($sql, $args);		
	}
    
    /**
     * Retorna OC's de uma licitação
     * @param int $lic_id
     * @param _Conexao $conexao
     * @return array
     */
    public static function exibirOc($lic_id, _Conexao $conexao = null) {
        
        if ( !$conexao ) {
            $con = new _Conexao();
        } else {
            $con = $conexao;
        }
        
        $sql =
        "
            SELECT
                X.OC ID,
                X.FAMILIAS

            FROM
                (SELECT
                    I.OC,
                
                    (SELECT
                        LIST(DISTINCT F.DESCRICAO, ', ')
                    FROM
                        TBOC_ITEM O,
                        TBPRODUTO P,
                        TBFAMILIA F
                    WHERE
                        P.CODIGO = O.PRODUTO_CODIGO
                    AND F.CODIGO = P.FAMILIA_CODIGO
                    AND O.OC = I.OC )FAMILIAS,
                
                    (SELECT FIRST 1 O.LICITACAO_ID FROM TBORCAMENTO O WHERE O.ID = I.ID)LICITACAO_ID
                 FROM
                    TBORCAMENTO_ITEM I)X
            WHERE
                X.LICITACAO_ID = :LICITACAO_ID            
        ";        
        
        $args = array(
          ':LICITACAO_ID'   => $lic_id
        );
        
        return $con->query($sql,$args);
    }

    /**
     * Alterar dados do objeto na base de dados.
     * 
     * @param _13020 $obj
     */
    public static function alterar(_13020 $obj) {
        
        $con = new _Conexao();
//		$con_slave = new _Conexao('SLAVE');

        try {
			
			self::alterarLic($obj, $con);
            self::alterarOrc($obj, $con);
			self::excluirEmpresa($obj, $con);
			self::excluirProduto($obj, $con);
			
//          self::alterarLic($obj, $con, $con_slave);
//          self::alterarOrc($obj, $con, $con_slave);
//			self::excluirEmpresa($obj, $con, $con_slave);
//			self::excluirProduto($obj, $con, $con_slave);

            $con->commit();
//			$con_slave->commit();

        } catch(Exception $e) {

            $con->rollback();
//			$con_slave->rollback();
            throw $e;

        }

    }
	
	/**
	 * Alterar licitação.
	 * Função complementar à 'alterar'.
	 * 
	 * @param _13020 $obj
	 * @param _Conexao $con
	 */
	public static function alterarLic(_13020 $obj, _Conexao $con) {
		
		$sql = '
            UPDATE TBLICITACAO L SET
            L.DESCRICAO     = :DESCRICAO,
            L.USUARIO_ID    = :USUARIO_ID,
            L.DATA_VALIDADE = :DATA_VALIDADE,
            L.OBSERVACAO    = :OBSERVACAO
            WHERE L.ID      = :ID
		';

		$args = array(
			':DESCRICAO'        => $obj->getLicitacaoDescricao(),
			':USUARIO_ID'       => $obj->getUsuarioId(),
			':DATA_VALIDADE'	=> $obj->getDataValidade(),
			':OBSERVACAO'		=> $obj->getObservacao(),
			':ID'               => $obj->getLicitacaoId()
		);

		$con->execute($sql, $args);		
//		$con_slave->execute($sql, $args);
		
	}

	/**
	 * Alterar orçamento.
	 * Função complementar à 'alterar'.
	 * 
	 * @param _13020 $obj
	 * @param _Conexao $con
	 */
	public static function alterarOrc(_13020 $obj, _Conexao $con) {
		
        $i = 0;
        $orc_id = 0; //necessário para o momento em que um fornecedor for adicionado
        foreach ($obj->getEmpresaId() as $emp_id) {

            //se o fornecedor não está na licitação, deve ser adicionado. Senão, apenas alterado.
            if (empty($obj->getOrcamentoId()[$i])) {

                $orc_id	= self::gerarIdOrcamento();

                $sql = '
                    insert into TBORCAMENTO (ID, LICITACAO_ID, EMPRESA_ID) values (:orc_id, :lic_id, :emp_id)
                ';

                $args = array(
                    ':orc_id' => $orc_id,
                    ':lic_id' => $obj->getLicitacaoId(),
                    ':emp_id' => $emp_id
                );

                $con->execute($sql, $args);
				//$con_slave->execute($sql, $args);

            } else {
                $orc_id = $obj->getOrcamentoId()[$i];
            }

            //item do orçamento
			self::alterarOrcItem($obj, $con, $orc_id, $i);
			//self::alterarOrcItem($obj, $con, $con_slave, $orc_id, $i);

            $i++;
        }
		
    }
	
	/**
	 * Alterar item do orçamento.
	 * Função complementar à 'alterarOrc'.
	 * 
	 * @param _13020 $obj
	 * @param _Conexao $con
	 * @param int $orc_id
	 * @param int $i
	 */
	public static function alterarOrcItem(_13020 $obj, _Conexao $con, $orc_id, $i) {
		
		//item do orçamento
		$j = 0;
		foreach ($obj->getProdutoId() as $prod_id) {			
			
			//se o produto não está na licitação, deve ser adicionado. Senão, apenas alterado.
			if (empty($obj->getProdutoLicitacao()[$j]) || empty($obj->getOrcamentoId()[$i])) {

				$sql1 = '
					insert into TBORCAMENTO_ITEM (ORCAMENTO_ID, PRODUTO_ID, PRODUTO_INFO, UM, TAMANHO, QUANTIDADE, REQUISICAO_ID, OPERACAO_CODIGO, OPERACAO_CCUSTO, OPERACAO_CCONTABIL)
					values (:orc_id, :prod_id, :prod_info, :um, :tam, :qtd, :req_id, :operacao, :oper_ccusto, :oper_ccontabil)
				';
				
				$args1 = array(
					':orc_id'		=> $orc_id,
					':prod_id'		=> $prod_id,
					':prod_info'	=> $obj->getProdutoInfo()[$j],
					':um'			=> $obj->getUm()[$j],
					':tam'			=> $obj->getTamanho()[$j],
					':qtd'			=> $obj->getQuantidade()[$j],
					':req_id'		=> $obj->getRequisicaoId()[$j],
					':operacao'		=> $obj->getOperacaoCodigo()[$j],
					':oper_ccusto'	=> $obj->getOperacaoCcusto()[$j],
					':oper_ccont'	=> $obj->getOperacaoCcontabil()[$j]
				);

				$con->execute($sql1, $args1);
				//$con_slave->execute($sql1, $args1);


				$sql2 = '
					update TBREQUISICAO_OC_ITEM r
					set r.LICITACAO_ID = :lic_id, r.FLAG = 1
					where r.REQUISICAO_ID = :req_id
					and r.PRODUTO_ID = :prod_id
				';

				$args2 = array(
					':lic_id'	=> $obj->getLicitacaoId(),
					':req_id'	=> $obj->getRequisicaoId()[$j],
					':prod_id'	=> $prod_id
				);

				$con->execute($sql2, $args2);

			} else {

				$sql3 = '
					update TBORCAMENTO_ITEM i
					set 
						i.UM = :um, 
						i.TAMANHO = :tam, 
						i.QUANTIDADE = :qtd,
						i.OPERACAO_CODIGO = :operacao, 
						i.OPERACAO_CCUSTO = :oper_ccusto, 
						i.OPERACAO_CCONTABIL = :oper_ccont
					where 
						i.ORCAMENTO_ID = :orc_id 
					and i.PRODUTO_ID = :prod_id
				';

				$args3 = array(
					':um'			=> $obj->getUm()[$j],
					':tam'			=> $obj->getTamanho()[$j],
					':qtd'			=> $obj->getQuantidade()[$j],
					':operacao'		=> $obj->getOperacaoCodigo()[$j],
					':oper_ccusto'	=> $obj->getOperacaoCcusto()[$j],
					':oper_ccont'	=> $obj->getOperacaoCcontabil()[$j],
					':orc_id'		=> $orc_id,
					':prod_id'		=> $prod_id
				);

				$con->execute($sql3, $args3);
				//$con_slave->execute($sql3, $args3);
			}

			$j++;
		}

	}

	/**
     * Exclui empresa do orçamento na base de dados. 
	 * Ou seja, um orçamento será removido.
	 * Função complementar à 'alterar'.
     *
     * @param int $obj
	 * @param _Conexao $con
     */
    public static function excluirEmpresa(_13020 $obj, _Conexao $con) {

		$i = 0;
		foreach($obj->getEmpresaExcluir() as $exc) {
			
			if($exc === '1') {

				$sql = "
					delete from TBORCAMENTO where ID = :id
				";
				$args = array(':id' => $obj->getOrcamentoId()[$i]);

				$con->execute($sql, $args);
//				$con_slave->execute($sql, $args);
			}
			
			$i++;
		}
		
    }

    /**
     * Exclui produto do orçamento na base de dados.
	 * Função complementar à 'alterar'.
     *
     * @param _13020 $obj
	 * @param _Conexao $con
     */
    public static function excluirProduto(_13020 $obj, _Conexao $con) {
					
		$i = 0;
		foreach($obj->getProdutoExcluir() as $prod) {

			if($prod === '1') {
				
				$sql = '
					delete from TBORCAMENTO_ITEM I 
					where I.PRODUTO_ID = :prod_id and I.REQUISICAO_ID = :req_id
				';
				
				$args = array(
					':prod_id'	=> $obj->getProdutoId()[$i],
					':req_id'	=> $obj->getRequisicaoId()[$i]
				);

				$con->execute($sql, $args);
//				$con_slave->execute($sql, $args);
				
				
				//remove licitação do item de requisição
				$sql2 = '
					update TBREQUISICAO_OC_ITEM r
					set r.LICITACAO_ID = null
					where r.ID = :req_item_id
				';

				$args2 = array(
					':req_item_id' => $obj->getRequisicaoItemId()[$i]
				);

				$con->execute($sql2, $args2);
			}
			
			$i++;
		}
		
    }
	
    /**
     * Exclui dados do objeto na base de dados.
     * 
     * @param int $id
     */
    public static function excluir($id) {
		
		$con = new _Conexao();
//		$con_slave = new _Conexao('SLAVE');

        try {
            
            //licitação
            $sql1 = 'delete from TBLICITACAO where ID = :id';
			$args1 = array(':id' => $id);

			$con->execute($sql1, $args1);
//			$con_slave->execute($sql1, $args1);

            $con->commit();
//			$con_slave->commit();

        } catch(Exception $e) {

            $con->rollback();
//			$con_slave->rollback();
            throw $e;

        }

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

		$con = new _Conexao();
			
		$sql = "
			SELECT FIRST :QTD SKIP :PAG
				LPAD(L.ID, 5, '0') ID, L.DESCRICAO, L.DATAHORA, L.DATA_VALIDADE,
				(SELECT U.NOME REQUERENTE FROM TBUSUARIO U WHERE U.CODIGO = L.USUARIO_ID),
				(SELECT LIST(DISTINCT F.DESCRICAO, ', ')
					FROM   TBREQUISICAO_OC_ITEM I,
						   TBPRODUTO P,
						   TBFAMILIA F
					WHERE  P.CODIGO = I.PRODUTO_ID
					  AND  F.CODIGO = P.FAMILIA_CODIGO
					  AND  I.LICITACAO_ID = L.ID
					ORDER BY 1) FAMILIAS
			FROM TBLICITACAO L
			ORDER BY 1 DESC
		";

		$args = array(
			':QTD' => $qtd_por_pagina, 
			':PAG' => $pagina
		);

		return $con->query($sql, $args);
		
    }

    /**
     * Filtrar lista de requisições.
     * Função chamada via Ajax.
     *
     * @param string $filtro
     * @return array
     */
    public static function filtraObj($filtro) {

		$con = new _Conexao();
			
		$first = empty($filtro) ? 'first 30' : '';

		$sql = "
			SELECT $first 
				LPAD(X.ID, 5, '0') ID, X.DESCRICAO, X.DATAHORA, X.DATA_VALIDADE, X.REQUERENTE, X.FAMILIAS
			
			FROM (
				SELECT DISTINCT
					L.ID, L.DESCRICAO, L.DATAHORA, L.DATA_VALIDADE,
					(SELECT U.NOME REQUERENTE FROM TBUSUARIO U WHERE U.CODIGO = L.USUARIO_ID),
					(SELECT LIST(DISTINCT F.DESCRICAO, ', ')
						FROM   TBREQUISICAO_OC_ITEM I,
							   TBPRODUTO P,
							   TBFAMILIA F
						WHERE  P.CODIGO = I.PRODUTO_ID
						  AND  F.CODIGO = P.FAMILIA_CODIGO
						  AND  I.LICITACAO_ID = L.ID
						ORDER BY 1) FAMILIAS
				FROM TBLICITACAO L
			) X
			
			WHERE 
				(X.ID LIKE :ID) OR
			    (X.DESCRICAO LIKE :DESC) OR
				(X.FAMILIAS LIKE :FAM) OR
				(X.REQUERENTE LIKE :REQ) OR
				(LPAD(EXTRACT(DAY FROM X.DATAHORA), 2, '0')||'/'||LPAD(EXTRACT(MONTH FROM X.DATAHORA), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATAHORA)) LIKE :DATA OR
				(LPAD(EXTRACT(DAY FROM X.DATA_VALIDADE), 2, '0')||'/'||LPAD(EXTRACT(MONTH FROM X.DATA_VALIDADE), 2, '0')||'/'||EXTRACT(YEAR FROM X.DATA_VALIDADE)) LIKE :VAL
			
			ORDER BY 1 DESC
		";

		$args = array(
			':ID'	=> '%'.$filtro.'%',
			':DESC'	=> '%'.$filtro.'%',
			':FAM'	=> '%'.$filtro.'%',
			':REQ'	=> '%'.$filtro.'%',
			':DATA' => '%'.$filtro.'%',
			':VAL'  => '%'.$filtro.'%'
		);

		return $con->query($sql, $args);
		
    }

    /**
     * Lista requisições pendentes no banco de dados.
     *
     * @return array
     */
    public static function listaRequisicaoPendente() {
		
		$con = new _Conexao();
	
		return array(
			'req'		=> self::listaReqPend($con), 
			'req_item'	=> self::listaReqItemPend($con)
		);
	
    }
	
	/**
	 * Retorna requisições pendentes.
	 * Função complementar à 'listaRequisicaoPendente'
	 * 
	 * @param _Conexao $con
	 * @return type
	 */
	public static function listaReqPend(_Conexao $con) {
		
		$sql = "
			select distinct lpad(r.ID, 5, '0') ID, 
				r.URGENCIA, 
			    lpad(r.EMPRESA_ID, 5, '0') EMPRESA_ID,
			    r.EMPRESA_DESCRICAO, 
			    r.DATA, 
			    r.DATA_UTILIZACAO,
			    (select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID),
			    (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO)

			from 
				TBREQUISICAO_OC r
			    left join TBREQUISICAO_OC_ITEM i on i.REQUISICAO_ID = r.ID
				left join TBOC o on o.REFERENCIA = 'R' AND O.REFERENCIA_ID = R.ID

			where 
				i.LICITACAO_ID is null
			and o.OC is null
			and r.NECESSITA_LICITACAO = '1'

			order by 1 desc
		";

		return $con->query($sql);
			
	}
	
	/**
	 * Retorna itens de requisições pendentes.
	 * Função complementar à 'listaRequisicaoPendente'
	 * 
	 * @param _Conexao $con
	 * @return type
	 */
	public static function listaReqItemPend(_Conexao $con) {
		
		$sql = "
			select 
			    lpad(i.ID, 5, '0') ID,
			    lpad(i.REQUISICAO_ID, 5, '0') REQUISICAO_ID, 
			    lpad(i.PRODUTO_ID, 6, '0') PRODUTO_ID,
			    i.PRODUTO_DESCRICAO, 
			    i.OBSERVACAO, 
			    i.UM, 
			    i.TAMANHO,
			    i.QUANTIDADE, 
			    i.VALOR_UNITARIO,
			    i.OPERACAO_CODIGO,
			    i.OPERACAO_CCUSTO,
			    i.OPERACAO_CCONTABIL

			from 
			    TBREQUISICAO_OC_ITEM i
			    left join TBREQUISICAO_OC r ON r.ID = i.REQUISICAO_ID
			    left join TBOC o on o.REFERENCIA = 'R' AND O.REFERENCIA_ID = i.REQUISICAO_ID

			where 
			    i.LICITACAO_ID is null
			and o.OC is null
			and r.NECESSITA_LICITACAO = '1'

			order by 1 desc
		";

		return $con->query($sql);
			
	}
	
	/**
	 * Editar dados do fornecedor.
	 * 
	 * @param _13020 $obj
	 */
	public static function editarDadosFornec(_13020 $obj) {
		
		$con = new _Conexao();
		
		try {
		
			$sql = "
				UPDATE TBEMPRESA E
				SET E.EMAIL = :EMAIL,
					E.FONE = :FONE,
					E.CONTATO = :CONTATO
				WHERE E.CODIGO = :ID
			";

			$args = array(
				':EMAIL'	=> $obj->getEmpresaEmail(),
				':FONE'		=> $obj->getEmpresaFone(),
				':CONTATO'	=> $obj->getEmpresaContato(),
				':ID'		=> $obj->getEmpresaId()[0]
			);

			$con->execute($sql, $args);
			$con->commit();
			
		} catch(Exception $e) {
			$con->rollback();
			throw $e;
		}
		
	}

}