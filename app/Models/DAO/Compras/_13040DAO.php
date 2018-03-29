<?php

namespace App\Models\DAO\Compras;

use Exception;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Compras\_13040;

class _13040DAO
{	
	
	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @param int $licitacao_id
	 * @return array
	 */
	public static function exibirProduto($licitacao_id) {
		
		$con = new _Conexao();
        $con_files = new _Conexao('FILES');
        
		$orcamento 				= self::queryOrcamento($con, $licitacao_id);		
		$orcamento_item			= self::queryOrcamentoItem($con, $licitacao_id);
		$orcamento_item_unico	= self::queryOrcamentoItemUnico($con, $licitacao_id);
        
        $vinc_id = '';
        $i = -1;
        foreach ($orcamento as $o) {
            
            if ( $o->VINCULO_ID > 0 ) {
                $i++;
                $vinc_id = $i == 0 ? $vinc_id = $o->VINCULO_ID : $vinc_id  . ',' . $o->VINCULO_ID;
            }
        }
		$arquivo_item			= self::queryArquivo($con_files, $vinc_id, 'ORCAMENTO');

        
		return array(
			'orcamento'				=> $orcamento,
			'orcamento_item'		=> $orcamento_item,
			'orcamento_item_unico'	=> $orcamento_item_unico,
			'arquivo_itens'			=> $arquivo_item
		);
	
	}
	
	/**
	 * Similar ao CREATE (CRIAR) do CRUD
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param _13040 $obj
	 */
	public static function gravar(_13040 $obj) {
		$con = new _Conexao();
		try {
            //Gravar OC
			$i = -1;
            foreach ( $obj->getFornecedorId() as $forn ) {                
                $i++;
                self::gravarOc($con, $obj, $i);
                
                //Gravar Item OC
                $j = -1;
                foreach ( $obj->getItemFornecedorId() as $forn_item ) {
                    $j++;
                    
                    if ( $forn == $forn_item ) {
                        self::gravarOcItem($con, $obj, $i, $j);
                    }
                }
            }

	    
            //Envia Email
            _13040::emailAutorizacao($obj, $con);
                
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
    
    /**
     * Retorna o nível da OC
     * @param int $oc_id
     * @return stdClass
     */
    public static function exibirNivelOc($oc_id) {
        $con = new _Conexao();
        
        return self::queryNivelOc($con, $oc_id);
    }
    
    /**
     * Retorna o Código do Usuário de Acordo com o Nível
     * @param int $nivel_usuario
     * @return stdClass
     */
    public static function exibirNivelUsuario($nivel_usuario) {
        $con = new _Conexao;
        
        return self::queryNivelUsuario($con, $nivel_usuario);
    }
    
    public static function exibirRequisicao($id) {
        
		$con = new _Conexao();

		$requisicao 		= self::queryRequisicao($con, $id);		
		$requisicao_item	= self::queryRequisicaoItem($con, $id);
        
        foreach ($requisicao_item as $o ) {
            $o->QUANTIDADE      = number_format($o->QUANTIDADE      , 4, ',', '.');
            $o->VALOR_UNITARIO  = number_format($o->VALOR_UNITARIO  , 4, ',', '.');
        }        
        
		return array(
			'requisicao'			=> $requisicao[0],
			'requisicao_item'		=> $requisicao_item
		);
    }

    public static function queryRequisicao(_Conexao $con, $id) {
        $sql =
        "
            SELECT
                COALESCE(O.EMPRESA_ID,0)EMPRESA_ID,
                O.EMPRESA_DESCRICAO,
                O.ESTABELECIMENTO_ID,

                COALESCE(
                (SELECT FIRST 1
                    (Select First 1 A.Nivel_Oc
                              From   TbFamilia A, TbProduto B
                              Where  A.Codigo = B.Familia_Codigo
                              And    B.Codigo = I.PRODUTO_ID)OC_NIVEL
                FROM  TBREQUISICAO_OC_ITEM I
                WHERE I.REQUISICAO_ID = O.ID
                ORDER BY 1),2)OC_NIVEL

            FROM
                TBREQUISICAO_OC O

            WHERE
                O.ID = :ID    
        ";
        
        $args = array(':ID' => $id);
        
        return $con->query($sql, $args);
    }    
    
    public static function queryRequisicaoItem(_Conexao $con, $id) {
        $sql =
        "
            SELECT
                I.PRODUTO_ID PRODUTO_ID,
                P.DESCRICAO  PRODUTO_DESCRICAO,
                I.OBSERVACAO,
                I.QUANTIDADE,
                I.TAMANHO,
                TAMANHO_GRADE(P.GRADE_CODIGO, I.TAMANHO) TAMANHO_DESCRICAO,
                I.VALOR_UNITARIO,
                I.OPERACAO_CODIGO,
                I.OPERACAO_CCUSTO,
                I.OPERACAO_CCONTABIL,
                0 PERCENTUAL_IPI,
                CAST('01/01/1989' AS DATE)DATA_ENTREGA,
                (SELECT FIRST 1 OC FROM TBOC O WHERE O.REFERENCIA = 'R' AND O.REFERENCIA_ID = I.REQUISICAO_ID)OC
            FROM
                TBREQUISICAO_OC_ITEM I,
                TBPRODUTO P
            WHERE
                I.PRODUTO_ID > 0
            AND P.CODIGO = I.PRODUTO_ID
            AND I.REQUISICAO_ID = :REQUISICAO_ID     
        ";
        
        $args = array(':REQUISICAO_ID' => $id);
        
        return $con->query($sql, $args);
    }

    /**
	 * Gravar OC.
	 * Função complementar à 'gravar'.
	 * 
	 * @param _Conexao $con
	 * @param _13040 $obj
	 */
	public static function gravarOc(_Conexao $con, _13040 $obj, $i){
		
        $sql = 
        "
            INSERT INTO TBOC (
                ESTABELECIMENTO_CODIGO, 
                OC, 
                FORNECEDOR_CODIGO, 
                TRANSPORTADORA_CODIGO, 
                FRETE, 
                VALOR_FRETE, 
                PAGAMENTO_FORMA, 
                PAGAMENTO_CONDICAO, 
                USUARIO_CODIGO,
                NIVEL_OC,
                REFERENCIA,
                REFERENCIA_ID
            ) VALUES (
                :ESTABELECIMENTO_ID, 
                :OC_ID, 
                :EMPRESA_ID, 
                :TRANSP_ID, 
                :FRETE, 
                :FRETE_VALOR,
                :FORMA_ID, 
                :CONDICAO_ID, 
                :USUARIO_ID,
                :OC_NIVEL,
                :REFERENCIA,
                :REFERENCIA_ID
            );
        ";

        $args = array(
            ':OC_ID'				=> $obj->getOc()                [$i],
            ':ESTABELECIMENTO_ID'	=> $obj->getEstabelecimentoId() [$i],
            ':EMPRESA_ID'			=> $obj->getFornecedorId()      [$i],
            ':TRANSP_ID'			=> $obj->getTransportadoraId()  [$i],
            ':FORMA_ID'				=> $obj->getPagamentoForma()    [$i],
            ':CONDICAO_ID'			=> $obj->getPagamentoCondicao() [$i],
            ':FRETE'				=> $obj->getFrete()             [$i],
            ':FRETE_VALOR'			=> $obj->getValorFrete()        [$i],				
            ':USUARIO_ID'			=> $obj->getUsuarioId()         [$i],				
            ':OC_NIVEL'         	=> $obj->getOcNivel()           [$i],				
            ':REFERENCIA'        	=> $obj->getReferencia()        [$i],				
            ':REFERENCIA_ID'      	=> $obj->getReferenciaId()      [$i]
        );

        $con->execute($sql, $args);
	}	
	
	/**
	 * Gravar item de OC.
	 * Função complementar à 'gravarOc'.
	 * 
	 * @param _Conexao $con
	 * @param _13040 $obj
	 * @param int $i
	 */
	public static function gravarOcItem(_Conexao $con, _13040 $obj, $i, $j)
    {   
        $pos = '';
        $val = '';
        
        if ($obj->getTamanho()[$j] && $obj->getQuantidade()[$j]) {
            for ($k = 1; $k <= 20 ; $k++) {
                if ( $obj->getTamanho()[$j] == $k ) {
                    $pos = 'T' . str_pad($k, 2,'0',0) . ',';
                    $val = $obj->getQuantidade()[$j] . ',';
                }
            }    
        }
            
        $sql = 
        "
        INSERT INTO TBOC_ITEM (
            ESTABELECIMENTO_CODIGO, 
            OC, 
            SEQUENCIA, 
            PRODUTO_CODIGO, 
            /*TAMANHO_POS*/
            FORNECEDOR_CODIGO, 
            QUANTIDADE, 
            IPI, 
            VALOR, 
            DATA_ENTREGA, 
            DATA_SAIDA, 
            CONTROLE, 
            DESCONTO, 
            CCUSTO, 
            OPERACAO_CODIGO, 
            CONTA_CONTABIL, 
            ORCAMENTO_ID
        ) VALUES (
            :ESTABELECIMENTO_CODIGO, 
            :OC, 
            :SEQUENCIA, 
            :PRODUTO_CODIGO, 
            /*TAMANHO_VAL*/
            :FORNECEDOR_CODIGO, 
            :QUANTIDADE, 
            :IPI, 
            :VALOR, 
            :DATA_ENTREGA, 
            :DATA_SAIDA, 
            :CONTROLE, 
            :DESCONTO, 
            :CCUSTO, 
            :OPERACAO_CODIGO, 
            :CONTA_CONTABIL, 
            :ORCAMENTO_ID
        );
        ";
        
        $sql = str_replace('/*TAMANHO_POS*/',   $pos, $sql);
        $sql = str_replace('/*TAMANHO_VAL*/',   $val, $sql);

        $args = array(
            ':OC'                       => $obj->getOc()                [$i],
            ':ESTABELECIMENTO_CODIGO'	=> $obj->getEstabelecimentoId() [$i],
            ':CONTROLE'                 => $obj->getControle()          [$j],
            ':SEQUENCIA'                => $obj->getSequencia()         [$j],
            ':FORNECEDOR_CODIGO'        => $obj->getFornecedorId()      [$i],
            ':CCUSTO'                   => $obj->getCcusto()            [$j],
            ':CONTA_CONTABIL'			=> $obj->getContaContabil()     [$j],
            ':OPERACAO_CODIGO'          => $obj->getOperacaoCodigo()    [$j],
            ':PRODUTO_CODIGO'           => $obj->getProdutoCodigo()     [$j],
            ':ORCAMENTO_ID'             => $obj->getOrcamentoId()       [$j],
            ':DATA_ENTREGA'             => $obj->getDataEntrega()       [$j],
            ':DATA_SAIDA'				=> $obj->getDataSaida()			[$j],
            ':QUANTIDADE'               => $obj->getQuantidade()        [$j],
            ':VALOR'                    => $obj->getValor()             [$j],
            ':IPI'                      => $obj->getIpi()               [$j],
            ':DESCONTO'                 => $obj->getDesconto()          [$j]
        );
                
        $con->execute($sql, $args);
	}
	
	
	/**
	 * Consulta Orçamentos
	 * @param _Conexao $con
	 * @param int $licitacao_id
	 * @return stdClass
	 */
	public static function queryOrcamento(_Conexao $con, $licitacao_id) {
		
		$sql = "
            SELECT
                O.ID,
                LPAD(O.EMPRESA_ID,4,'0')EMPRESA_ID,
                
                ((SELECT FIRST 1 NOMEFANTASIA 
                  FROM   TBEMPRESA 
                  WHERE  CODIGO = O.EMPRESA_ID) || ' / ' ||
                 (SELECT FIRST 1 UF
                  FROM   TBEMPRESA 
                  WHERE  CODIGO = O.EMPRESA_ID)) EMPRESA_DESCRICAO,
                
                O.FRETE,
                O.FRETE_VALOR,
                O.PAGAMENTO_CONDICAO,
                O.PAGAMENTO_FORMA,
                O.VALIDADE_PROPOSTA,
                O.PRAZO_ENTREGA,
                O.CONTATO,
                O.OBSERVACAO,
                COALESCE(o.VINCULO_ID,0) VINCULO_ID,
                O.STATUS_RESPOSTA,

                (SELECT FIRST 1 B.CODIGO
                FROM   TBOC A, TBTRANSPORTADORA B
                WHERE  A.TRANSPORTADORA_CODIGO = B.CODIGO
                   AND A.FORNECEDOR_CODIGO = O.EMPRESA_ID ORDER BY B.CODIGO DESC)TRANSPORTADORA_ID,

                (SELECT FIRST 1 (LPAD(B.CODIGO, 4, '0') || ' - ' || B.RAZAOSOCIAL)
                FROM   TBOC A, TBTRANSPORTADORA B
                WHERE  A.TRANSPORTADORA_CODIGO = B.CODIGO
                   AND A.FORNECEDOR_CODIGO = O.EMPRESA_ID ORDER BY B.CODIGO DESC)TRANSPORTADORA_DEFAULT,

                (SELECT FIRST 1 B.CODIGO
                FROM   TBOC A, TBPAGAMENTO_FORMA B
                WHERE  A.PAGAMENTO_FORMA = B.CODIGO
                   AND A.FORNECEDOR_CODIGO = O.EMPRESA_ID
                ORDER  BY A.OC DESC)PAGAMENTO_FORMA_ID,

                (SELECT FIRST 1 (LPAD(B.CODIGO, 2, '0') || ' - ' || B.DESCRICAO)
                FROM   TBOC A, TBPAGAMENTO_FORMA B
                WHERE  A.PAGAMENTO_FORMA = B.CODIGO
                   AND A.FORNECEDOR_CODIGO = O.EMPRESA_ID
                ORDER  BY A.OC DESC)PAGAMENTO_FORMA_DEFAULT,

                (SELECT FIRST 1 B.CODIGO
                FROM   TBOC A, TBPAGAMENTO_CONDICAO B
                WHERE  A.PAGAMENTO_CONDICAO = B.CODIGO
                   AND A.FORNECEDOR_CODIGO = O.EMPRESA_ID
                ORDER  BY A.OC DESC)PAGAMENTO_CONDICAO_ID,

                (SELECT FIRST 1 B.DESCRICAO
                FROM   TBOC A, TBPAGAMENTO_CONDICAO B
                WHERE  A.PAGAMENTO_CONDICAO = B.CODIGO
                   AND A.FORNECEDOR_CODIGO = O.EMPRESA_ID
                ORDER  BY A.OC DESC)PAGAMENTO_CONDICAO_DEFAULT,


                (SELECT FIRST 1
                    Coalesce((Select First 1
                                  (Select First 1 A.Nivel_Oc
                                   From   TbFamilia A, TbProduto B
                                   Where  A.Codigo = B.Familia_Codigo
                                   And    B.Codigo = I.PRODUTO_ID)Nivel
                              From  TbOc_Item X
                              Where X.Oc = I.ORCAMENTO_ID
                              Order By Nivel),'1')OC_NIVEL
                FROM  TBORCAMENTO_ITEM I
                WHERE I.ORCAMENTO_ID = O.ID
                ORDER BY 1)
                    
            FROM
                TBORCAMENTO O
                    
            WHERE
                O.LICITACAO_ID = :LICITACAO_ID
                
            ORDER BY 3
		";
			
		$args = array(':LICITACAO_ID' => $licitacao_id);
		
		return $con->query($sql, $args);			
	}
	
	/**
	 * Consulta Itens dos Orçamentos
	 * @param _Conexao $con
	 * @param int $licitacao_id
	 * @return stdClass
	 */
	public static function queryOrcamentoItem(_Conexao $con, $licitacao_id) {
	
		$sql = "
            SELECT
                ORCAMENTO_ID,
                LICITACAO_ID,
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
				PRODUTO_INFO,
				OBS_PRODUTO,
                TAMANHO,
                VALOR_UNITARIO,
                PERCENTUAL_IPI,
                DATA_ENTREGA,
                OC,
                NIVEL_OC,
                SUM(QUANTIDADE)QUANTIDADE

            FROM
               (SELECT
                    I.ORCAMENTO_ID,
                    O.LICITACAO_ID,
                    LPAD(I.PRODUTO_ID,5,'0') PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
					I.PRODUTO_INFO,
					I.OBS_PRODUTO,
                    IIF(I.TAMANHO < 1, NULL, I.TAMANHO)TAMANHO,
                    I.QUANTIDADE,
                    I.VALOR_UNITARIO,
                    I.PERCENTUAL_IPI,
                    O.PRAZO_ENTREGA DATA_ENTREGA,
                    I.OC,
                    (SELECT FIRST 1 F.NIVEL_OC FROM TBFAMILIA F WHERE F.CODIGO = P.FAMILIA_CODIGO) NIVEL_OC
    
                FROM
                    TBORCAMENTO_ITEM I,
                    TBORCAMENTO O,
                    TBPRODUTO P
    
                WHERE
                    O.ID = I.ORCAMENTO_ID
                AND P.CODIGO = I.PRODUTO_ID
                AND O.LICITACAO_ID = :LICITACAO_ID)X


            GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12
		";
			
		$args = array(':LICITACAO_ID' => $licitacao_id);
	
		return $con->query($sql, $args);
	}	
	
	/**
	 * Consulta distinta de itens
	 * @param _Conexao $con
	 * @param unknown $licitacao_id
	 */
	public static function queryOrcamentoItemUnico(_Conexao $con, $licitacao_id) {
	
		$sql = "
            SELECT
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
				PRODUTO_INFO,
				OPERACAO_CODIGO,
				OPERACAO_CCUSTO,
				OPERACAO_CCONTABIL,
                SUM(QUANTIDADE)QUANTIDADE

            FROM
           (SELECT
                LPAD(I.PRODUTO_ID,5,'0')PRODUTO_ID,
                (SELECT FIRST 1 DESCRICAO FROM TBPRODUTO WHERE CODIGO = I.PRODUTO_ID) PRODUTO_DESCRICAO,
				I.PRODUTO_INFO,
                I.QUANTIDADE,
				I.OPERACAO_CODIGO,
				I.OPERACAO_CCUSTO,
				I.OPERACAO_CCONTABIL
    
            FROM
                TBORCAMENTO_ITEM I,
                TBORCAMENTO O
    
            WHERE
                O.ID = I.ORCAMENTO_ID
            AND O.LICITACAO_ID = :LICITACAO_ID
                            
            ORDER BY 2)X

            GROUP BY 1,2,3,4,5,6
		";
			
		$args = array(':LICITACAO_ID' => $licitacao_id);
	
		return $con->query($sql, $args);
	}	
	
    public static function queryNivelOc(_Conexao $con, $oc_id) {
        $sql = "
            SELECT FIRST 1
                A.NIVEL_OC OC_NIVEL
            FROM
                TBOC A
            WHERE
                A.OC = :OC
        ";
        
		$args = array(
			':OC' => $oc_id
		);
		
		return $con->query($sql,$args);        
    }
    
    public static function queryNivelUsuario(_Conexao $con, $nivel_usuario) {
        $sql = "
            SELECT
                U.CODIGO,    
                U.USUARIO,
                U.NOME,
                U.EMAIL

            FROM
                TBUSUARIO U

            WHERE
                U.NIVEL_OC = :OC_NIVEL
        ";
        
		$args = array(
			':OC_NIVEL' => $nivel_usuario
		);
		
		return $con->query($sql,$args);        
    }    

    /**
	 * Consulta Arquivos por Orçamento
	 * @param _Conexao $con
	 * @param unknown $vinculo_id
	 * @param unknown $tabela
	 */
	public static function queryArquivo(_Conexao $con, $vinculo_id, $tabela) {
        
        $vinculo_id = $vinculo_id == '' ? '0' : $vinculo_id;
        
		$sql = "
    	    SELECT
				V.ID,
				V.ARQUIVO_ID,
				V.OBSERVACAO,
				V.USUARIO_ID,
                V.TABELA_ID
				
			FROM
				TBVINCULO V
				
    		WHERE
				V.TABELA 		= '" . $tabela . "'
			AND V.TABELA_ID    IN (" . $vinculo_id . ")
			AND STATUSVINCULO	= 1
        ";
		
		return $con->query($sql);
	}
	
	/**
	 * Gerar id do obj
	 */
	public static function gerarId()
	{
		$con = new _Conexao();
	
		$sql = '
			SELECT GEN_ID(GTBOC, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);
	
		return $qry[0]->ID;
	}	
}
?>