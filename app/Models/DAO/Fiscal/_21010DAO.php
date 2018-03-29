<?php

namespace App\Models\DAO\Fiscal;

use Exception;
use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Fiscal\_21010;
use Illuminate\Support\Facades\Auth;

class _21010DAO
{	
	/**
	 * Similar ao CREATE (CRIAR) do CRUD
	 * @param _21010 $obj
	 */
	public static function gravar(_21010 $obj)
	{
		$con = new _Conexao();
		try
		{
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
	public static function listar()
	{
		$con = new _Conexao();

		//
	
		return array();
	}
	
	/**
	 * Similar ao UPDATE (ATUALIZAR) do CRUD
	 * Atualiza dados do objeto na base de dados.
	 * @param _21010 $obj
	 */
	public static function alterar(_21010 $obj)
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
	
	/**
	 * Pesquisar registro
	 * @param string $filtro | alfanumérico
	 * @return array stdClass
	 */
	public static function pesquisa($produto_id, $data, $filtro) {
		$con = new _Conexao();

		return self::exibirOperacoes($con, $produto_id, $data, $filtro);
	}

	public static function exibirOperacoes(_Conexao $con, $produto_id, $data, $filtro = false){
		$first  = 200;
		$filtro = '%' . Helpers::removeAcento($filtro, '%', 'upper', true) . '%';
        
		$sql = 
		"
            SELECT FIRST :FIRST
                Y.CODIGO,
                Y.CCONTABIL,
                Y.CCONTABIL_MASK,
                Y.CCONTABIL_DESCRICAO,
                Y.CCUSTO,
                Y.CCUSTO_MASK,
                Y.CCUSTO_DESCRICAO

            FROM
                (SELECT
                    X.CODIGO,
                    X.CCONTABIL,
                    X.CCONTABIL_MASK,
                    X.CCONTABIL_DESCRICAO,
                    X.CCUSTO,
                    X.CCUSTO_MASK,
                    X.CCUSTO_DESCRICAO,
    
                    (X.CODIGO ||' ' ||
                     X.CCONTABIL || ' ' || X.CCONTABIL_MASK || ' ' || X.CCONTABIL_DESCRICAO ||' ' ||
                     X.CCUSTO || ' ' || X.CCUSTO_MASK || ' ' || X.CCUSTO_DESCRICAO) FILTRO
    
                FROM
                    (SELECT
                        A.OPERACAO_CODIGO   CODIGO,
                        A.CONTA_CONTABIL    CCONTABIL,  
                        
                        IIF(char_length(C.CONTA)= 1,C.CONTA,
                        IIF(char_length(C.CONTA)= 2,Substring(C.CONTA From 1 For 1)||'.'||Substring(C.CONTA From 2 For 1),
                        IIF(char_length(C.CONTA)= 4,Substring(C.CONTA From 1 For 1)||'.'||Substring(C.CONTA From 2 For 1)||'.'||Substring(C.CONTA From 3 For 2),
                        IIF(char_length(C.CONTA)= 7,Substring(C.CONTA From 1 For 1)||'.'||Substring(C.CONTA From 2 For 1)||'.'||Substring(C.CONTA From 3 For 2)||'.'||Substring(C.CONTA From 5 For 3),
                        IIF(char_length(C.CONTA)=11,Substring(C.CONTA From 1 For 1)||'.'||Substring(C.CONTA From 2 For 1)||'.'||Substring(C.CONTA From 3 For 2)||'.'||Substring(C.CONTA From 5 For 3)||'.'||Substring(C.CONTA From 8 For 4),'')))))
                        CCONTABIL_MASK,
        
                        UPPER(C.DESCRICAO)  CCONTABIL_DESCRICAO,
        
                        A.CCUSTO,
        
                        IIF(char_length(A.CCUSTO)=2,A.CCUSTO,
                        IIF(char_length(A.CCUSTO)=5,Substring(A.CCUSTO From 1 For 2)||'.'||Substring(A.CCUSTO From 3 For 3),
                        IIF(char_length(A.CCUSTO)=8,Substring(A.CCUSTO From 1 For 2)||'.'||Substring(A.CCUSTO From 3 For 3)||'.'||Substring(A.CCUSTO From 6 For 3),'')))
                        CCUSTO_MASK,
        
                        UPPER(IIF(CHAR_LENGTH(A.CCUSTO)=2,
                        (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 2)),
                        IIF(CHAR_LENGTH(A.CCUSTO)=5,
                        (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 2))||' - '||
                        (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 5)),
                        IIF(CHAR_LENGTH(A.CCUSTO)=8,
                        (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 5))||' - '||
                        (SELECT FIRST 1 C.DESCRICAO FROM TBCENTRO_DE_CUSTO C WHERE C.CODIGO = SUBSTRING(A.CCUSTO FROM 1 FOR 8)),''))))
                        CCUSTO_DESCRICAO
                    
                    FROM 
                        TBPRODUTO_OPERACAO_CONTABIL A,
                        TBCONTACONTABIL C,
                        TBOPERACAO O
                        
                    WHERE TRUE
                    AND C.CONTA           = A.CONTA_CONTABIL
                    AND O.CODIGO          = A.OPERACAO_CODIGO
                    AND O.STATUS          = '1'    
                    AND O.TIPO            = 'E'    
                    AND A.PRODUTO_ID    LIKE :PRODUTO_ID)X)Y

            WHERE
                FILTRO LIKE :FILTRO
		";

		$args = array(
			':FIRST'		=> $first,
			':PRODUTO_ID'	=> $produto_id,
			':FILTRO'		=> $filtro
		);

        return $con->query($sql, $args);
	}
	
	/**
	 * Listar operações para a Baixa de Estoque (requisições de consumo).
	 * 
	 * @param string $filtro
	 * @param string $produto_id
	 */
	public static function listarOperacaoConsumo($filtro, $produto_id) {
		
		$con		= new _Conexao();		
		$palavra	= $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';
		
		$sql = "
			SELECT
                O.CODIGO,
                O.DESCRICAO
            FROM
                TBOPERACAO O
            WHERE
                O.TIPO = 'S'
            AND O.STATUS = '1'
            AND O.ESTOQUE = '1'
            AND O.CONTROLE_ESTOQUE = '1'
            AND O.TRANSFERENCIA = '0'
            AND O.ACERTO = '0'
            AND O.DESPERDICIO = '0'
            AND O.NOTAFISCAL = 0
            AND (CAST(O.CODIGO AS VARCHAR(50)) LIKE :CODIGO OR O.DESCRICAO LIKE :DESCRICAO)
            AND o.codigo in(
                    select * from split(
                        (select iif(coalesce(u.valor_ext,'') <> '',u.valor_ext,(select list(s.codigo,',') from tboperacao s)) from tbcontrole_usuario u where u.usuario_id = :USER and u.id = 128)
                         , ','
                    )
                )
		";
		
		$args = array(
			':CODIGO'		=> $palavra,
			':DESCRICAO'	=> $palavra,
            ':USER'         => Auth::user()->CODIGO
		);
		
		return $con->query($sql, $args);
		
	}
	
}