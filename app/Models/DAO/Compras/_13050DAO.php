<?php

namespace App\Models\DAO\Compras;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Compras\_13050;
use Exception;
use Illuminate\Support\Facades\Auth;

class _13050DAO
{	
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
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar(_13050 $obj = null)
	{
		$con = new _Conexao();
        
        if ( !$obj ) {
            $obj = new _13050;
        }
        
        $oc = _13050DaoSelect::oc($con,$obj);
        foreach ($oc as $o ) {
			$o->AUTORIZACAO			= (int)$o->AUTORIZACAO;
            $o->QTD_ITENS           = number_format($o->QTD_ITENS        , 4, ',', '.');
            $o->FRETE_VALOR         = number_format($o->FRETE_VALOR      , 4, ',', '.');
            $o->VALOR_SUBTOTAL      = number_format($o->VALOR_SUBTOTAL   , 4, ',', '.');
            $o->VALOR_DESCONTO      = number_format($o->VALOR_DESCONTO   , 4, ',', '.');
            $o->VALOR_ACRESCIMO     = number_format($o->VALOR_ACRESCIMO  , 4, ',', '.');
            $o->VALOR_IPI           = number_format($o->VALOR_IPI        , 4, ',', '.');
            $o->VALOR_TOTAL_GERAL   = number_format($o->VALOR_TOTAL_GERAL, 4, ',', '.');
        }
        
        $res = array('oc' => $oc);
        
        if ( $obj->getItens() ) {
            
            $itens = _13050DaoSelect::ocItem($con,$obj);
            foreach ($itens as $item ) {
				$item->QUANTIDADE   = number_format($item->QUANTIDADE  , 4, ',', '.');
                $item->VALOR        = number_format($item->VALOR       , 4, ',', '.');
                $item->IPI          = number_format($item->IPI         , 4, ',', '.');
                $item->ACRESCIMO    = number_format($item->ACRESCIMO   , 4, ',', '.');
                $item->DESCONTO     = number_format($item->DESCONTO    , 4, ',', '.');
                $item->TOTAL        = number_format($item->TOTAL       , 4, ',', '.');
                $item->DATA_SAIDA   = date_format(date_create($item->DATA_SAIDA  ), 'd/m/Y');
                $item->DATA_ENTREGA = date_format(date_create($item->DATA_ENTREGA), 'd/m/Y');
            }
            $res = $res+array('oc_item' => $itens);
            
            
            $pend = _13050DaoSelect::ocPendencia($con,$obj);
            foreach ($pend as $item ) {
				$item->VALOR    = number_format($item->VALOR, 4, ',', '.');
                $item->DATA     = date_format(date_create($item->DATA), 'd/m/Y');
            }
            $res = $res+array('oc_pendencia' => $pend);
           
        }        
        
        if ( $obj->getHistorico() ) {

            $hist = _13050DaoSelect::ocHistorico($con,$obj);
            $res = $res+array('oc_historico' => $hist);            
        }
        
        $nivel = _13050DaoSelect::nivelAutorizacao($con);
        $res = $res+array('nivel' => $nivel);
	
		return $res;
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
	
    public static function autorizar($param)
    {
        $con = new _Conexao();
        try {
            
            _13050DaoInsert::autorizacaoLog($con,$param);
            _13050DaoUpdate::autorizacao($con,$param);
            
            foreach ($param->ITENS as $i => $item) {
                _13050DaoUpdate::ocItemDivergente($con,$param,$i);
            }
                
            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    public static function ocPendencia2($con, $id)
    { 
        $pend = _13050DaoSelect::ocPendencia2($con, $id);
        return $pend;
    }
	
    public static function enviarOc($id)
    {
		$con = new _Conexao();
		try {
			self::executeEnviarOc($con,$id);
            
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
    
    /**
	 * Gerar PDF de OC autorizada.
	 */
	public static function infoPdfOc(_13050 $obj)
    {
		
		$con = new _Conexao();
		
		$estab  = self::queryEstab($con);
		$fornec = self::queryFornec($con, $obj);
		
		return array(
			'estab'		=> $estab,
			'fornec'	=> $fornec
		);
	}
	
	public static function queryEstab(_Conexao $con)
    {
		
		$sql = "
			SELECT
				E.RAZAOSOCIAL, E.ENDERECO, E.NUMERO, E.BAIRRO, E.CEP, E.CIDADE, E.UF,
				E.CNPJ, E.IE, E.FONE, E.EMAIL, E.FAX
			FROM TBESTABELECIMENTO E
			WHERE E.CODIGO = 1		
		";
		
		return $con->query($sql);
	}
	
	public static function queryFornec(_Conexao $con, _13050 $obj)
    {
		
		$sql = "
			SELECT
				E.RAZAOSOCIAL, E.ENDERECO, E.NUMERO, E.BAIRRO, E.CEP, E.CIDADE,
				E.CNPJ, E.IE, E.FONE, E.FAX, E.EMAIL
			FROM TBEMPRESA E
			WHERE E.CODIGO = :CODIGO
		";
		
		$args = array(':CODIGO' => $obj->getFornecedorId());
		
		return $con->query($sql, $args);
	}
    
    public static function executeEnviarOc($con,$id)
    {

        $sql = 
        "
            UPDATE TBOC
            SET OC_ENVIADA = :OC_ENVIADA
            WHERE OC = :OC;
        ";
        
		$args = array(
            ':OC'           => $id,
            ':OC_ENVIADA'   => '1'
		);

		$con->execute($sql, $args);
    }    
}

class _13050DaoSelect
{
    /**
     * Consulta Ordem de Compra
     * @param _Conexao $con Objecto de conexão
     * @param _13050 $obj
     * @return stdClass
     */
    public static function oc(_Conexao $con, _13050 $obj)
    {
        $filtro         = $obj->getFiltro()         == null ? ''         : ($obj->getFiltro()              ? "AND X.FILTRO LIKE '" . $obj->getFiltro() . "'"                : '');        
        $first          = $obj->getFirst()          == null ? 'FIRST 30' : ($obj->getFirst()         > 0   ? "FIRST " . $obj->getFirst()                                    : '');
        $skip           = $obj->getSkip()           == null ? ''         : ($obj->getSkip()          > 0   ? "SKIP " . $obj->getSkip()                                      : '');
        $id             = $obj->getId()             == null ? ''         : ($obj->getId()                  ? "AND X.ID = " . $obj->getId()                                  : '');
        $status         = $obj->getStatus()         == null ? ''         : ($obj->getStatus() == '1' ? "AND A.STATUS = '1'" : "AND A.STATUS = '0'");
        $data           = $obj->getDataInicial()    == null ? ''         : "AND A.DATA BETWEEN '" . $obj->getDataInicial() . "' AND '" . $obj->getDataFinal() . "'";
        $enviada        = $obj->getEnviada()        == null ? ''         : ($obj->getEnviada() == '1' ? "AND A.OC_ENVIADA = '1'" : "AND A.OC_ENVIADA = '0'");
        $autorizacao    = $obj->getAutorizacao()    == null ? ''         : "AND A.AUTORIZACAO IN (" . $obj->getAutorizacao() . ")";
        $pendencia      = $obj->getPendencia()      == null ? ''         : "AND COALESCE(A.NIVEL_OC,2) >= COALESCE((SELECT FIRST 1 IIF(CAST(NIVEL_OC AS INTEGER) = 0, 9999,CAST(NIVEL_OC AS INTEGER)) FROM TBUSUARIO WHERE CODIGO = " . Auth::getUser()->CODIGO . "),9999)";
        $item_pendente  = $obj->getItemPendente()   == null ? ''         : "AND ITEM_PENDENTE = '1'";
        
        $sql =
        "
            SELECT /*@FIRST*/ /*@SKIP*/
                ID,
                DATA,
                FAMILIAS,
                ESTABELECIMENTO_ID,
                ESTABELECIMENTO_DESCRICAO,
                FORNECEDOR_ID,
                FORNECEDOR_DESCRICAO,
                TRANSPORTADORA_ID,
                TRANSPORTADORA_DESCRICAO,
                FRETE_ID,
                FRETE_DESCRICAO,
                FRETE_VALOR,
                PAGAMENTO_FORMA_ID,
                PAGAMENTO_FORMA_DESCRICAO,
                PAGAMENTO_CONDICAO_ID,
                PAGAMENTO_CONDICAO_DESCRICAO,
                CAST(SUBSTRING(VALORES FROM   1 FOR 20) AS NUMERIC(18,4))QTD_ITENS,
                CAST(SUBSTRING(VALORES FROM  21 FOR 20) AS NUMERIC(18,4))VALOR_SUBTOTAL,
                CAST(SUBSTRING(VALORES FROM  41 FOR 20) AS NUMERIC(18,4))VALOR_DESCONTO,
                CAST(SUBSTRING(VALORES FROM  61 FOR 20) AS NUMERIC(18,4))VALOR_ACRESCIMO,
                CAST(SUBSTRING(VALORES FROM  81 FOR 20) AS NUMERIC(18,4))VALOR_IPI,
                CAST(SUBSTRING(VALORES FROM 101 FOR 20) AS NUMERIC(18,4))VALOR_TOTAL_GERAL,
                OBSERVACAO, 
                OBSERVACAO_INTERNA,
                USUARIO_COMPRADOR_ID,   
                USUARIO_COMPRADOR,
                REFERENCIA,
                REFERENCIA_ID,
                STATUS,
                AUTORIZACAO,
                NIVEL_OC,      
                OC_ENVIADA,
                ITEM_PENDENTE

            FROM
                (SELECT

                    A.OC ID,
                    A.DATA,     

                    (SELECT LIST(DISTINCT F.DESCRICAO, ', ') FROM   TBOC_ITEM O,TBPRODUTO P,TBFAMILIA F WHERE  P.CODIGO = O.PRODUTO_CODIGO AND F.CODIGO = P.FAMILIA_CODIGO AND O.OC = A.OC
                    ) FAMILIAS,

                    LPAD(A.ESTABELECIMENTO_CODIGO, 3,'0'
                    ) ESTABELECIMENTO_ID,

                    (SELECT FIRST 1 RAZAOSOCIAL||' ('||UF||')' From TBESTABELECIMENTO WHERE A.ESTABELECIMENTO_CODIGO = CODIGO
                    ) ESTABELECIMENTO_DESCRICAO,

                    LPAD(A.FORNECEDOR_CODIGO,5,'0'
                    ) FORNECEDOR_ID,

                    (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = A.FORNECEDOR_CODIGO
                    ) FORNECEDOR_DESCRICAO,

                    LPAD(A.TRANSPORTADORA_CODIGO,5,'0'
                    ) TRANSPORTADORA_ID,

                    (SELECT FIRST 1 RAZAOSOCIAL||' ('||UF||')' FROM TBEMPRESA WHERE A.TRANSPORTADORA_CODIGO = CODIGO
                    ) TRANSPORTADORA_DESCRICAO,

                    A.FRETE FRETE_ID,

                    (CASE WHEN A.FRETE = 1 THEN 'CIF - FORNECEDOR' ELSE 'FOB - DESTINATÁRIO' END
                    ) FRETE_DESCRICAO,

                    A.VALOR_FRETE FRETE_VALOR,

                    LPAD(A.PAGAMENTO_FORMA,2,'0'
                    ) PAGAMENTO_FORMA_ID,

                    (SELECT FIRST 1 DESCRICAO FROM TBPAGAMENTO_FORMA WHERE A.PAGAMENTO_FORMA = CODIGO
                    ) PAGAMENTO_FORMA_DESCRICAO,

                    A.PAGAMENTO_CONDICAO PAGAMENTO_CONDICAO_ID,

                    (SELECT FIRST 1 DESCRICAO FROM TBPAGAMENTO_CONDICAO WHERE A.PAGAMENTO_CONDICAO = CODIGO
                    ) PAGAMENTO_CONDICAO_DESCRICAO,
                              
                    A.OBSERVACAO,
                    A.OBSERVACAO_INTERNA,
                    A.REFERENCIA,
                    A.REFERENCIA_ID,
                    A.OC_ENVIADA,
                    A.STATUS,
                    A.AUTORIZACAO,
                    COALESCE(A.NIVEL_OC,2)NIVEL_OC,

                    A.USUARIO_CODIGO AS USUARIO_COMPRADOR_ID,
                    
                    (SELECT FIRST 1 IIF(NOME = '', USUARIO, NOME) USUARIO_COMPRADOR FROM   TBUSUARIO WHERE CODIGO = A.USUARIO_CODIGO
                    ) USUARIO_COMPRADOR,

                    (SELECT (LPAD(SUM(QUANTIDADE)            ,20,' ')|| /**ITENS*/
                             LPAD(SUM(VALOR*QUANTIDADE)      ,20,' ')|| /**VALOR_SUBTOTAL*/
                             LPAD(SUM(DESCONTO)              ,20,' ')|| /**VALOR_DESCONTO*/
                             LPAD(SUM(ACRESCIMO)             ,20,' ')|| /**VALOR_ACRESCIMO*/
                             LPAD(SUM((QUANTIDADE)*(IPI/100)),20,' ')|| /**VALOR_IPI*/
                             LPAD(SUM( (CAST(VALOR      AS NUMERIC(18,4))*
                                        CAST(QUANTIDADE AS NUMERIC(18,4))+
                                        CAST(ACRESCIMO  AS NUMERIC(18,4))-
                                        CAST(DESCONTO   AS NUMERIC(18,4)))+
                                      ((CAST(VALOR      AS NUMERIC(18,4))*
                                        CAST(QUANTIDADE AS NUMERIC(18,4)))*
                                       (CAST(IPI        AS NUMERIC(18,4))/100))),20,' ')) /**VALOR_TOTAL_GERAL*/
                     FROM TBOC_ITEM R WHERE OC = A.OC)VALORES,

                     (SELECT FIRST 1 SITUACAO FROM TBOC_ITEM I WHERE SITUACAO = '1' AND I.OC = A.OC)ITEM_PENDENTE,

                    (OC || ' ' || 
                    LPAD(EXTRACT(DAY FROM A.DATA),2,0)||'/'|| LPAD(EXTRACT(MONTH FROM A.DATA),2,0)||'/'|| LPAD(EXTRACT(YEAR FROM A.DATA),4,0)  || ' ' || 
                    (SELECT LIST(DISTINCT F.DESCRICAO, ', ') FROM   TBOC_ITEM O,TBPRODUTO P,TBFAMILIA F WHERE  P.CODIGO = O.PRODUTO_CODIGO AND F.CODIGO = P.FAMILIA_CODIGO AND O.OC = A.OC) || ' ' || 
                    LPAD(A.FORNECEDOR_CODIGO,5,'0') || ' ' || 
                    (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = A.FORNECEDOR_CODIGO)
                    ) FILTRO
    
                FROM
                    TBOC A
    
                WHERE   
                    1=1
                    /*@NIVEL_OC*/
                    /*@STATUS*/
                    /*@DATA*/
                    /*@ENVIADA*/
                    /*@AUTORIZACAO*/
                )X
            
            WHERE
                1=1
                /*@ITEM_PENDENTE*/
                /*@FILTRO*/
                /*@ID*/

            ORDER BY
                ID DESC
        ";
        
		
		$args = array(
            '@FIRST'         => $first        ,
            '@SKIP'          => $skip         ,
            '@STATUS'        => $status       ,
            '@DATA'          => $data         ,
            '@ENVIADA'       => $enviada      ,
            '@AUTORIZACAO'   => $autorizacao  ,
			'@NIVEL_OC'      => $pendencia    ,
            '@ID'            => $id           ,
            '@FILTRO'        => $filtro       ,
            '@ITEM_PENDENTE' => $item_pendente,
		);
		
        
		return $con->query($sql,$args);
    }
    

    public static function ocItem(_Conexao $con, _13050 $obj)
    {
        $id = $obj->getId() == null ? '' : ($obj->getId() ? "AND A.OC =  " . $obj->getId() : '');
        
        $sql =
        "
            SELECT
                SEQUENCIA,
                PRODUTO_ID,
                QUANTIDADE,
                IPI,
                VALOR,
                TOTAL,  
                GRADE_CODIGO,
                TAMANHO,
                TAMANHO_GRADE(GRADE_CODIGO,TAMANHO) TAMANHO_DESCRICAO,
                TAMANHO_EDI,
                OBSERVACAO,
                CONTROLE,
                DATA_SAIDA,
                DATA_ENTREGA,
                SITUACAO,
                PRODUTO_DESCRICAO,
                PRODUTO_INFO,
                PRODUTO_EDI,
                TOLERANCIA,
                ACRESCIMO,
                DESCONTO,
                PDESCONTO,
                SALDO,
                CLASSIFICACAO_FINANCEIRA,
                LOCALIZACAO_CODIGO,
                CCUSTO,
                CCUSTO_MASK,
                CCUSTO_DESCRICAO,
                OPERACAO_CODIGO,
                OPERACAO_DESCRICAO,
                CONTA_CONTABIL,
                REQUISICAO_ID

            FROM (
                SELECT 
                    LPAD(A.SEQUENCIA,3,'0') SEQUENCIA,
                    LPAD(A.PRODUTO_CODIGO,5,'0') PRODUTO_ID,

                    CAST(A.QUANTIDADE AS NUMERIC(18,4)) QUANTIDADE,
                    CAST(A.IPI AS NUMERIC(18,2)) IPI,
                    CAST(A.VALOR AS NUMERIC(18,5)) VALOR,

                    CAST (  (CAST((A.VALOR * A.QUANTIDADE) AS NUMERIC(18,4))) + (A.ACRESCIMO - A.DESCONTO) + ( (CAST((A.VALOR * A.QUANTIDADE) AS NUMERIC(18,4))) * (CAST((A.IPI/100) AS NUMERIC(10,4))) ) AS NUMERIC(18,2) ) TOTAL,

                    CASE
                    WHEN A.T01 > 0 THEN 01
                    WHEN A.T02 > 0 THEN 02
                    WHEN A.T03 > 0 THEN 03
                    WHEN A.T04 > 0 THEN 04
                    WHEN A.T05 > 0 THEN 05
                    WHEN A.T06 > 0 THEN 06
                    WHEN A.T07 > 0 THEN 07
                    WHEN A.T08 > 0 THEN 08
                    WHEN A.T09 > 0 THEN 09
                    WHEN A.T10 > 0 THEN 10
                    WHEN A.T11 > 0 THEN 11
                    WHEN A.T12 > 0 THEN 12
                    WHEN A.T13 > 0 THEN 13
                    WHEN A.T14 > 0 THEN 14
                    WHEN A.T15 > 0 THEN 15
                    WHEN A.T16 > 0 THEN 16
                    WHEN A.T17 > 0 THEN 17
                    WHEN A.T18 > 0 THEN 18
                    WHEN A.T19 > 0 THEN 19
                    WHEN A.T20 > 0 THEN 20
                    ELSE 0 END TAMANHO,

                    CASE
                    WHEN A.T01 > 0 THEN COALESCE((SELECT FIRST 1 B.T01 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T02 > 0 THEN COALESCE((SELECT FIRST 1 B.T02 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T03 > 0 THEN COALESCE((SELECT FIRST 1 B.T03 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T04 > 0 THEN COALESCE((SELECT FIRST 1 B.T04 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T05 > 0 THEN COALESCE((SELECT FIRST 1 B.T05 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T06 > 0 THEN COALESCE((SELECT FIRST 1 B.T06 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T07 > 0 THEN COALESCE((SELECT FIRST 1 B.T07 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T08 > 0 THEN COALESCE((SELECT FIRST 1 B.T08 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T09 > 0 THEN COALESCE((SELECT FIRST 1 B.T09 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T10 > 0 THEN COALESCE((SELECT FIRST 1 B.T10 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T11 > 0 THEN COALESCE((SELECT FIRST 1 B.T11 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T12 > 0 THEN COALESCE((SELECT FIRST 1 B.T12 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T13 > 0 THEN COALESCE((SELECT FIRST 1 B.T13 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T14 > 0 THEN COALESCE((SELECT FIRST 1 B.T14 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T15 > 0 THEN COALESCE((SELECT FIRST 1 B.T15 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T16 > 0 THEN COALESCE((SELECT FIRST 1 B.T16 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T17 > 0 THEN COALESCE((SELECT FIRST 1 B.T17 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T18 > 0 THEN COALESCE((SELECT FIRST 1 B.T18 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T19 > 0 THEN COALESCE((SELECT FIRST 1 B.T19 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    WHEN A.T20 > 0 THEN COALESCE((SELECT FIRST 1 B.T20 FROM TBPRODUTO_EDI B WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO),'')
                    ELSE '' END TAMANHO_EDI,


                    A.OBSERVACAO,A.CONTROLE,A.DATA_SAIDA,A.DATA_ENTREGA,A.SITUACAO,
                   (SELECT FIRST 1 (TRIM(B.DESCRICAO)||' / '||B.UNIDADEMEDIDA_SIGLA) FROM TBPRODUTO B WHERE A.PRODUTO_CODIGO = B.CODIGO) PRODUTO_DESCRICAO,
                   (SELECT FIRST 1 O.PRODUTO_INFO FROM TBORCAMENTO_ITEM O WHERE A.PRODUTO_CODIGO = O.PRODUTO_ID) PRODUTO_INFO,

                   (SELECT FIRST 1 (B.CODIGO||'-'||B.DESCRICAO||' / '||B.UNIDADEMEDIDA_SIGLA) FROM TBPRODUTO_EDI B 
                     WHERE A.PRODUTO_CODIGO = B.PRODUTO_CODIGO AND A.FORNECEDOR_CODIGO = B.EMPRESA_CODIGO) PRODUTO_EDI,


                   (SELECT FIRST 1 CAST(D.ALTURA AS NUMERIC(15,2)) FROM TBPRODUTO_FICHA D WHERE A.PRODUTO_CODIGO = D.PRODUTO_CODIGO) TOLERANCIA,

                   (SELECT FIRST 1 B.GRADE_CODIGO FROM TBPRODUTO B WHERE A.PRODUTO_CODIGO = B.CODIGO) GRADE_CODIGO,

                    A.ACRESCIMO, A.DESCONTO,

                    IIF((CAST((A.VALOR * A.QUANTIDADE) AS NUMERIC(18,4))) = 0, 0, CAST ( A.DESCONTO / (CAST((A.VALOR * A.QUANTIDADE) AS NUMERIC(18,4))) + (A.ACRESCIMO - A.DESCONTO) + ( (CAST((A.VALOR * A.QUANTIDADE) AS NUMERIC(18,4))) * (CAST((A.IPI/100) AS NUMERIC(10,4))) ) * 100 AS NUMERIC(18,2) ))  PDESCONTO,

                   (CASE WHEN A.SITUACAO = 1 THEN (SELECT FIRST 1 CAST(S.QUANTIDADE AS NUMERIC(15,4))  FROM TBOC_ITEM_SALDO S WHERE S.OC_ITEM_CONTROLE = A.CONTROLE) ELSE 0 END) SALDO,

                  A.CLASSIFICACAO_FINANCEIRA, A.LOCALIZACAO_CODIGO,

                  A.CCUSTO, 


                  IIF(CHAR_LENGTH(A.CCUSTO)=2,A.CCUSTO,
                  IIF(CHAR_LENGTH(A.CCUSTO)=5,SUBSTRING(A.CCUSTO FROM 1 FOR 2)||'.'||SUBSTRING(A.CCUSTO FROM 3 FOR 3),
                  IIF(CHAR_LENGTH(A.CCUSTO)=8,SUBSTRING(A.CCUSTO FROM 1 FOR 2)||'.'||SUBSTRING(A.CCUSTO FROM 3 FOR 3)||'.'||SUBSTRING(A.CCUSTO FROM 6 FOR 3),''))) CCUSTO_MASK,

                  IIF(CHAR_LENGTH(A.CCUSTO)=2,
                 (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 2)),
                  IIF(CHAR_LENGTH(A.CCUSTO)=5,
                 (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 2))||' - '||
                 (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 5)),
                  IIF(CHAR_LENGTH(A.CCUSTO)=8,
                 (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 5))||' - '||
                 (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 8)),''))) CCUSTO_DESCRICAO,

                  A.OPERACAO_CODIGO,
                  (SELECT FIRST 1 OP.DESCRICAO FROM TBOPERACAO OP WHERE OP.CODIGO = A.OPERACAO_CODIGO) OPERACAO_DESCRICAO,
                  A.CONTA_CONTABIL, 
                  (SELECT FIRST 1 O.REFERENCIA_ID FROM TBOC O WHERE O.OC = A.OC) REQUISICAO_ID

                FROM
                    TBOC_ITEM A
                WHERE 
                    A.PRODUTO_CODIGO > 0
                    /*ID*/
                ORDER BY 1) X         
        ";
        
        $sql = str_replace('/*ID*/', $id, $sql);
        
		return $con->query($sql);        
    }
    
    public static function ocPendencia(_Conexao $con, _13050 $obj)
    {
        $id = $obj->getId();
        
        $sql =
        "
            SELECT
                ID,
                PRODUTO_ID,
                DATA,
                FORNECEDOR_ID,
                FORNECEDOR_DESCRICAO,
                MAX(VALOR)VALOR

            FROM
                (SELECT
                    O.OC ID,
                    X.PRODUTO_CODIGO PRODUTO_ID,    
                    O.DATA DATA,
                    LPAD(O.FORNECEDOR_CODIGO,5,'0') FORNECEDOR_ID,
                    (SELECT FIRST 1 E.NOMEFANTASIA||' ('||E.UF||')' FROM TBEMPRESA E WHERE O.FORNECEDOR_CODIGO = E.CODIGO) FORNECEDOR_DESCRICAO,
                    CAST(OI.VALOR AS NUMERIC(15,4)) VALOR
    
                FROM
                    TBOC_ITEM OI,
                    TBOC O,
                    (SELECT
                        OI2.ESTABELECIMENTO_CODIGO,
                        OI2.OC,
                        OI2.PRODUTO_CODIGO,
                        CAST(OI2.VALOR AS NUMERIC(15,4)) VALOR_UNITARIO
                    FROM
                        TBOC_ITEM OI2
                    WHERE OI2.OC = :OC_1
                        AND OI2.SITUACAO = 1) X,
    
                    (SELECT
                        DISTINCT(OI3.PRODUTO_CODIGO) PRODUTO_CODIGO,
                        O3.ESTABELECIMENTO_CODIGO,
                        MAX(O3.OC) OC
                        /*MAX(O3.DATA) DATA*/
                    FROM
                        TBOC_ITEM OI3,
                        TBOC O3
                    WHERE
                        O3.OC = OI3.OC
                    AND O3.OC < :OC_2
                    AND O3.DATA > (CURRENT_DATE - 365)
                    AND O3.STATUS = 1
                    AND O3.AUTORIZACAO = '2'
                    AND OI3.VALOR_REF = '1'
                    GROUP BY 1,2
                    ORDER BY 3 DESC) Y
    
                WHERE
                    O.OC = OI.OC
                AND O.ESTABELECIMENTO_CODIGO = X.ESTABELECIMENTO_CODIGO
                AND O.ESTABELECIMENTO_CODIGO = Y.ESTABELECIMENTO_CODIGO
                AND OI.PRODUTO_CODIGO = X.PRODUTO_CODIGO
                AND OI.PRODUTO_CODIGO = Y.PRODUTO_CODIGO
                AND O.STATUS = 1
                AND O.AUTORIZACAO = '2'
                AND O.OC = Y.OC
                AND O.OC < X.OC
                /*AND O.DATA = Y.DATA*/
                AND CAST(OI.VALOR AS NUMERIC(15,4)) < X.VALOR_UNITARIO)Z

            GROUP BY 1,2,3,4,5
        ";
        
        $args = array(
            ':OC_1' => $id,
            ':OC_2' => $id
        );
        
        return $con->query($sql, $args);
    }

    public static function ocPendencia2(_Conexao $con, $id)
    {
        
        $sql =
        "
            SELECT
                l.produto_codigo,
                l.valor
            from

            (
                SELECT
                    ID,
                    PRODUTO_ID,
                    DATA,
                    FORNECEDOR_ID,
                    FORNECEDOR_DESCRICAO,
                    MAX(VALOR)VALOR

                FROM
                    (SELECT
                        O.OC ID,
                        X.PRODUTO_CODIGO PRODUTO_ID,    
                        O.DATA DATA,
                        LPAD(O.FORNECEDOR_CODIGO,5,'0') FORNECEDOR_ID,
                        (SELECT FIRST 1 E.NOMEFANTASIA||' ('||E.UF||')' FROM TBEMPRESA E WHERE O.FORNECEDOR_CODIGO = E.CODIGO) FORNECEDOR_DESCRICAO,
                        CAST(OI.VALOR AS NUMERIC(15,4)) VALOR

                    FROM
                        TBOC_ITEM OI,
                        TBOC O,
                        (SELECT
                            OI2.ESTABELECIMENTO_CODIGO,
                            OI2.OC,
                            OI2.PRODUTO_CODIGO,
                            CAST(OI2.VALOR AS NUMERIC(15,4)) VALOR_UNITARIO
                        FROM
                            TBOC_ITEM OI2
                        WHERE OI2.OC = :OC_1
                            AND OI2.SITUACAO = 1) X,

                        (SELECT
                            DISTINCT(OI3.PRODUTO_CODIGO) PRODUTO_CODIGO,
                            O3.ESTABELECIMENTO_CODIGO,
                            MAX(O3.OC) OC
                            /*MAX(O3.DATA) DATA*/
                        FROM
                            TBOC_ITEM OI3,
                            TBOC O3
                        WHERE
                            O3.OC = OI3.OC
                        AND O3.OC < :OC_2
                        AND O3.DATA > (CURRENT_DATE - 365)
                        AND O3.STATUS = 1
                        AND O3.AUTORIZACAO = '2'
                        AND OI3.VALOR_REF = '1'
                        GROUP BY 1,2
                        ORDER BY 3 DESC) Y

                    WHERE
                        O.OC = OI.OC
                    AND O.ESTABELECIMENTO_CODIGO = X.ESTABELECIMENTO_CODIGO
                    AND O.ESTABELECIMENTO_CODIGO = Y.ESTABELECIMENTO_CODIGO
                    AND OI.PRODUTO_CODIGO = X.PRODUTO_CODIGO
                    AND OI.PRODUTO_CODIGO = Y.PRODUTO_CODIGO
                    AND O.STATUS = 1
                    AND O.AUTORIZACAO = '2'
                    AND O.OC = Y.OC
                    AND O.OC < X.OC
                    /*AND O.DATA = Y.DATA*/
                    AND CAST(OI.VALOR AS NUMERIC(15,4)) < X.VALOR_UNITARIO)Z

                GROUP BY 1,2,3,4,5
            ) a, tboc_item l
            where l.oc = :OC_3
            and l.produto_codigo = a.PRODUTO_ID
            and l.valor > a.VALOR
        ";
        
        $args = array(
            ':OC_1' => $id,
            ':OC_2' => $id,
            ':OC_3' => $id
        );
        
        return $con->query($sql, $args);
    }
    
    public static function ocHistorico($con,$obj)
    {
        $sql =
        "
            SELECT
                A.NIVEL,
                B.DESCRICAO NIVEL_DESCRICAO,
                A.AUTORIZACAO,
                IIF(A.AUTORIZACAO = '2','Autorizado',
                IIF(A.AUTORIZACAO = '3','Reprovado',''))AUTORIZACAO_DESCRICAO,
                A.USUARIO_ID,
                C.NOME USUARIO_NOME,
                A.DATAHORA,
                A.OBSERVACAO

            FROM
                TBOC_AUTORIZACAO A,
                TBOC_NIVEL_AUTORIZACAO B,
                TBUSUARIO C

            WHERE
                A.OC = :ID
            AND B.ID = A.NIVEL
            AND C.CODIGO = A.USUARIO_ID     
            
            ORDER BY DATAHORA DESC
        ";    
        
		$args = array(':ID' => $obj->getId());
		
		return $con->query($sql, $args);
    }
    
	public static function nivelAutorizacao(_Conexao $con) {
		
		$sql = "
            SELECT N.ID, N.DESCRICAO
            FROM   TBOC_NIVEL_AUTORIZACAO N
		";
		
		return $con->query($sql);
	}
}

class _13050DaoInsert
{
    /**
     * Registra log de autorização de ordem de compra
     * @param _Conexao $con Objecto de conexão
     * @param type $param
     */
    public static function autorizacaoLog($con,$param)
    {
        $sql = 
        "
            INSERT INTO
            TBOC_AUTORIZACAO (
                OC,
                NIVEL,
                AUTORIZACAO,
                USUARIO_ID,
                OBSERVACAO
            ) VALUES (
                :OC,
                :NIVEL,
                :AUTORIZACAO,
                :USUARIO_ID,
                :OBSERVACAO
            );
        ";
        
		$args = array(
            ':OC'          => $param->ID,
            ':NIVEL'       => $param->USUARIO_NIVEL,
            ':AUTORIZACAO' => $param->TIPO,
            ':USUARIO_ID'  => Auth::user()->CODIGO,
            ':OBSERVACAO'  => $param->OBS,
		);

		$con->execute($sql, $args);
    }
}

class _13050DaoUpdate
{
    /**
     * Autoriza/Reprova ordem de compra
     * @param _Conexao $con Objecto de conexão
     * @param type $param
     */
    public static function autorizacao($con,$param)
    {
        $autorizacao = $param->AUTORIZACAO ? ', AUTORIZACAO = ' . $param->AUTORIZACAO : '';
        
        $sql = 
        "
            UPDATE TBOC
            SET NIVEL_OC = :NIVEL_OC
                /*@AUTORIZACAO*/
            WHERE ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO_ID
              AND OC                     = :OC;           
        ";
        
		$args = array(
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':OC'                 => $param->ID,
            ':NIVEL_OC'           => $param->PROXIMO_NIVEL,
            '@AUTORIZACAO'        => $autorizacao,
		);

		$con->execute($sql, $args);
    }
    
    /**
     * Marca o item da ordem de compra para que não seja referência de menor preço
     * para as proximas ordens de compra do mesmo produto
     * @param _Conexao $con
     * @param (object)array $param
     * @param integer $i chave do array de itens
     */
    public static function ocItemDivergente($con,$param,$i)
    {
        $sql = 
        "
            UPDATE TBOC_ITEM
            SET VALOR_REF = 0
            WHERE ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO_ID
              AND OC                     = :OC
              AND SEQUENCIA              = :SEQUENCIA
        ";
        
        $args = array(
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':OC'                 => $param->ID,
            ':SEQUENCIA'          => $param->ITENS[$i],
        );

        $con->execute($sql, $args);
    }

    /**
     * Marca o item da ordem de compra para que não seja referência de menor preço
     * para as proximas ordens de compra do mesmo produto
     * @param _Conexao $con
     * @param (object)array $param
     * @param integer $i chave do array de itens
     */
    public static function ocItemDivergente2($con,$param)
    {
        $sql = 
        "
            UPDATE TBOC_ITEM
            SET VALOR_REF = 0
            WHERE ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO_ID
              AND OC                     = :OC
        ";
        
        $args = array(
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':OC'                 => $param->ID
        );

        $con->execute($sql, $args);
    }
}

class _13050DaoDelte
{
    
}