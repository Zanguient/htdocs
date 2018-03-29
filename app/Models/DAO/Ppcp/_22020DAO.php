<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22020;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use Exception;

class _22020DAO
{	
	/**
     * Consulta um material na tbrevisao.
     * @param array $param
     */
    public static function itemTbRevisao($param){
        $con = new _Conexao();
        
		try
		{
			 $sql = "SELECT
                ESTABELECIMENTO_ID,
                PRODUTO_ID,
                (SELECT FIRST 1 P.DESCRICAO FROM TBPRODUTO P WHERE P.CODIGO = E.PRODUTO_ID) as PRODUTO,
                (SELECT b.densidade from tbmodelo b where b.codigo = (select p.modelo_codigo from tbproduto p where p.codigo = E.PRODUTO_ID)) as densidade,
                (SELECT b.espessura from tbmodelo b where b.codigo = (select p.modelo_codigo from tbproduto p where p.codigo = E.PRODUTO_ID)) as espessura,
                ID,
                DATA_REVISAO,
                EMPRESA_ID,
                NUMERO_NOTAFISCAL,
                SERIE,
                NFE_ID,
                coalesce((select first 1 P.data_entrada FROM tbnfe P where P.controle = E.NFE_ID),'') AS DATA_NF, 
                coalesce((select first 1 P.razaosocial FROM tbempresa P where P.codigo = E.EMPRESA_ID),'') AS FORNECEDOR, 
                coalesce((select first 1 P.grupo||'-'||p.subgrupo FROM tbenderecamento P where P.id = E.ENDERECAMENTO_ID),'') AS ENDERECAMENTO, 
                OPERADOR_ID,
                USUARIO_ID,
                OB,
                DATA,
                PESO_LIQUIDO,
                PESO_BRUTO,
                METRAGEM,
                LARGURA,
                LOTE,
                FALHAS,
                PECA,
                MAQUINA,
                RESULTADO,
                OBSERVACAO,
                NFE_ITEM_ID,
                LOCALIZACAO_ENTRADA,
                LOCALIZACAO_SAIDA,
                ESTOQUE_ID_PESAGEM,
                ESTOQUE_ID_REVISAO,
                ENDERECAMENTO_ID,
                UP_ID,
                coalesce(e.SALDO,0) SALDO,
                STATUS_SALDO,
                STATUS_COLETA,
                TAMANHO,
                PESO_PECA,
                DATAHORA_COLETA,
                ESTOQUE_ID_AJUSTE,
                ESTOQUE_ID_COLETADO,
                OPERADOR_PESAGEM_ID,
                OPERACAO_INVENTARIO,
                ESTOQUE_ID_INVENTARIO,
                OPERADOR_COLETA_ID,
                DATA_INVENTARIO,
                STATUS_OB,
                DATA_STATUS_OB,
                TARA,
                TAMANHO,
                RENDIMENTO,
                TALAO_ID,
                ESTOQUE_ID_SOBRA,
                cast(METRAGEM_SALDO AS numeric(12,4)) AS METRAGEM_SALDO,
                cast(coalesce(e.rendimento_consumo,0) AS numeric(12,4)) AS RENDIMENTO_CONSUMO,
                (select first 1 o.CLASSIFICACAO from TBREVISAO_OB o where o.NFE_ID = e.NFE_ID and o.OB = e.OB) as CLASSIFICACAO
                FROM TBREVISAO E WHERE E.ID IN (".$param->TABELA_ID.")";
		
            $res = $con->query($sql);
	
			$con->commit();
            return $res;
			
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
	public static function listar($param)
	{
		$con = new _Conexao();
        
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('PRODUCAO', $param->RETORNO) ) {
            $res = $res+['PRODUCAO' => _22020DaoSelect::producao($param)];
        }
	
		return (object)$res;
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
	 * Similar ao CREATE (CRIAR) do CRUD
	 * @param _22020 $obj
	 */
	public static function gravar(_22020 $obj)
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
	 * Similar ao UPDATE (ATUALIZAR) do CRUD
	 * Atualiza dados do objeto na base de dados.
	 * @param _22020 $obj
	 */
	public static function alterar(_22020 $obj)
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
	 * Verifica se a Estação está ativa (em produção).
	 * 
	 * @param array $param
	 * @return array
	 */
	public static function verificarEstacaoAtiva($param) {
		
		$con = new _Conexao();
        
        return _22020DaoSelect::verificarEstacaoAtiva($param, $con);
	}
	

	/**
	 * Registra as ações do talão
	 * 
	 * @param array $param
	 * @return array
	 */
	public static function registraAcao($param)
    {
		$con = new _Conexao();
		try {
            
            if ( array_key_exists('REMESSA_TALAO_DETALHE_STATUS', $param) ) {
                _22020DaoUpdate::remessaTalaoDetalhe($param,$con);
            }
            
            if ( array_key_exists('REMESSA_TALAO_STATUS', $param) ) {
                _22020DaoUpdate::remessaTalao($param,$con);
            }
			_22020DaoInsert::programacaoHistorico($param,$con);
			_22020DaoUpdate::programacao         ($param,$con);
			_22020DaoUpdate::estacaoBloqueio     ($param,$con);
            
            if ( $param->PROGRAMACAO_STATUS == '3' ) {
                _22020DaoUpdate::reprogramacaoProduzido($param,$con);
            }
            
			$con->commit();
            
//			$con->rollback();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
    
    public static function consultaOBTalao($param)
	{
        $con = new _Conexao();
        
            $REMESSA_ID                 = $param->REMESSA_ID;
            $REMESSA_TALAO_ID           = $param->REMESSA_TALAO_ID;
            $REMESSA_TALAO_DETALHE_ID   = $param->REMESSA_TALAO_DETALHE_ID;
            $sql = "
                select list(ob,',') as OB from
                (SELECT distinct
                        p.ob
                        FROM
                        VWREMESSA_TALAO_DETALHE D,
                        vwremessa_consumo C,
                        TBREMESSA_TALAO_VINCULO v,
                        tbrevisao p
                WHERE
                    d.id = (
                        SELECT first 1
                            v.tabela_id
                            FROM
                            VWREMESSA_TALAO_DETALHE D,
                            vwremessa_consumo C,
                            TBREMESSA_TALAO_VINCULO v
                        WHERE
                            c.remessa_id = :REMESSA_ID
                            and c.remessa_talao_id = :REMESSA_TALAO_ID
                            and c.remessa_talao_detalhe_id = :REMESSA_TALAO_DETALHE_ID
                            and v.consumo_id = c.id
                            and d.id = c.remessa_talao_detalhe_id
                            and c.remessa_talao_detalhe_id > 0
                        )
                    and c.remessa_id = d.remessa_id
                    and c.remessa_talao_id = d.remessa_talao_id
                    and v.consumo_id = c.id
                    and p.id = v.tabela_id
                    and v.tipo = 'R'
                    and c.remessa_talao_detalhe_id > 0
                    and p.produto_id = d.produto_id
                )";

            $args = [
                ':REMESSA_ID'               => $REMESSA_ID,
                ':REMESSA_TALAO_ID'         => $REMESSA_TALAO_ID,
                ':REMESSA_TALAO_DETALHE_ID' => $REMESSA_TALAO_DETALHE_ID
            ];
            
            $dados  = $con->query($sql,$args);
            
            if(count($dados) > 0){
                $ret = trim($dados[0]->OB);
            }else{
                $ret = '';
            }
            
            return $ret;
	}
	
    public static function alterarQtdSobraMaterial($param)
	{

        $con = new _Conexao();
        
		try
		{
            $consumo_id = $param->CONSUMO_ID;
            $sobra      = $param->SOBRA;

            $sql = "UPDATE TBREMESSA_CONSUMO SET QUANTIDADE_SOBRA = :SOBRA WHERE ID = :CONSUMO_ID";

            $args = [
                ':CONSUMO_ID'       => $consumo_id,
                ':SOBRA'            => $sobra
            ];

            $con->execute($sql,$args);

            $con->commit();
           
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}

	}
    
    public static function remessaOrigemConsumo($param)
	{
        $con = new _Conexao();
        
		try
		{
            $consumo_id = $param->CONSUMO_ID;

            $sql = "select v.tipo,list(v.tabela_id,',') as tabela_id from TBREMESSA_TALAO_VINCULO v where v.consumo_id  = :CONSUMO_ID group by v.tipo";

            $args = [
                ':CONSUMO_ID'       => $consumo_id
            ];

            $dados = $con->query($sql,$args);
            
            $ret = [];
            
            if(count($dados) > 0){
                
                $tipo = trim($dados[0]->TIPO);
                $ID   = trim($dados[0]->TABELA_ID);
                
                $tabela = '';
                
                if($tipo == 'D'){
                    //não colocar firt 1, pois se tiver mais de um retorno com saldo ouve um erro
                    $tabela  = "
                        SELECT
                            T.ID TALAO_ID,
                            '$tipo' as TIPO,
                            D.ID,
                            R.REMESSA,
                            P.DESCRICAO PROD,
                            M.DENSIDADE TAMANHO,
                            M.ESPESSURA,
                            C.DESCRICAO COR,
                            D.REMESSA_ID,
                            D.REMESSA_TALAO_ID
                        FROM
                            VWREMESSA_TALAO_DETALHE D,
                            VWREMESSA_TALAO T,
                            vwremessa r,
                            TBPRODUTO P,
                            TBMODELO M,
                            TBCOR C
                        WHERE
                            P.CODIGO = D.PRODUTO_ID
                        AND M.CODIGO = P.MODELO_CODIGO
                        AND T.REMESSA_ID = D.REMESSA_ID
                        AND T.REMESSA_TALAO_ID = D.REMESSA_TALAO_ID
                        AND C.CODIGO = P.COR_CODIGO
                        and R.remessa_id = d.remessa_id
                        AND D.ID in ($ID)
                    and d.QUANTIDADE_SALDO > 0
                ";}
                                           
                if($tipo == 'R'){
                    //não colocar firt 1, pois se tiver mais de um retorno com saldo ouve um erro
                    $tabela  =  "SELECT
                        '$tipo' as TIPO,
                        d.largura as TAMANHO,
                        D.ID,
                        P.DESCRICAO PROD,
                        M.DENSIDADE TAMANHO,
                        M.ESPESSURA,
                        C.DESCRICAO COR,
                        '' as REMESSA_ID,
                        '' as REMESSA_TALAO_ID
                    FROM
                        tbrevisao D,
                        TBPRODUTO P,
                        TBMODELO M,
                        TBCOR C
                    WHERE
                        P.CODIGO = D.PRODUTO_ID
                    AND M.CODIGO = P.MODELO_CODIGO
                    AND C.CODIGO = P.COR_CODIGO
                    AND D.id in ($ID)
                    and d.saldo > 0";
                    
                }
                
                $ret = $con->query($tabela);
                    
            }

            if(count($dados) > 2){
                log_info("#################### - VERIFICAR - ##################### Erro: sobra duplicada ".$tabela);
            }    
            
            return $ret;

		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}


	}
    
    public static function materiaPrima($param)
	{
		$con = new _Conexao();
        
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('MATERIA_PRIMA', $param->RETORNO) ) {
            $res = $res+['MATERIA_PRIMA' => _22020DaoSelect::materiaPrima($param)];
        }
	
		return (object)$res;
	}
    
    public static function materiaPrimaSobra($param)
	{
		$con = new _Conexao();
        
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('MATERIA_PRIMA', $param->RETORNO) ) {
            $res = $res+['MATERIA_PRIMA' => _22020DaoSelect::materiaPrimaSobra($param)];
        }
	
		return (object)$res;
	}
    
    public static function itensmateriaPrima($param)
	{
		$con = new _Conexao();
        
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('MATERIA_PRIMA', $param->RETORNO) ) {
            $res = $res+['MATERIA_PRIMA' => _22020DaoSelect::listarConsumo($param)];
        }
	
		return (object)$res;
	}
	
	public static function consultarPecaDisponivel($param) {
		
		$con = new _Conexao();
        
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('PECA_DISPONIVEL', $param->RETORNO) ) {
            $res = $res+['PECA_DISPONIVEL' => _22020DaoSelect::consultarPecaDisponivel($param)];
        }
	
		return (object)$res;
		
	}


	public static function pesagem($param)
	{
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('PESAGEM', $param->RETORNO) ) {
            $res = $res+['PESAGEM' => _22020DaoSelect::pesagem($param)];
        }
        
        if ( isset($param->RETORNO) && in_array('OB_CLASSIFICACAO', $param->RETORNO) ) {
            $res = $res+['OB_CLASSIFICACAO' => _22020DaoSelect::pesagemObClassificacao($param)];
        }
	
		return (object)$res;
	}
    
    public static function gravarVinculo($param)
	{
		$con = new _Conexao();
		try
		{
            /**
             * Grava o vinculo da projeção com um item de estoque
             */
            foreach ( $param as $item ) {
                _22020DaoInsert::vinculo($item, $con);
            }
	
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
	
	public static function baixarQuantidadeProduzida($param)
	{
		$con = new _Conexao();
		try
		{
			$res = _22020DaoUpdate::alterarQtdTalaoDetalhe($param, $con);
            _22020DaoInsert::vinculoMateriaPrima($param, $con);
	
			$con->commit();
            return $res;
			
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
    
    public static function projecaoVinculo($param)
	{
        $res = [];
                
        if ( isset($param->RETORNO) && in_array('VINCULO', $param->RETORNO) ) {
            $res = $res+['VINCULO' => _22020DaoSelect::vinculo($param)];
        }
        if ( isset($param->RETORNO) && in_array('TALAO_VINCULO', $param->RETORNO) ) {
            $res = $res+['TALAO_VINCULO' => _22020DaoSelect::remessaTalaoVinculo($param)];
        }
	
		return (object)$res;
	}
	
	/**
     * Altera a quantidade alocada da matéria-prima.
     * @param array $param
     */
	public static function alterarQtdAlocada($param) {
		
		$con = new _Conexao();
		
		try {
			
			_22020DaoUpdate::alterarQtdAlocada($param,$con);
	
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
		
	}
	
	/**
     * Altera a quantidade de produção ou a quantidade alternativa de produção do detalhe do talão.
     * @param array $param
     */
    public static function alterarQtdTalaoDetalhe($param) {
		
		$con = new _Conexao();
		
		try {
			
			$res = _22020DaoUpdate::alterarQtdTalaoDetalhe($param,$con);
	
			$con->commit();
            
            return $res;
            
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
	
	/**
     * Altera todas as quantidades de produção ou as quantidades alternativas de produção do detalhe do talão.
     * @param array $param
     */
    public static function alterarTodasQtdTalaoDetalhe($param) {
		
		$con = new _Conexao();
		
		try {
			
			$ret = _22020DaoUpdate::alterarTodasQtdTalaoDetalhe($param,$con);
	
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
        
        return $ret;
	}
	
	/**
     * Recarregar o status do talão.
     * @param array $param
     */
    public static function recarregarStatus($param) {
		
		$con = new _Conexao();
		
        return _22020DaoSelect::recarregarStatus($param, $con);
		
	}
	
    public static function remessaOrigem($param)
	{
        $res = [];

        if ( isset($param->RETORNO) && in_array('TALAO', $param->RETORNO) ) {
            $res = $res+['TALAO' => _22020DaoSelect::remessaTalaoOrigem($param)];
        }
	
		return (object)$res;
	}
	
    public static function projecaoVinculoExcluir($param)
	{
		$con = new _Conexao();
		
		try {
			
			_22020DaoDelete::projecaoVinculoExcluir($param,$con);
	
			$con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}
	
	/**
	 * Verifica se o item é um aproveitamento.
	 */
	public static function verificarAproveitamento($param) {
		
		$con = new _Conexao();
		
		return _22020DaoSelect::verificarAproveitamento($param, $con);
		
	}
	
	public static function registrarAproveitamento($param) {
		
		$con = new _Conexao();
		
		try {
			
			_22020DaoInsert::registrarAproveitamento($param, $con);
			
			$con->commit();
			
		}
		catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
		
	}
	
	/**
	 * Autenticar UP.
	 * @param array $param
	 * @return array
	 */
	public static function autenticarUp($param) {
		
		$con = new _Conexao();
		
		return _22020DaoSelect::autenticarUp($param, $con);
	}
	
	/**
	 * Totalizadores diários.
	 * @param array $param
	 * @return array
	 */
	public static function totalizadorDiario($param) {
		
		$con = new _Conexao();
		
		return _22020DaoSelect::totalizadorDiario($param, $con);
	}
	
	public static function totalizadorProgramado($param, $conexao) {
		
		return _22020DaoSelect::totalizadorProgramado($param, $conexao);
	}
	
	public static function totalizadorProduzido($param, $conexao) {
		
		return _22020DaoSelect::totalizadorProduzido($param, $conexao);
	}
	
	public static function totalizadorParPorDataRemessa($param, $conexao) {
		
		return _22020DaoSelect::totalizadorParPorDataRemessa($param, $conexao);
	}
	
	public static function totalizadorParPorDataProducao($param, $conexao) {
		
		return _22020DaoSelect::totalizadorParPorDataProducao($param, $conexao);
	}
    
    public static function updateTalaoViaEtiqueta($id) {
        
		$con = new _Conexao();
		
		try {

            $con = $con ? $con : new _Conexao;

            $sql =
            "
                UPDATE
                    VWREMESSA_TALAO T
                SET
                    T.VIA_ETIQUETA = COALESCE(T.VIA_ETIQUETA,0) + 1
                WHERE
                    T.ID = :ID
            ";

            $args = [
                ':ID' => $id,
            ];

            $con->execute($sql, $args);
			$con->commit();
		}
		catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
			
}

class _22020DaoSelect
{
    public static function producao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
//        $first      = isset($param->FIRST     ) ? "FIRST              " . $param->FIRST                                    : '';
//        $skip       = isset($param->SKIP      ) ? "SKIP               " . $param->SKIP                                     : '';
//        $filtro     = isset($param->FILTRO    ) ? "AND FILTRO   LIKE '%". str_replace(' ','%', $param->FILTRO) ."%'"       : '';
//        $remessa    = isset($param->REMESSA   ) ? "AND REMESSA    IN (" . arrayToList($param->REMESSA   , "'#'"    ) . ")" : '';
//        $estacao    = isset($param->ESTACAO   ) ? "AND ESTACAO    IN (" . arrayToList($param->ESTACAO   , 999999999) . ")" : '';
//        $ordem      = isset($param->ORDEM     ) ? "ORDER BY           " . arrayToList($param->ORDEM , 'DATAHORA_REALIZADO_INICIO, DATAHORA_INICIO') : '';
      
        $sql = 
        "

        ";
        
        $args = [
            
        ];
        
        return $con->query($sql,$args);
    }

	public static function verificarEstacaoAtiva($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao;
		
		$up_id		= array_key_exists('UP_ID', $param)			? "AND S.UP_ID = ". $param->UP_ID	: '';
		$estacao_id = array_key_exists('ESTACAO_ID', $param)	? "AND S.ID = ". $param->ESTACAO_ID	: '';
		
		$sql = 
        "
            SELECT FIRST 1
                S.EM_PRODUCAO,
                S.TALAO_ID,
                R.DATAHORA,
                R.OPERADOR_ID,
                O.NOME OPERADOR_NOME
            FROM
                TBUP_ESTACAO S
                LEFT JOIN TBPROGRAMACAO P ON P.TABELA_ID = S.TALAO_ID
                LEFT JOIN TBPROGRAMACAO_REGISTRO R ON R.PROGRAMACAO_ID = P.ID
                LEFT JOIN TBOPERADOR O ON O.CODIGO = R.OPERADOR_ID
            WHERE
                1=1
                /*@UP_ID*/
                /*@ESTACAO_ID*/
            ORDER BY R.DATAHORA DESC
        ";
        
        $args = [
            '@UP_ID'		=> $up_id,
            '@ESTACAO_ID'	=> $estacao_id
        ];
		
		return $con->query($sql,$args);
	}
    
    /**
     * Consulta projeção de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function listarConsumo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
   
        $consumo_id       = array_key_exists('CONSUMO_ID'      , $param) ? "AND CONSUMO_ID       IN (" . arrayToList($param->CONSUMO_ID      , 9999999999999) . ")" : '';
        $remessa_id       = array_key_exists('REMESSA_ID'      , $param) ? "AND REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 9999999999999) . ")" : '';
        $remessa_talao_id = array_key_exists('REMESSA_TALAO_ID', $param) ? "AND REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 9999999999999) . ")" : '';
        $talao_id         = array_key_exists('TALAO_ID'        , $param) ? "AND TALAO_ID         IN (" . arrayToList($param->TALAO_ID        , 9999999999999) . ")" : '';
        $status		      = array_key_exists('STATUS'          , $param) ? "AND V.STATUS         IN (" . arrayToList($param->STATUS          , 9999999999999) . ")" : '';

        $sql =
        " SELECT list(c.tabela_id,',') as COD from tbremessa_talao_vinculo c
            WHERE
                1=1
            /*@CONSUMO_ID*/
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
            /*@TALAO_ID*/
        ";

        $args = [
            '@CONSUMO_ID'       => $consumo_id,
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id,
            '@TALAO_ID'         => $talao_id,
            '@STATUS'	        => $status
        ];
        
        $ret = $con->query($sql,$args);
        
        return $ret;
    }

    /**
     * Consulta projeção de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function materiaPrima($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
   
        $consumo_id       = array_key_exists('CONSUMO_ID'      , $param) ? "AND CONSUMO_ID       IN (" . arrayToList($param->CONSUMO_ID      , 9999999999999) . ")" : '';
        $remessa_id       = array_key_exists('REMESSA_ID'      , $param) ? "AND REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 9999999999999) . ")" : '';
        $remessa_talao_id = array_key_exists('REMESSA_TALAO_ID', $param) ? "AND REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 9999999999999) . ")" : '';
        $talao_id         = array_key_exists('TALAO_ID'        , $param) ? "AND TALAO_ID         IN (" . arrayToList($param->TALAO_ID        , 9999999999999) . ")" : '';
        $status		      = array_key_exists('STATUS'          , $param) ? "AND V.STATUS         IN (" . arrayToList($param->STATUS          , 9999999999999) . ")" : '';

        $sql =
        "
            SELECT
                X.CONSUMO_ID,
                X.REMESSA_ID,
                X.REMESSA_TALAO_ID,
                X.REMESSA_TALAO_DETALHE_ID,
                X.TALAO_ID,
                X.DENSIDADE,
                X.ESPESSURA,
                X.PRODUTO_ID,
                X.PRODUTO_DESCRICAO,
                X.GRADE_ID,
                X.TAMANHO,
                X.TAMANHO_DESCRICAO,
                X.QUANTIDADE,
                X.QUANTIDADE_SALDO,   
                X.UM,
                X.QUANTIDADE_ALTERNATIVA,
                X.QUANTIDADE_ALTERNATIVA_SALDO,
                X.UM_ALTERNATIVA,
                X.QUANTIDADE_ALOCADA,
                X.QUANTIDADE_ALTERNATIVA_ALOCADA,
                X.UM_ALTERNATIVA_ALOCADA,
				X.FAMILIA_ID,
                X.QUANTIDADE_SOBRA,
                X.TABELA_ID,
				X.STATUS_COMPONENTE,
				(CASE X.STATUS_COMPONENTE
					WHEN '0' THEN 'NÃO INICIADO'
                    WHEN '1' THEN 'PARADO'
                    WHEN '2' THEN 'EM ANDAMENTO'
                    WHEN '3' THEN 'FINALIZADO'
                    WHEN '6' THEN 'ENCERRADO'
                    ELSE 'INDEFINIDO' END) STATUS_COMPONENTE_DESCRICAO,
				
				--STATUS DO COMPONENTE REDUZIDO PARA 0 OU 1
				IIF (X.STATUS_COMPONENTE = 3, '1', '0') STATUS_COMPONENTE_REDUZIDO,
                IIF (X.STATUS_COMPONENTE = 3, 'DISPONÍVEL', 'INDISPONÍVEL') STATUS_COMPONENTE_REDUZIDO_DESC,
				
				X.COMPONENTE,
				IIF ( (X.ESTOQUE_FISICO-X.ESTOQUE_ALOCADO+X.ALOCADO_ITEM) >= X.NECESSIDADE, '1','0') STATUS_MATERIA_PRIMA,
				IIF ( (X.ESTOQUE_FISICO-X.ESTOQUE_ALOCADO+X.ALOCADO_ITEM) >= X.NECESSIDADE, 'ESTOQUE DISPONÍVEL','ESTOQUE INDISPONÍVEL') STATUS_MATERIA_PRIMA_DESCRICAO

            FROM
                (SELECT DISTINCT
                    C.ID CONSUMO_ID,
                    T.REMESSA_ID,
                    T.REMESSA_TALAO_ID,
                    C.REMESSA_TALAO_DETALHE_ID,
                    T.ID TALAO_ID,
                    C.DENSIDADE,
                    C.ESPESSURA,
                    LPAD(C.PRODUTO_ID,5,'0') PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
                    P.GRADE_CODIGO GRADE_ID,
                    C.TAMANHO,
                    (SELECT FIRST 1 * FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                    C.QUANTIDADE,
                    C.QUANTIDADE_SALDO,   
                    P.UNIDADEMEDIDA_SIGLA UM,
                    C.QUANTIDADE_ALTERNATIVA,
                    C.QUANTIDADE_ALTERNATIVA_SALDO,
                    coalesce(C.QUANTIDADE_SOBRA,0) QUANTIDADE_SOBRA,
                    (SELECT FIRST 1 UNIDADEMEDIDA_ALTERNATIVO FROM TBFAMILIA WHERE CODIGO = P.FAMILIA_CODIGO) UM_ALTERNATIVA,
                    (SELECT SUM(QUANTIDADE) FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/)QUANTIDADE_ALOCADA,
                    (SELECT SUM(QUANTIDADE_ALTERNATIVA) FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/)QUANTIDADE_ALTERNATIVA_ALOCADA,
                    (SELECT FIRST 1 UNIDADEMEDIDA_ALTERNATIVO FROM TBFAMILIA WHERE CODIGO = (SELECT FIRST 1 FAMILIA_CODIGO FROM TBPRODUTO WHERE CODIGO = (SELECT FIRST 1 PRODUTO_ID FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/))) UM_ALTERNATIVA_ALOCADA,
					P.FAMILIA_CODIGO FAMILIA_ID,
                    (SELECT FIRST 1 v.tabela_id FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/) TABELA_ID,
					
					(SELECT FIRST 1 P.STATUS
						FROM TBREMESSA_CONSUMO_VINCULO V,
							 VWREMESSA_TALAO T,
							 TBPROGRAMACAO P
						WHERE
							V.CONSUMO_ID = C.ID
						AND P.TABELA_ID = T.ID
						AND P.TIPO = 'A'
						AND T.REMESSA_ID = V.REMESSA_ID
						AND T.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID
					) STATUS_COMPONENTE,
					
					C.COMPONENTE,
					
					--ESTOQUE FISICO
                    CAST(COALESCE((SELECT FIRST 1 IIF(T.TAMANHO=0,S.SALDO,
                               IIF(T.TAMANHO=1 ,S.T01_SALDO,IIF(T.TAMANHO=2 ,S.T02_SALDO,
                               IIF(T.TAMANHO=3 ,S.T03_SALDO,IIF(T.TAMANHO=4 ,S.T04_SALDO,
                               IIF(T.TAMANHO=5 ,S.T05_SALDO,IIF(T.TAMANHO=6 ,S.T06_SALDO,
                               IIF(T.TAMANHO=7 ,S.T07_SALDO,IIF(T.TAMANHO=8 ,S.T08_SALDO,
                               IIF(T.TAMANHO=9 ,S.T09_SALDO,IIF(T.TAMANHO=10,S.T10_SALDO,
                               IIF(T.TAMANHO=11,S.T11_SALDO,IIF(T.TAMANHO=12,S.T12_SALDO,
                               IIF(T.TAMANHO=13,S.T13_SALDO,IIF(T.TAMANHO=14,S.T14_SALDO,
                               IIF(T.TAMANHO=15,S.T15_SALDO,IIF(T.TAMANHO=16,S.T16_SALDO,
                               IIF(T.TAMANHO=17,S.T17_SALDO,IIF(T.TAMANHO=18,S.T18_SALDO,
                               IIF(T.TAMANHO=19,S.T19_SALDO,IIF(T.TAMANHO=20,S.T20_SALDO,
                               0.00000))))))))))))))))))))) FROM TBESTOQUE_SALDO S
                    WHERE S.ESTABELECIMENTO_CODIGO = C.ESTABELECIMENTO_ID
                      AND S.LOCALIZACAO_CODIGO = F.LOCALIZACAO_CODIGO
                      AND S.PRODUTO_CODIGO = C.PRODUTO_ID),0) AS NUMERIC(15,4)) ESTOQUE_FISICO,

                    --ESTOQUE ALOCADO
                    CAST(COALESCE((SELECT FIRST 1 SA.QUANTIDADE FROM TBESTOQUE_SALDO_ALOCACAO SA
                      WHERE SA.ESTABELECIMENTO_ID = C.ESTABELECIMENTO_ID
                        AND SA.LOCALIZACAO_ID = F.LOCALIZACAO_CODIGO
                        AND SA.PRODUTO_ID = C.PRODUTO_ID
                        AND SA.TAMANHO = C.TAMANHO),0) AS NUMERIC(15,4)) ESTOQUE_ALOCADO,

                    --ESTOQUE ALOCADO PARA O ITEM
                    COALESCE((SELECT SUM(E.QUANTIDADE)
                        FROM TBESTOQUE_DETALHE_ALOCACAO E,
                            VWREMESSA_CONSUMO RC
                        WHERE E.ESTABELECIMENTO_ID = C.ESTABELECIMENTO_ID
                          AND E.TABELA_ID = C.REMESSA_ID
                          AND E.TAB_ITEM_ID = RC.ID
                          AND RC.REMESSA_ID = T.REMESSA_ID
                          AND RC.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                          AND E.PRODUTO_ID = C.PRODUTO_ID
                          AND E.TAMANHO = C.TAMANHO
                          AND E.TIPO = 'R'),0.0000) ALOCADO_ITEM,
    
                    --SOMA DOS PRODUTOS DO TALAO
                    COALESCE((SELECT SUM(D.QUANTIDADE)
                       FROM VWREMESSA_TALAO_DETALHE D
                      WHERE D.PRODUTO_ID = C.PRODUTO_ID
                        AND D.TAMANHO = C.TAMANHO
                        AND D.REMESSA_ID = C.REMESSA_ID
                        AND D.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID), 0.0000) NECESSIDADE

                FROM
                    VWREMESSA_CONSUMO C,
                    VWREMESSA_TALAO T,
                    TBPRODUTO P,
					VWREMESSA R,
					TBFAMILIA F

                WHERE
                    C.REMESSA_ID       = T.REMESSA_ID
                AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                AND P.CODIGO           = C.PRODUTO_ID
				AND R.REMESSA_ID       = C.REMESSA_ID
				AND F.CODIGO           = P.FAMILIA_CODIGO
                )X

            WHERE
                1=1
            /*@CONSUMO_ID*/
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
            /*@TALAO_ID*/

            ORDER BY PRODUTO_ID
        ";

        $args = [
            '@CONSUMO_ID'       => $consumo_id,
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id,
            '@TALAO_ID'         => $talao_id,
            '@STATUS'	        => $status
        ];
        
        $ret = $con->query($sql,$args);
        
        return $ret;
    }
    
    /**
     * Consulta projeção de consumo
     * @param (object)array $param
     * @param _Conexao $con
     * @return (object)array
     */
    public static function materiaPrimaSobra($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
   
        $consumo_id       = array_key_exists('CONSUMO_ID'      , $param) ? "AND CONSUMO_ID       IN (" . arrayToList($param->CONSUMO_ID      , 9999999999999) . ")" : '';
        $remessa_id       = array_key_exists('REMESSA_ID'      , $param) ? "AND REMESSA_ID       IN (" . arrayToList($param->REMESSA_ID      , 9999999999999) . ")" : '';
        $remessa_talao_id = array_key_exists('REMESSA_TALAO_ID', $param) ? "AND REMESSA_TALAO_ID IN (" . arrayToList($param->REMESSA_TALAO_ID, 9999999999999) . ")" : '';
        $talao_id         = array_key_exists('TALAO_ID'        , $param) ? "AND TALAO_ID         IN (" . arrayToList($param->TALAO_ID        , 9999999999999) . ")" : '';
        $status		      = array_key_exists('STATUS'          , $param) ? "AND V.STATUS         IN (" . arrayToList($param->STATUS          , 9999999999999) . ")" : '';

        $sql =
        "
            SELECT
                X.CONSUMO_ID,
                X.REMESSA_ID,
                X.REMESSA_TALAO_ID,
                X.REMESSA_TALAO_DETALHE_ID,
                X.TALAO_ID,
                X.DENSIDADE,
                X.ESPESSURA,
                X.PRODUTO_ID,
                X.PRODUTO_DESCRICAO,
                X.GRADE_ID,
                X.TAMANHO,
                X.TAMANHO_DESCRICAO,
                X.QUANTIDADE,
                X.QUANTIDADE_SALDO,   
                X.UM,
                X.QUANTIDADE_ALTERNATIVA,
                X.QUANTIDADE_ALTERNATIVA_SALDO,
                X.UM_ALTERNATIVA,
                X.QUANTIDADE_ALOCADA,
                X.QUANTIDADE_ALTERNATIVA_ALOCADA,
                X.UM_ALTERNATIVA_ALOCADA,
				X.FAMILIA_ID,
                X.QUANTIDADE_SOBRA,
                X.TABELA_ID,
				X.STATUS_COMPONENTE,
				(CASE X.STATUS_COMPONENTE
					WHEN '0' THEN 'NÃO INICIADO'
                    WHEN '1' THEN 'PARADO'
                    WHEN '2' THEN 'EM ANDAMENTO'
                    WHEN '3' THEN 'FINALIZADO'
                    WHEN '6' THEN 'ENCERRADO'
                    ELSE 'INDEFINIDO' END) STATUS_COMPONENTE_DESCRICAO,
				X.COMPONENTE,
				IIF ( (X.ESTOQUE_FISICO-X.ESTOQUE_ALOCADO+X.ALOCADO_ITEM) >= X.NECESSIDADE, '1','0') STATUS_MATERIA_PRIMA,
				IIF ( (X.ESTOQUE_FISICO-X.ESTOQUE_ALOCADO+X.ALOCADO_ITEM) >= X.NECESSIDADE, 'ESTOQUE DISPONÍVEL','ESTOQUE INDISPONÍVEL') STATUS_MATERIA_PRIMA_DESCRICAO

            FROM
                (SELECT DISTINCT
                    C.ID CONSUMO_ID,
                    T.REMESSA_ID,
                    T.REMESSA_TALAO_ID,
                    C.REMESSA_TALAO_DETALHE_ID,
                    T.ID TALAO_ID,
                    C.DENSIDADE,
                    C.ESPESSURA,
                    LPAD(C.PRODUTO_ID,5,'0') PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
                    P.GRADE_CODIGO GRADE_ID,
                    C.TAMANHO,
                    (SELECT FIRST 1 * FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO))TAMANHO_DESCRICAO,
                    C.QUANTIDADE,
                    C.QUANTIDADE_SALDO,   
                    P.UNIDADEMEDIDA_SIGLA UM,
                    C.QUANTIDADE_ALTERNATIVA,
                    C.QUANTIDADE_ALTERNATIVA_SALDO,
                    coalesce(C.QUANTIDADE_SOBRA,0) QUANTIDADE_SOBRA,
                    (SELECT FIRST 1 UNIDADEMEDIDA_ALTERNATIVO FROM TBFAMILIA WHERE CODIGO = P.FAMILIA_CODIGO) UM_ALTERNATIVA,
                    (SELECT SUM(QUANTIDADE) FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/)QUANTIDADE_ALOCADA,
                    (SELECT SUM(QUANTIDADE_ALTERNATIVA) FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/)QUANTIDADE_ALTERNATIVA_ALOCADA,
                    (SELECT FIRST 1 UNIDADEMEDIDA_ALTERNATIVO FROM TBFAMILIA WHERE CODIGO = (SELECT FIRST 1 FAMILIA_CODIGO FROM TBPRODUTO WHERE CODIGO = (SELECT FIRST 1 PRODUTO_ID FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/))) UM_ALTERNATIVA_ALOCADA,
					P.FAMILIA_CODIGO FAMILIA_ID,
                    (SELECT FIRST 1 v.tabela_id FROM TBREMESSA_TALAO_VINCULO V WHERE V.CONSUMO_ID = C.ID /*@STATUS*/) TABELA_ID,
					
					(SELECT FIRST 1 P.STATUS
						FROM TBREMESSA_CONSUMO_VINCULO V,
							 VWREMESSA_TALAO T,
							 TBPROGRAMACAO P
						WHERE
							V.CONSUMO_ID = C.ID
						AND P.TABELA_ID = T.ID
						AND P.TIPO = 'A'
						AND T.REMESSA_ID = V.REMESSA_ID
						AND T.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID
					) STATUS_COMPONENTE,
					
					C.COMPONENTE,
					
					--ESTOQUE FISICO
                    CAST(COALESCE((SELECT FIRST 1 IIF(T.TAMANHO=0,S.SALDO,
                               IIF(T.TAMANHO=1 ,S.T01_SALDO,IIF(T.TAMANHO=2 ,S.T02_SALDO,
                               IIF(T.TAMANHO=3 ,S.T03_SALDO,IIF(T.TAMANHO=4 ,S.T04_SALDO,
                               IIF(T.TAMANHO=5 ,S.T05_SALDO,IIF(T.TAMANHO=6 ,S.T06_SALDO,
                               IIF(T.TAMANHO=7 ,S.T07_SALDO,IIF(T.TAMANHO=8 ,S.T08_SALDO,
                               IIF(T.TAMANHO=9 ,S.T09_SALDO,IIF(T.TAMANHO=10,S.T10_SALDO,
                               IIF(T.TAMANHO=11,S.T11_SALDO,IIF(T.TAMANHO=12,S.T12_SALDO,
                               IIF(T.TAMANHO=13,S.T13_SALDO,IIF(T.TAMANHO=14,S.T14_SALDO,
                               IIF(T.TAMANHO=15,S.T15_SALDO,IIF(T.TAMANHO=16,S.T16_SALDO,
                               IIF(T.TAMANHO=17,S.T17_SALDO,IIF(T.TAMANHO=18,S.T18_SALDO,
                               IIF(T.TAMANHO=19,S.T19_SALDO,IIF(T.TAMANHO=20,S.T20_SALDO,
                               0.00000))))))))))))))))))))) FROM TBESTOQUE_SALDO S
                    WHERE S.ESTABELECIMENTO_CODIGO = C.ESTABELECIMENTO_ID
                      AND S.LOCALIZACAO_CODIGO = F.LOCALIZACAO_CODIGO
                      AND S.PRODUTO_CODIGO = C.PRODUTO_ID),0) AS NUMERIC(15,4)) ESTOQUE_FISICO,

                    --ESTOQUE ALOCADO
                    CAST(COALESCE((SELECT FIRST 1 SA.QUANTIDADE FROM TBESTOQUE_SALDO_ALOCACAO SA
                      WHERE SA.ESTABELECIMENTO_ID = C.ESTABELECIMENTO_ID
                        AND SA.LOCALIZACAO_ID = F.LOCALIZACAO_CODIGO
                        AND SA.PRODUTO_ID = C.PRODUTO_ID
                        AND SA.TAMANHO = C.TAMANHO),0) AS NUMERIC(15,4)) ESTOQUE_ALOCADO,

                    --ESTOQUE ALOCADO PARA O ITEM
                    COALESCE((SELECT SUM(E.QUANTIDADE)
                        FROM TBESTOQUE_DETALHE_ALOCACAO E,
                            VWREMESSA_CONSUMO RC
                        WHERE E.ESTABELECIMENTO_ID = C.ESTABELECIMENTO_ID
                          AND E.TABELA_ID = C.REMESSA_ID
                          AND E.TAB_ITEM_ID = RC.ID
                          AND RC.REMESSA_ID = T.REMESSA_ID
                          AND RC.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                          AND E.PRODUTO_ID = C.PRODUTO_ID
                          AND E.TAMANHO = C.TAMANHO
                          AND E.TIPO = 'R'),0.0000) ALOCADO_ITEM,
    
                    --SOMA DOS PRODUTOS DO TALAO
                    COALESCE((SELECT SUM(D.QUANTIDADE)
                       FROM VWREMESSA_TALAO_DETALHE D
                      WHERE D.PRODUTO_ID = C.PRODUTO_ID
                        AND D.TAMANHO = C.TAMANHO
                        AND D.REMESSA_ID = C.REMESSA_ID
                        AND D.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID), 0.0000) NECESSIDADE

                FROM
                    VWREMESSA_CONSUMO C,
                    VWREMESSA_TALAO T,
                    TBPRODUTO P,
					VWREMESSA R,
					TBFAMILIA F

                WHERE
                    C.REMESSA_ID       = T.REMESSA_ID
                AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                AND P.CODIGO           = C.PRODUTO_ID
				AND R.REMESSA_ID       = C.REMESSA_ID
				AND F.CODIGO           = P.FAMILIA_CODIGO
                --AND C.componente       = 0
                )X

            WHERE
                1=1
            AND QUANTIDADE_SOBRA > 0
            /*@CONSUMO_ID*/
            /*@REMESSA_ID*/
            /*@REMESSA_TALAO_ID*/
            /*@TALAO_ID*/

            ORDER BY PRODUTO_ID
        ";

        $args = [
            '@CONSUMO_ID'       => $consumo_id,
            '@REMESSA_ID'       => $remessa_id,
            '@REMESSA_TALAO_ID' => $remessa_talao_id,
            '@TALAO_ID'         => $talao_id,
            '@STATUS'	        => $status
        ];
        
        $ret = $con->query($sql,$args);
        
        return $ret;
    }
	
	public static function consultarPecaDisponivel($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao;
		
		$produto_id_revisao = array_key_exists('PRODUTO_ID', $param) ? "R.PRODUTO_ID = $param->PRODUTO_ID"		: '';
		$produto_id_detalhe = array_key_exists('PRODUTO_ID', $param) ? "AND D.PRODUTO_ID = $param->PRODUTO_ID"	: '';
		$saldo				= array_key_exists('QUANTIDADE', $param) ? "AND X.SALDO >= $param->QUANTIDADE"		: '';
		
		$sql = "
			SELECT FIRST 5
				Z.REFERENCIA_TIPO,
				LPAD(Z.REFERENCIA_ID, 7, '0') REFERENCIA_ID,
				LPAD(Z.PRODUTO_ID, 6, '0') PRODUTO_ID,
				(SELECT REM.REMESSA FROM VWREMESSA REM WHERE REM.REMESSA_ID = Z.REMESSA_ID) REMESSA,
				LPAD(Z.REMESSA_ID, 6, '0') REMESSA_ID,
				LPAD(Z.REMESSA_TALAO_ID, 6, '0') REMESSA_TALAO_ID,
				LPAD(Z.REMESSA_TALAO_DETALHE_ID, 7, '0') REMESSA_TALAO_DETALHE_ID,
				Z.SALDO

				FROM
					(SELECT
						X.REFERENCIA_TIPO,
						X.REFERENCIA_ID,
						X.PRODUTO_ID,
						X.REMESSA_ID,
						X.REMESSA_TALAO_ID,
						X.REMESSA_TALAO_DETALHE_ID,
						X.SALDO

						FROM
							(SELECT
								'R' REFERENCIA_TIPO,
								R.ID REFERENCIA_ID,
								R.PRODUTO_ID,
								T1.REMESSA_ID,
								T1.REMESSA_TALAO_ID,
								D1.ID REMESSA_TALAO_DETALHE_ID,
								(R.SALDO - COALESCE(
									(SELECT SUM(V.QUANTIDADE)
									   FROM TBREMESSA_TALAO_VINCULO V
									  WHERE V.TIPO = 'R'
										AND V.TABELA_ID = R.ID
										AND V.STATUS = '0'),0)
								) SALDO

								FROM
									TBREVISAO R
									LEFT JOIN VWREMESSA_TALAO T1 ON T1.REMESSA_TALAO_ID = R.TALAO_ID
									LEFT JOIN VWREMESSA_TALAO_DETALHE D1
										ON  D1.REMESSA_ID       = T1.REMESSA_ID
										AND D1.REMESSA_TALAO_ID = T1.REMESSA_TALAO_ID

								WHERE
									/*@PRODUTO_ID_REVISAO*/

							UNION

							SELECT
								'D' REFERENCIA_TIPO,
								D.ID REFERENCIA_ID,
								D.PRODUTO_ID,
								T2.REMESSA_ID,
								T2.REMESSA_TALAO_ID,
								D.ID REMESSA_TALAO_DETALHE_ID,
								(D.QUANTIDADE_SALDO - COALESCE(
									(SELECT SUM(V.QUANTIDADE)
									   FROM TBREMESSA_TALAO_VINCULO V
									  WHERE V.TIPO = 'R'
										AND V.TABELA_ID = D.ID
										AND V.STATUS = '0'),0)
								) SALDO

								FROM
									VWREMESSA_TALAO_DETALHE D
									LEFT JOIN TBREMESSA_CONSUMO_VINCULO CV ON CV.REMESSA_TALAO_DETALHE_ID = D.ID
									LEFT JOIN VWREMESSA_TALAO T2
										ON  T2.REMESSA_ID       = D.REMESSA_ID
										AND T2.REMESSA_TALAO_ID = D.REMESSA_TALAO_ID

								WHERE
									CV.ID IS NULL
									/*@PRODUTO_ID_DETALHE*/
							)X

						WHERE
							X.SALDO > 0
							/*@SALDO*/
				) Z

			ORDER BY SALDO
		";
		
		$args = [
			'@PRODUTO_ID_REVISAO'	=> $produto_id_revisao,
			'@PRODUTO_ID_DETALHE'	=> $produto_id_detalhe,
			'@SALDO'				=> $saldo
		];
		
		return $con->query($sql, $args);
		
	}


	public static function pesagem($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $id           = array_key_exists('ID', $param) ? "AND ID IN (" . arrayToList($param->ID, 999999999) . ")" : '';
        $status_ativo = array_key_exists('STATUS_ATIVO', $param) ? "AND ((RESULTADO = 'I' OR RESULTADO = 'R') OR (RESULTADO = 'P' AND STATUS_OB = '2')) " : '';
		$saldo        = array_key_exists('SALDO'       , $param) ? "AND SALDO " . $param->SALDO : '';

        $sql = 
        "
            SELECT                  
                ID,
                ESTABELECIMENTO_ID,
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
                OB,
                DATA,
                PESO_PECA,
                PESO_BRUTO,
                PESO_LIQUIDO,
                PESO_TARA,
                SALDO,
                STATUS_SALDO,
                STATUS_COLETA,
                CLASSIFICACAO,
                RENDIMENTO,
                ALOCADO

            FROM
               (SELECT     
                    R.ID,
                    R.ESTABELECIMENTO_ID,
                    R.PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
                    R.OB,
                    R.DATA,      
                    R.PESO_PECA,
                    R.PESO_BRUTO,
                    R.PESO_LIQUIDO,
                    R.TARA PESO_TARA,
                    (R.SALDO - COALESCE((SELECT SUM(V.QUANTIDADE)
                    FROM TBREMESSA_TALAO_VINCULO V
                    WHERE V.STATUS <> '1'
                      AND V.TIPO = 'R'
                      AND V.TABELA_ID = R.ID),0))SALDO,
                    R.STATUS_SALDO,
                    R.STATUS_COLETA,
                    R.RESULTADO,
                    R.STATUS_OB,

                    COALESCE((SELECT list('ALOCADO EM REMESSA:'||s.remessa||' TALAO:'||c.talao||' USADO:'||v.QUANTIDADE,',<br>')
                    FROM TBREMESSA_TALAO_VINCULO V,tbremessa_consumo c,tbremessa s
                    WHERE 1 = 1
                      and V.STATUS <> '1'
                      AND V.TIPO = 'R'
                      and c.id = v.consumo_id
                      and s.numero = c.remessa
                      AND V.tabela_id = R.ID),'') ALOCADO,
                    
                    TRIM(COALESCE((SELECT FIRST 1 O.CLASSIFICACAO
                       FROM TBREVISAO_OB O
                      WHERE O.NFE_ID = R.NFE_ID
                        AND O.OB = R.OB),'')) CLASSIFICACAO,

                    (SELECT
                        IIF(Z.RENDIMENTO_PECA    > 0, Z.RENDIMENTO_PECA,
                        IIF(Z.RENDIMENTO_OB      > 0, Z.RENDIMENTO_OB,
                        IIF(Z.RENDIMENTO_PRODUTO > 0, Z.RENDIMENTO_PRODUTO,0)))RENDIMENTO

                    FROM (
                        SELECT FIRST 1
                            E.RENDIMENTO RENDIMENTO_PECA,

                            (SELECT FIRST 1 B.RENDIMENTO
                               FROM TBREVISAO B
                              WHERE B.OB = E.OB
                                AND B.RENDIMENTO > 0
                              ORDER BY B.DATA DESC)RENDIMENTO_OB,

                            (SELECT AVG(RENDIMENTO)
                                FROM
                                  (SELECT FIRST 30 A.RENDIMENTO, A.DATA, A.PRODUTO_ID
                                      FROM TBREVISAO A
                                     WHERE A.PRODUTO_ID = E.PRODUTO_ID
                                       AND A.RENDIMENTO > 0
                                     ORDER BY A.DATA DESC)X)RENDIMENTO_PRODUTO
                        FROM
                            TBREVISAO E

                        WHERE
                            E.ID = R.ID)Z)RENDIMENTO


                FROM
                    TBREVISAO R,
                    TBPRODUTO P
                WHERE
                    P.CODIGO = R.PRODUTO_ID
                    )X
            WHERE
                1=1
                AND SALDO is NOT NULL
                /*@ID*/
                /*@STATUS_ATIVO*/
                /*@SALDO*/

        ";
        
        $args = [
            '@ID'			=> $id,
            '@STATUS_ATIVO' => $status_ativo,
            '@SALDO'		=> $saldo
        ];
        
        return $con->query($sql,$args);
    }

	public static function pesagemObClassificacao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql = 
        "
            SELECT FIRST 1
                X.*
            FROM
                (SELECT
                    CAST(COALESCE((SELECT FIRST 1 O.CLASSIFICACAO
                       FROM TBREVISAO_OB O
                      WHERE O.NFE_ID = R.NFE_ID
                        AND O.OB = R.OB),'') AS VARCHAR(10)) CLASSIFICACAO,
                      D.PECA_CONJUNTO,
                      R.ID,
                      R.PRODUTO_ID

                FROM
                    VWREMESSA_TALAO T,
                    TBREMESSA_TALAO_VINCULO V,
                    TBREVISAO R,
                    VWREMESSA_CONSUMO C,
                    VWREMESSA_TALAO_DETALHE D

                WHERE
                    T.REMESSA_ID = :REMESSA_ID
                AND T.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
                AND V.TALAO_ID = T.ID
                AND V.PRODUTO_ID = :PRODUTO_ID
                AND V.TIPO = 'R'
                AND R.ID = V.TABELA_ID
                AND C.ID = V.CONSUMO_ID
                AND D.ID = C.REMESSA_TALAO_DETALHE_ID
                AND D.PECA_CONJUNTO = :PECA_CONJUNTO) X

            WHERE
                X.CLASSIFICACAO <> :CLASSIFICACAO
            AND X.CLASSIFICACAO <> ''
        ";
        
        $args = [
            ':REMESSA_ID'	    => $param->REMESSA_ID,
            ':REMESSA_TALAO_ID'	=> $param->REMESSA_TALAO_ID,
            ':PRODUTO_ID'	    => $param->PRODUTO_ID,
            ':PECA_CONJUNTO'    => $param->PECA_CONJUNTO,
            ':CLASSIFICACAO'    => $param->CLASSIFICACAO
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function vinculo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
//        $remessa_id       = array_key_exists('REMESSA_ID'      , $param) ? "AND CONSUMO_ID IN (" . arrayToList($param->CONSUMO_ID, 999999999) . ")" : '';
//        $remessa_talao_id = array_key_exists('REMESSA_TALAO_ID', $param) ? "AND CONSUMO_ID IN (" . arrayToList($param->CONSUMO_ID, 999999999) . ")" : '';
      
        $sql = 
        "
            SELECT
                ID,
                CONSUMO_ID,
                REMESSA_ID,
                REMESSA_TALAO_ID,
                REMESSA_TALAO_DETALHE_ID,
                QUANTIDADE,
                --QUANTIDADE_SALDO
                IIF(CONT = 1, QUANTIDADE_SALDO, (QUANTIDADE + IIF(INDICE = 1 , QUANTIDADE_SALDO - TRUNC(QUANTIDADE_SALDO) ,0)))  QUANTIDADE_SALDO--,
                --CONT,
                --INDICE

            FROM (
                SELECT
                    V.ID,
                    V.CONSUMO_ID,
                    V.REMESSA_ID,
                    V.REMESSA_TALAO_ID,
                    V.REMESSA_TALAO_DETALHE_ID,
                    V.QUANTIDADE,
                    D.QUANTIDADE_SALDO,
                    (ROW_NUMBER() OVER (ORDER BY V.ID DESC) ) AS INDICE ,

                        (SELECT COUNT(*)
                           FROM VWREMESSA_CONSUMO C1, TBREMESSA_CONSUMO_VINCULO V1
                          WHERE C1.ID = V1.CONSUMO_ID
                            AND C1.REMESSA_ID       = C.REMESSA_ID
                            AND C1.REMESSA_TALAO_ID = C.REMESSA_TALAO_ID
                            AND V1.REMESSA_TALAO_DETALHE_ID = V.REMESSA_TALAO_DETALHE_ID) CONT

                FROM
                    TBREMESSA_CONSUMO_VINCULO V,
                    VWREMESSA_CONSUMO C,
                    VWREMESSA_TALAO T,
                    VWREMESSA_TALAO_DETALHE D

                WHERE
                    C.ID = V.CONSUMO_ID  
                AND T.REMESSA_ID       = V.REMESSA_ID
                AND T.REMESSA_TALAO_ID = V.REMESSA_TALAO_ID
                AND D.STATUS           = 3
                AND D.ID               = V.REMESSA_TALAO_DETALHE_ID
                AND C.REMESSA_ID       = :REMESSA_ID_ORIGEM
                AND C.REMESSA_TALAO_ID = :REMESSA_TALAO_ID_ORIGEM
                AND T.ID               = :TALAO_ID_DESTINO
                )X
                ORDER BY ID
        ";
        
        $args = [
            ':REMESSA_ID_ORIGEM'	   => $param->REMESSA_ID_ORIGEM,
            ':REMESSA_TALAO_ID_ORIGEM' => $param->REMESSA_TALAO_ID_ORIGEM,
            ':TALAO_ID_DESTINO'	       => $param->TALAO_ID_DESTINO,
        ];
        
        return $con->query($sql,$args);
    }
    
    
    public static function aproveitamento($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
       
        $sql ="
               SELECT * FROM TBREMESSA_TALAO_VINCULO V
               WHERE V.REMESSA_TALAO_DETALHE_ID = :TALAO_ID
            ";
        
        $args = [
            ':TALAO_ID'	       => $param->TALAO_ID,
        ];
        
        return $con->query($sql,$args);
    }

	public static function recarregarStatus($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao;
		
		$estabelecimento_id	= array_key_exists('ESTABELECIMENTO_ID', $param)	? "AND ESTABELECIMENTO_ID = ". $param->ESTABELECIMENTO_ID	: '';
		$gp_id				= array_key_exists('GP_ID', $param)					? "AND GP_ID IN (". $param->GP_ID. ")"						: '';
		$up_id				= array_key_exists('UP_ID', $param)					? "AND UP_ID IN (". $param->UP_ID. ")"						: '';
		$estacao_id			= array_key_exists('ESTACAO_ID', $param)			? "AND ESTACAO_ID = ". $param->ESTACAO_ID					: '';
		$status				= array_key_exists('STATUS', $param)				? "AND STATUS IN (". $param->STATUS. ")"					: '';
		$talao_id			= array_key_exists('TALAO_ID', $param)				? "AND TALAO_ID = ". $param->TALAO_ID						: '';
		
		$sql = 
        "
			SELECT FIRST 1
				X.STATUS,
				X.STATUS_DESCRICAO,
				X.PROGRAMACAO_STATUS,
				X.PROGRAMACAO_STATUS_DESCRICAO

			FROM
				(SELECT
					R.ESTABELECIMENTO_ID,
					T.ID TALAO_ID,
					T.GP_ID,
					T.UP_ID,
					U.DESCRICAO UP_DESCRICAO,
					T.ESTACAO,

					T.STATUS,
				   (CASE
						T.STATUS
					WHEN 1 THEN 'EM ABERTO'
					WHEN 2 THEN 'PRODUZIDO'
					WHEN 3 THEN 'LIBERADO'
					ELSE 'INDEFINIDO' END) STATUS_DESCRICAO,

					P.STATUS PROGRAMACAO_STATUS,
				   (CASE
						P.STATUS
					WHEN '0' THEN 'NÃO INICIADO'
					WHEN '1' THEN 'PARADO'
					WHEN '2' THEN 'EM ANDAMENTO'
					WHEN '3' THEN 'FINALIZADO'
					WHEN '6' THEN 'ENCERRADO'
					ELSE 'INDEFINIDO' END) PROGRAMACAO_STATUS_DESCRICAO

				FROM
					VWREMESSA_TALAO T,
					TBUP U,
					TBSUB_UP S,
					VWREMESSA R,
					TBPROGRAMACAO P

				WHERE
					U.ID         = T.UP_ID
				AND S.UP_ID      = U.ID
				AND S.ID         = T.ESTACAO
				AND R.REMESSA_ID = T.REMESSA_ID
				AND P.TABELA_ID  = T.ID
				AND P.GP_ID      = T.GP_ID
				AND P.UP_ID      = T.UP_ID
				AND P.ESTACAO    = T.ESTACAO)X

			WHERE
				1=1
				/*@ESTABELECIMENTO_ID*/	
				/*@GP_ID*/
				/*@UP_ID*/
				/*@ESTACAO_ID*/
				/*@STATUS*/
				/*@TALAO_ID*/
        ";
        
        $args = [
            '@ESTABELECIMENTO_ID'	=> $estabelecimento_id,
            '@GP_ID'				=> $gp_id,
            '@UP_ID'				=> $up_id,
            '@ESTACAO_ID'			=> $estacao_id,
            '@STATUS'				=> $status,
            '@TALAO_ID'				=> $talao_id
        ];
		
		return $con->query($sql,$args)[0];
	}
	
	public static function remessaTalaoOrigem($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao;
        
        $status = array_key_exists('STATUS', $param) ? "AND T.STATUS IN (". $param->STATUS. ")" : '';
        
		$sql = 
        "
            SELECT
                ID,
                REMESSA,
                remessa_talao_id,
                remessa_id,
                REMESSA_DATA,     
                REMESSA_REQUISICAO,
                REMESSA_TIPO,
                CONTROLE,
                MODELO_CODIGO,
                MODELO_DENSIDADE,
                MODELO_ESPESSURA,
                MATRIZ_CODIGO,
                MATRIZ_DESCRICAO,
                MATRIZ_LARGURA,
                PRODUTO_CODIGO,
                PRODUTO_DESCRICAO,
                TAMANHO,
                QUANTIDADE,
                QUANTIDADE_SOBRA,
                TOLERANCIA,
                CHAPAS,
                PLACAS,
                SOBRAS,
                PLACAS_,
                SOBRAS_,
                LIB,
                CONSUMO_MEDIO,
                OBSERVACAO,
                SITUACAO,
                DESCRICAO,
                GRADE_CODIGO,
                COR_CODIGO,
                CLASSE,
                PROGRAMACAO_BOCA,
                PROGRAMACAO_ESTEIRA,
                TAMANHO_GRADE,
                PROGRAMACAO_SEQUENCIA,
                ESTEIRA_DESCRICAO,
                HORA_PRODUCAO,
                HORA_LIBERACAO,
                TURNO,
                COR_DESCRICAO,
                SEQ,
                PERFIL,
                PERC_SOBRA,
                FLAG,
                APROVEITAMENTO,
                MEDIAPAR,
                LARGURA,
                ESPESSURA,
                LARGURA_T01,
                LARGURA_T02,
                LARGURA_T03,
                LARGURA_T04,
                LARGURA_T05,
                LARGURA_T06,
                LARGURA_T07,
                LARGURA_T08,
                LARGURA_T09,
                LARGURA_T10,
                LARGURA_T11,
                LARGURA_T12,
                LARGURA_T13,
                LARGURA_T14,
                LARGURA_T15,
                LARGURA_T16,
                LARGURA_T17,
                LARGURA_T18,
                LARGURA_T19,
                LARGURA_T20,
                COMPRIMENTO_T01,
                COMPRIMENTO_T02,
                COMPRIMENTO_T03,
                COMPRIMENTO_T04,
                COMPRIMENTO_T05,
                COMPRIMENTO_T06,
                COMPRIMENTO_T07,
                COMPRIMENTO_T08,
                COMPRIMENTO_T09,
                COMPRIMENTO_T10,
                COMPRIMENTO_T11,
                COMPRIMENTO_T12,
                COMPRIMENTO_T13,
                COMPRIMENTO_T14,
                COMPRIMENTO_T15,
                COMPRIMENTO_T16,
                COMPRIMENTO_T17,
                COMPRIMENTO_T18,
                COMPRIMENTO_T19,
                COMPRIMENTO_T20,
                PLACA_T01,
                PLACA_T02,
                PLACA_T03,
                PLACA_T04,
                PLACA_T05,
                PLACA_T06,
                PLACA_T07,
                PLACA_T08,
                PLACA_T09,
                PLACA_T10,
                PLACA_T11,
                PLACA_T12,
                PLACA_T13,
                PLACA_T14,
                PLACA_T15,
                PLACA_T16,
                PLACA_T17,
                PLACA_T18,
                PLACA_T19,
                PLACA_T20,
                SOBRA_T01,
                SOBRA_T02,
                SOBRA_T03,
                SOBRA_T04,
                SOBRA_T05,
                SOBRA_T06,
                SOBRA_T07,
                SOBRA_T08,
                SOBRA_T09,
                SOBRA_T10,
                SOBRA_T11,
                SOBRA_T12,
                SOBRA_T13,
                SOBRA_T14,
                SOBRA_T15,
                SOBRA_T16,
                SOBRA_T17,
                SOBRA_T18,
                SOBRA_T19,
                SOBRA_T20,
                TALAO_ORIGEM,
                UP_DESTINO,
                CLIENTE,
                VIA_ETIQUETA

            FROM
                (SELECT
                    X.ID,
                    X.REMESSA,
                    X.remessa_talao_id,
                    x.remessa_id,
                    X.REMESSA_DATA,
                    X.REMESSA_REQUISICAO,
                    X.REMESSA_TIPO,
                    X.CONTROLE,
                    X.MODELO_CODIGO,

                    CAST(SUBSTRING(X.DENS_ESPESS FROM 1 FOR 5) AS INTEGER)  MODELO_DENSIDADE,

                    CAST(SUBSTRING(X.DENS_ESPESS FROM 6 FOR 5) AS NUMERIC(15,2))  MODELO_ESPESSURA,
                    
                    X.MATRIZ_CODIGO,
                    X.MATRIZ_DESCRICAO,
                    X.MATRIZ_LARGURA,
                    X.PRODUTO_CODIGO,
                    X.PRODUTO_DESCRICAO,
                    X.TAMANHO,
                    X.QUANTIDADE,
                    X.QUANTIDADE_SOBRA,
                    X.TOLERANCIA,
                    X.CHAPAS,
                    X.PLACAS,
                    X.SOBRAS,
                    X.PLACAS_,
                    X.SOBRAS_,
                    X.LIB,
                    X.CONSUMO_MEDIO,
                    X.OBSERVACAO,
                    X.SITUACAO,
                    X.DESCRICAO,
                    X.GRADE_CODIGO,
                    X.COR_CODIGO,
                    X.CLASSE,
                    X.PROGRAMACAO_BOCA,
                    X.PROGRAMACAO_ESTEIRA,
                    X.TAMANHO_GRADE,
                    X.PROGRAMACAO_SEQUENCIA,
                    X.ESTEIRA_DESCRICAO,
                    X.HORA_PRODUCAO,
                    X.HORA_LIBERACAO,
                    X.TURNO,
                    (SELECT FIRST 1 DESCRICAO FROM TBCOR WHERE CODIGO = X.COR_CODIGO) COR_DESCRICAO,
                    X.SEQ,
                    X.PERFIL,
                    X.PERC_SOBRA,
                    '0' FLAG,
                    X.APROVEITAMENTO,
                    X.VIA_ETIQUETA,
                    CAST(IIF(X.CONSUMO_MEDIO>0,(X.QUANTIDADE-X.QUANTIDADE_SOBRA)/X.CONSUMO_MEDIO,0) AS NUMERIC(15,4)) MEDIAPAR,

                    CAST(SUBSTRING(X.MEDIDAS FROM   1 FOR 10) AS NUMERIC(15,5)) LARGURA,
                    CAST(SUBSTRING(X.MEDIDAS FROM  11 FOR 10) AS NUMERIC(15,5)) ESPESSURA,
                    CAST(SUBSTRING(X.MEDIDAS FROM  21 FOR 10) AS NUMERIC(15,5)) LARGURA_T01,
                    CAST(SUBSTRING(X.MEDIDAS FROM  31 FOR 10) AS NUMERIC(15,5)) LARGURA_T02,
                    CAST(SUBSTRING(X.MEDIDAS FROM  41 FOR 10) AS NUMERIC(15,5)) LARGURA_T03,
                    CAST(SUBSTRING(X.MEDIDAS FROM  51 FOR 10) AS NUMERIC(15,5)) LARGURA_T04,
                    CAST(SUBSTRING(X.MEDIDAS FROM  61 FOR 10) AS NUMERIC(15,5)) LARGURA_T05,
                    CAST(SUBSTRING(X.MEDIDAS FROM  71 FOR 10) AS NUMERIC(15,5)) LARGURA_T06,
                    CAST(SUBSTRING(X.MEDIDAS FROM  81 FOR 10) AS NUMERIC(15,5)) LARGURA_T07,
                    CAST(SUBSTRING(X.MEDIDAS FROM  91 FOR 10) AS NUMERIC(15,5)) LARGURA_T08,
                    CAST(SUBSTRING(X.MEDIDAS FROM 101 FOR 10) AS NUMERIC(15,5)) LARGURA_T09,
                    CAST(SUBSTRING(X.MEDIDAS FROM 111 FOR 10) AS NUMERIC(15,5)) LARGURA_T10,
                    CAST(SUBSTRING(X.MEDIDAS FROM 121 FOR 10) AS NUMERIC(15,5)) LARGURA_T11,
                    CAST(SUBSTRING(X.MEDIDAS FROM 131 FOR 10) AS NUMERIC(15,5)) LARGURA_T12,
                    CAST(SUBSTRING(X.MEDIDAS FROM 141 FOR 10) AS NUMERIC(15,5)) LARGURA_T13,
                    CAST(SUBSTRING(X.MEDIDAS FROM 151 FOR 10) AS NUMERIC(15,5)) LARGURA_T14,
                    CAST(SUBSTRING(X.MEDIDAS FROM 161 FOR 10) AS NUMERIC(15,5)) LARGURA_T15,
                    CAST(SUBSTRING(X.MEDIDAS FROM 171 FOR 10) AS NUMERIC(15,5)) LARGURA_T16,
                    CAST(SUBSTRING(X.MEDIDAS FROM 181 FOR 10) AS NUMERIC(15,5)) LARGURA_T17,
                    CAST(SUBSTRING(X.MEDIDAS FROM 191 FOR 10) AS NUMERIC(15,5)) LARGURA_T18,
                    CAST(SUBSTRING(X.MEDIDAS FROM 201 FOR 10) AS NUMERIC(15,5)) LARGURA_T19,
                    CAST(SUBSTRING(X.MEDIDAS FROM 211 FOR 10) AS NUMERIC(15,5)) LARGURA_T20,
                    CAST(SUBSTRING(X.MEDIDAS FROM 221 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T01,
                    CAST(SUBSTRING(X.MEDIDAS FROM 231 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T02,
                    CAST(SUBSTRING(X.MEDIDAS FROM 241 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T03,
                    CAST(SUBSTRING(X.MEDIDAS FROM 251 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T04,
                    CAST(SUBSTRING(X.MEDIDAS FROM 261 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T05,
                    CAST(SUBSTRING(X.MEDIDAS FROM 271 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T06,
                    CAST(SUBSTRING(X.MEDIDAS FROM 281 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T07,
                    CAST(SUBSTRING(X.MEDIDAS FROM 291 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T08,
                    CAST(SUBSTRING(X.MEDIDAS FROM 301 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T09,
                    CAST(SUBSTRING(X.MEDIDAS FROM 311 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T10,
                    CAST(SUBSTRING(X.MEDIDAS FROM 321 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T11,
                    CAST(SUBSTRING(X.MEDIDAS FROM 331 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T12,
                    CAST(SUBSTRING(X.MEDIDAS FROM 341 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T13,
                    CAST(SUBSTRING(X.MEDIDAS FROM 351 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T14,
                    CAST(SUBSTRING(X.MEDIDAS FROM 361 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T15,
                    CAST(SUBSTRING(X.MEDIDAS FROM 371 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T16,
                    CAST(SUBSTRING(X.MEDIDAS FROM 381 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T17,
                    CAST(SUBSTRING(X.MEDIDAS FROM 391 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T18,
                    CAST(SUBSTRING(X.MEDIDAS FROM 401 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T19,
                    CAST(SUBSTRING(X.MEDIDAS FROM 411 FOR 10) AS NUMERIC(15,5)) COMPRIMENTO_T20,
                    CAST(SUBSTRING(X.MEDIDAS FROM 421 FOR 10) AS NUMERIC(15,5)) PLACA_T01,
                    CAST(SUBSTRING(X.MEDIDAS FROM 431 FOR 10) AS NUMERIC(15,5)) PLACA_T02,
                    CAST(SUBSTRING(X.MEDIDAS FROM 441 FOR 10) AS NUMERIC(15,5)) PLACA_T03,
                    CAST(SUBSTRING(X.MEDIDAS FROM 451 FOR 10) AS NUMERIC(15,5)) PLACA_T04,
                    CAST(SUBSTRING(X.MEDIDAS FROM 461 FOR 10) AS NUMERIC(15,5)) PLACA_T05,
                    CAST(SUBSTRING(X.MEDIDAS FROM 471 FOR 10) AS NUMERIC(15,5)) PLACA_T06,
                    CAST(SUBSTRING(X.MEDIDAS FROM 481 FOR 10) AS NUMERIC(15,5)) PLACA_T07,
                    CAST(SUBSTRING(X.MEDIDAS FROM 491 FOR 10) AS NUMERIC(15,5)) PLACA_T08,
                    CAST(SUBSTRING(X.MEDIDAS FROM 501 FOR 10) AS NUMERIC(15,5)) PLACA_T09,
                    CAST(SUBSTRING(X.MEDIDAS FROM 511 FOR 10) AS NUMERIC(15,5)) PLACA_T10,
                    CAST(SUBSTRING(X.MEDIDAS FROM 521 FOR 10) AS NUMERIC(15,5)) PLACA_T11,
                    CAST(SUBSTRING(X.MEDIDAS FROM 531 FOR 10) AS NUMERIC(15,5)) PLACA_T12,
                    CAST(SUBSTRING(X.MEDIDAS FROM 541 FOR 10) AS NUMERIC(15,5)) PLACA_T13,
                    CAST(SUBSTRING(X.MEDIDAS FROM 551 FOR 10) AS NUMERIC(15,5)) PLACA_T14,
                    CAST(SUBSTRING(X.MEDIDAS FROM 561 FOR 10) AS NUMERIC(15,5)) PLACA_T15,
                    CAST(SUBSTRING(X.MEDIDAS FROM 571 FOR 10) AS NUMERIC(15,5)) PLACA_T16,
                    CAST(SUBSTRING(X.MEDIDAS FROM 581 FOR 10) AS NUMERIC(15,5)) PLACA_T17,
                    CAST(SUBSTRING(X.MEDIDAS FROM 591 FOR 10) AS NUMERIC(15,5)) PLACA_T18,
                    CAST(SUBSTRING(X.MEDIDAS FROM 601 FOR 10) AS NUMERIC(15,5)) PLACA_T19,
                    CAST(SUBSTRING(X.MEDIDAS FROM 611 FOR 10) AS NUMERIC(15,5)) PLACA_T20,
                    CAST(SUBSTRING(X.MEDIDAS FROM 621 FOR 10) AS NUMERIC(15,5)) SOBRA_T01,
                    CAST(SUBSTRING(X.MEDIDAS FROM 631 FOR 10) AS NUMERIC(15,5)) SOBRA_T02,
                    CAST(SUBSTRING(X.MEDIDAS FROM 641 FOR 10) AS NUMERIC(15,5)) SOBRA_T03,
                    CAST(SUBSTRING(X.MEDIDAS FROM 651 FOR 10) AS NUMERIC(15,5)) SOBRA_T04,
                    CAST(SUBSTRING(X.MEDIDAS FROM 661 FOR 10) AS NUMERIC(15,5)) SOBRA_T05,
                    CAST(SUBSTRING(X.MEDIDAS FROM 671 FOR 10) AS NUMERIC(15,5)) SOBRA_T06,
                    CAST(SUBSTRING(X.MEDIDAS FROM 681 FOR 10) AS NUMERIC(15,5)) SOBRA_T07,
                    CAST(SUBSTRING(X.MEDIDAS FROM 691 FOR 10) AS NUMERIC(15,5)) SOBRA_T08,
                    CAST(SUBSTRING(X.MEDIDAS FROM 701 FOR 10) AS NUMERIC(15,5)) SOBRA_T09,
                    CAST(SUBSTRING(X.MEDIDAS FROM 711 FOR 10) AS NUMERIC(15,5)) SOBRA_T10,
                    CAST(SUBSTRING(X.MEDIDAS FROM 721 FOR 10) AS NUMERIC(15,5)) SOBRA_T11,
                    CAST(SUBSTRING(X.MEDIDAS FROM 731 FOR 10) AS NUMERIC(15,5)) SOBRA_T12,
                    CAST(SUBSTRING(X.MEDIDAS FROM 741 FOR 10) AS NUMERIC(15,5)) SOBRA_T13,
                    CAST(SUBSTRING(X.MEDIDAS FROM 751 FOR 10) AS NUMERIC(15,5)) SOBRA_T14,
                    CAST(SUBSTRING(X.MEDIDAS FROM 761 FOR 10) AS NUMERIC(15,5)) SOBRA_T15,
                    CAST(SUBSTRING(X.MEDIDAS FROM 771 FOR 10) AS NUMERIC(15,5)) SOBRA_T16,
                    CAST(SUBSTRING(X.MEDIDAS FROM 781 FOR 10) AS NUMERIC(15,5)) SOBRA_T17,
                    CAST(SUBSTRING(X.MEDIDAS FROM 791 FOR 10) AS NUMERIC(15,5)) SOBRA_T18,
                    CAST(SUBSTRING(X.MEDIDAS FROM 801 FOR 10) AS NUMERIC(15,5)) SOBRA_T19,
                    CAST(SUBSTRING(X.MEDIDAS FROM 811 FOR 10) AS NUMERIC(15,5)) SOBRA_T20,
                    TALAO_ORIGEM,
                    UP_DESTINO,
                    CLIENTE
                
                FROM
                   (SELECT
                        A.ID,
                        A.REMESSA,
                        T.remessa_talao_id,
                        T.remessa_id,
                        R.DATA REMESSA_DATA,
                        R.REQUISICAO REMESSA_REQUISICAO,
                        R.TIPO REMESSA_TIPO,
                        A.CONTROLE,
                        A.MODELO_CODIGO,
                        P.GRADE_CODIGO,
                        A.MATRIZ_CODIGO,
                        A.PRODUTO_CODIGO,
                        P.DESCRICAO PRODUTO_DESCRICAO,
                        A.TAMANHO,
                        A.QUANTIDADE,
                        A.TOLERANCIA,
                        A.CHAPAS,
                        A.PLACAS,
                        A.SOBRAS,
                        A.PLACAS_,
                        A.SOBRAS_,
                        A.CONSUMO_MEDIO,
                        A.OBSERVACAO,
                        A.SITUACAO,
                        P.DESCRICAO,
                        P.COR_CODIGO,
                        A.PROGRAMACAO_BOCA,
                        A.PROGRAMACAO_ESTEIRA,
                        A.PROGRAMACAO_SEQUENCIA,
                        A.HORA_PRODUCAO,
                        A.HORA_LIBERACAO,
                        A.TURNO,
                        A.SEQ,
                        COALESCE(T.VIA_ETIQUETA,0) VIA_ETIQUETA,
                                     
                        COALESCE(A.LIB,'0') LIB,

                        COALESCE((SELECT SUM(S.QUANTIDADE) FROM TBREQUISICAO_SOBRA S
                                   WHERE S.REMESSA = A.REMESSA AND S.TALAO = A.CONTROLE
                                     AND S.REQUISICAO_ID = 0),0.0000) QUANTIDADE_SOBRA,

                       (SELECT TAM_DESCRICAO FROM SP_TAMANHO_GRADE (P.GRADE_CODIGO,A.TAMANHO)) TAMANHO_GRADE,

                        IIF(A.ESPESSURA > 0 AND A.DENSIDADE > 0, LPAD(A.DENSIDADE,5,'0')||LPAD(A.ESPESSURA,5,'0'),
                        IIF(M.ESPESSURA > 0 AND M.DENSIDADE > 0, LPAD(M.DENSIDADE,5,'0')||LPAD(M.ESPESSURA,5,'0'),
                        COALESCE(
                        (SELECT FIRST 1
                          COALESCE((SELECT FIRST 1 LPAD(M.DENSIDADE,5,'0')||LPAD(M.ESPESSURA,5,'0')
                             FROM TBMODELO M WHERE M.CODIGO = P2.MODELO_CODIGO),'0000000000')
                          FROM TBMODELO_CONSUMO_COR MC, TBFAMILIA F, TBPRODUTO P2
                         WHERE MC.MODELO_ID = A.MODELO_CODIGO
                           AND MC.COR_ID = P.COR_CODIGO
                           AND MC.PRODUTO_ID = P2.CODIGO
                           AND P2.FAMILIA_CODIGO = F.CODIGO
                           AND F.DENSIDADE_ESPESSURA = '1'),'0000000000'))) DENS_ESPESS,
                    
                        (SELECT FIRST 1 E.DESCRICAO FROM TBESTEIRA E WHERE A.PROGRAMACAO_ESTEIRA = E.CODIGO) ESTEIRA_DESCRICAO,

                        (SELECT FIRST 1 G.DESCRICAO FROM TBMATRIZ G WHERE A.MATRIZ_CODIGO = G.CODIGO) MATRIZ_DESCRICAO,

                        (SELECT FIRST 1 H.LARGURA   FROM TBMATRIZ H WHERE A.MATRIZ_CODIGO = H.CODIGO) MATRIZ_LARGURA,

                        (SELECT FIRST 1 COALESCE(CLASSE||'.'||LPAD(SUBCLASSE,3,'0'),'0.000') FROM TBCOR WHERE P.COR_CODIGO = CODIGO) CLASSE,

                        (SELECT FIRST 1 COALESCE(PERFIL,'') FROM TBCOR WHERE P.COR_CODIGO = CODIGO) PERFIL, A.PERC_SOBRA, A.APROVEITAMENTO,

                        (SELECT FIRST 1
                            (
                            LPAD(Z.LARGURA        ,10,' ') ||
                            LPAD(COALESCE(Z.ESPESSURA,0),10,' ') ||
                            LPAD(Z.LARGURA_T01    ,10,' ') ||
                            LPAD(Z.LARGURA_T02    ,10,' ') ||
                            LPAD(Z.LARGURA_T03    ,10,' ') ||
                            LPAD(Z.LARGURA_T04    ,10,' ') ||
                            LPAD(Z.LARGURA_T05    ,10,' ') ||
                            LPAD(Z.LARGURA_T06    ,10,' ') ||
                            LPAD(Z.LARGURA_T07    ,10,' ') ||
                            LPAD(Z.LARGURA_T08    ,10,' ') ||
                            LPAD(Z.LARGURA_T09    ,10,' ') ||
                            LPAD(Z.LARGURA_T10    ,10,' ') || 
                            LPAD(Z.LARGURA_T11    ,10,' ') ||
                            LPAD(Z.LARGURA_T12    ,10,' ') ||
                            LPAD(Z.LARGURA_T13    ,10,' ') ||
                            LPAD(Z.LARGURA_T14    ,10,' ') ||
                            LPAD(Z.LARGURA_T15    ,10,' ') ||
                            LPAD(Z.LARGURA_T16    ,10,' ') ||
                            LPAD(Z.LARGURA_T17    ,10,' ') ||
                            LPAD(Z.LARGURA_T18    ,10,' ') ||
                            LPAD(Z.LARGURA_T19    ,10,' ') ||
                            LPAD(Z.LARGURA_T20    ,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T01,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T02,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T03,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T04,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T05,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T06,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T07,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T08,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T09,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T10,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T11,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T12,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T13,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T14,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T15,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T16,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T17,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T18,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T19,10,' ') ||
                            LPAD(Z.COMPRIMENTO_T20,10,' ') ||
                            LPAD(Z.PLACA_T01,10,' ') ||
                            LPAD(Z.PLACA_T02,10,' ') ||
                            LPAD(Z.PLACA_T03,10,' ') ||
                            LPAD(Z.PLACA_T04,10,' ') ||
                            LPAD(Z.PLACA_T05,10,' ') ||
                            LPAD(Z.PLACA_T06,10,' ') ||
                            LPAD(Z.PLACA_T07,10,' ') ||
                            LPAD(Z.PLACA_T08,10,' ') ||
                            LPAD(Z.PLACA_T09,10,' ') ||
                            LPAD(Z.PLACA_T10,10,' ') ||
                            LPAD(Z.PLACA_T11,10,' ') ||
                            LPAD(Z.PLACA_T12,10,' ') ||
                            LPAD(Z.PLACA_T13,10,' ') ||
                            LPAD(Z.PLACA_T14,10,' ') ||
                            LPAD(Z.PLACA_T15,10,' ') ||
                            LPAD(Z.PLACA_T16,10,' ') ||
                            LPAD(Z.PLACA_T17,10,' ') ||
                            LPAD(Z.PLACA_T18,10,' ') ||
                            LPAD(Z.PLACA_T19,10,' ') ||
                            LPAD(Z.PLACA_T20,10,' ') ||
                            LPAD(Z.SOBRA_T01,10,' ') ||
                            LPAD(Z.SOBRA_T02,10,' ') ||
                            LPAD(Z.SOBRA_T03,10,' ') ||
                            LPAD(Z.SOBRA_T04,10,' ') ||
                            LPAD(Z.SOBRA_T05,10,' ') ||
                            LPAD(Z.SOBRA_T06,10,' ') ||
                            LPAD(Z.SOBRA_T07,10,' ') ||
                            LPAD(Z.SOBRA_T08,10,' ') ||
                            LPAD(Z.SOBRA_T09,10,' ') ||
                            LPAD(Z.SOBRA_T10,10,' ') ||
                            LPAD(Z.SOBRA_T11,10,' ') ||
                            LPAD(Z.SOBRA_T12,10,' ') ||
                            LPAD(Z.SOBRA_T13,10,' ') ||
                            LPAD(Z.SOBRA_T14,10,' ') ||
                            LPAD(Z.SOBRA_T15,10,' ') ||
                            LPAD(Z.SOBRA_T16,10,' ') ||
                            LPAD(Z.SOBRA_T17,10,' ') ||
                            LPAD(Z.SOBRA_T18,10,' ') ||
                            LPAD(Z.SOBRA_T19,10,' ') ||
                            LPAD(Z.SOBRA_T20,10,' ')
                            )
                        
                        FROM TBMATRIZ Z
                        
                        WHERE
                            Z.CODIGO = A.MATRIZ_CODIGO)MEDIDAS,

                        (SELECT LIST(LPAD(V.REMESSA_TALAO_ID,4,'0'), ', ')
                           FROM TBREMESSA_CONSUMO_VINCULO V
                          WHERE V.CONSUMO_ID = C.ID) TALAO_ORIGEM,
                          
                        (SELECT FIRST 1 DESCRICAO FROM TBUP P WHERE P.ID = T.UP_ID)UP_DESTINO,

                        (SELECT
                            X.TABELA_ID ||' - '||
                           (SELECT FIRST 1 (SELECT FIRST 1 LEFT(E.RAZAOSOCIAL,20) FROM TBEMPRESA E WHERE E.CODIGO = P.CLIENTE_CODIGO)
                              FROM TBPEDIDO P
                             WHERE P.PEDIDO = X.TABELA_ID) CLIENTE
                        FROM (
                            SELECT FIRST 1 B.TABELA_ID
                            FROM TBPEDIDO_ITEM_PROCESSADO A, TBREMESSA_ITEM_ALOCACAO B
                            WHERE A.REMESSA = C.REMESSA_ID
                              AND A.REMESSA_ACUMULADO_CONTROLE = C.REMESSA_TALAO_ID
                              AND A.ESTABELECIMENTO_CODIGO = B.ESTABELECIMENTO_ID
                              AND A.REMESSA = B.REMESSA
                              AND A.CONTROLE = B.TALAO
                              AND TIPO = 'P'
                              AND B.STATUS = 1) X
                        )CLIENTE
                    
                    FROM
                        TBREMESSA_ITEM_PROCESSADO A,
                        TBPRODUTO P,
                        TBMODELO M,
                        VWREMESSA_TALAO T,
                        TBREMESSA_CONSUMO_VINCULO V,
                        VWREMESSA_CONSUMO C,
                        TBREMESSA R
                    WHERE          
                        A.PRODUTO_CODIGO         = P.CODIGO
                    AND A.MODELO_CODIGO          = M.CODIGO
                    AND A.REMESSA                = C.REMESSA_ID
                    AND A.CONTROLE               = C.REMESSA_TALAO_ID
                    AND V.REMESSA_ID             = T.REMESSA_ID
                    AND V.REMESSA_TALAO_ID       = T.REMESSA_TALAO_ID
                    AND C.ID                     = V.CONSUMO_ID
                    AND V.ETIQUETA               = '1'
                    AND A.REMESSA                = R.NUMERO
                    AND T.ID                     = :TALAO_ID
                    /*@STATUS*/
                    
                    ) X
                ) Y
            ORDER BY MODELO_DENSIDADE, MODELO_ESPESSURA, PRODUTO_CODIGO, TAMANHO, REMESSA_TALAO_ID                
        ";
        
        $args = [
            ':TALAO_ID'	=> $param->TALAO_ID,
            '@STATUS'	=> $status
        ];
		
		return $con->query($sql,$args);
	}
    
    public static function remessaTalaoVinculo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;


        $consumo_id = array_key_exists('CONSUMO_ID', $param) ? "AND CONSUMO_ID IN (" . arrayToList($param->CONSUMO_ID, 9999999999999) . ")" : '';
        $status	    = array_key_exists('STATUS'    , $param) ? "AND STATUS     IN (" . arrayToList($param->STATUS    , 9999999999999) . ")" : '';
        $talao_id   = array_key_exists('TALAO_ID'  , $param) ? "AND TALAO_ID   IN (" . arrayToList($param->TALAO_ID  , 9999999999999) . ")" : '';
        
        $sql =
        "
            SELECT
                ID,
                TALAO_ID,
                CONSUMO_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
				OB,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA,
                UM,
                UM_ALTERNATIVA,
                STATUS
            FROM
                (SELECT
                    V.ID,
                    V.TALAO_ID,
                    V.CONSUMO_ID,
                    V.TIPO,
                    v.TABELA_ID,
                   (CASE
                        V.TIPO
                    WHEN 'R' THEN (SELECT FIRST 1 PRODUTO_ID FROM TBREVISAO WHERE ID = V.TABELA_ID)
                    WHEN 'D' THEN (SELECT FIRST 1 PRODUTO_ID FROM VWREMESSA_TALAO_DETALHE WHERE ID = V.TABELA_ID)
                    ELSE 0 END)PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
					IIF(V.TIPO = 'R', (SELECT FIRST 1 OB FROM TBREVISAO WHERE ID = V.TABELA_ID), '') OB,
                    V.QUANTIDADE,
                    V.QUANTIDADE_ALTERNATIVA,
                    F.UNIDADEMEDIDA_SIGLA UM,
                    F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                    V.STATUS


                FROM
                    TBREMESSA_TALAO_VINCULO V,
                    TBPRODUTO P,
                    TBFAMILIA F

                WHERE
                    P.CODIGO = V.PRODUTO_ID
                AND F.CODIGO = P.FAMILIA_CODIGO)X

            WHERE
                1=1
            /*@STATUS*/
            /*@CONSUMO_ID*/
            /*@TALAO_ID*/
        ";
        $args = [
            '@CONSUMO_ID' => $consumo_id,
            '@STATUS'     => $status,
            '@TALAO_ID'   => $talao_id
        ];
        
        return $con->query($sql,$args);
    }

	public static function verificarAproveitamento($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao();
        
        $id = array_key_exists('ID', $param)
				? "AND R.ID IN (" . arrayToList($param->ID, 999999999) . ")" 
				: '';		

		$sql = "
			SELECT
				R.TALAO_ID
			FROM
				TBREVISAO R
			
			WHERE
				1=1
			/*@ID*/
			AND R.SALDO > 0
		";
		
		$args = [
			'@ID' => $id
		];
        
        return $con->query($sql,$args);		
	}
	
	public static function autenticarUp($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao();

		$sql = "
			SELECT FIRST 1
				U.ID
			FROM
				TBUP U
			WHERE
				U.CODIGO1 = :COD
		";
		
		$args = [
			':COD' => $param->UP_BARRA
		];
        
        return $con->query($sql,$args);
	}
	
	public static function totalizadorDiario($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao();
		
		$estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param) ? "AND R.ESTABELECIMENTO_ID IN (" . arrayToList($param->ESTABELECIMENTO_ID, 999999999) . ")" : '';
		$gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID              IN (" . arrayToList($param->GP_ID             , 999999999) . ")" : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID              IN (" . arrayToList($param->UP_ID             , 999999999) . ")" : '';
		
		if ( array_key_exists('UP_TODOS', $param) && $param->UP_TODOS == '1' ) {
			$up_id = '';
		}
		
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND (T.ESTACAO = 0 OR T.ESTACAO IN (" . arrayToList($param->ESTACAO           , 999999999) . "))" : '';
		
		if ( array_key_exists('ESTACAO_TODOS', $param) && $param->ESTACAO_TODOS == '1' ) {
			$estacao = '';
		}
		
        $periodo            = array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param)
								? "AND R.DATA BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'" 
								: '';
		
        $turno              = array_key_exists('TURNO', $param) && !empty($param->TURNO)
								? "AND TURNO IN('" . arrayToList(ltrim($param->TURNO, 0), 999999999) . "')"
								: '';
		
		//se o 'período' e 'turno' forem passados
//		if ( array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param) ) {
//
//			if ( array_key_exists('TURNO', $param) && array_key_exists('TURNO_HORA_INI', $param) && array_key_exists('TURNO_HORA_FIM', $param) ) {
//				
//				if ( $param->TURNO == '01' ) {
//					$periodo = "AND T.HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '$param->DATA_FIM $param->TURNO_HORA_FIM'";
//				}
//				//se o turno for noite, será adicionado 1 dia na 'data fim' devido à 'data de produção'
//				else if ( $param->TURNO == '02' ) {
//					$data_criada = date_create($param->DATA_FIM);
//					date_add($data_criada, date_interval_create_from_date_string('1 days'));
//					$periodo = "AND T.HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '". date_format($data_criada, 'Y-m-d') ." $param->TURNO_HORA_FIM'";
//				}
//			}
//			else {
//				$periodo = "AND R.DATA BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
//			}
//		}
				
		$sql = " 
			SELECT
				Z.REMESSA_DATA,
				Z.UP_ID,
				Z.UP_DESCRICAO,
				Z.CAPACIDADE_DISPONIVEL,
				Z.UM,

				/* PROGRAMADO */
				Z.CARGA_PROGRAMADA,
				Z.QUANTIDADE_TALAO_PROGRAMADA,
				Z.QUANTIDADE_CARGA_PROGRAMADA,
				Z.QTD_TALAO_UTILIZ_PROGRAMADA,
				Z.QTD_CARGA_UTILIZ_PROGRAMADA,
				Z.QUANTIDADE_PARES_PROGRAMADA,

				/* PRODUZIDO */
				Z.CARGA_UTILIZADA,
				Z.QUANTIDADE_TALAO_UTILIZADA,
				Z.QUANTIDADE_CARGA_UTILIZADA,
				Z.CARGA_PROG_UTILIZ,
				Z.QUANTIDADE_CARGA_PROG_UTILIZ,
				Z.QUANTIDADE_PARES_UTILIZADA,

				/* PENDENTE */
				(COALESCE(Z.CARGA_PROGRAMADA, 0) - COALESCE(Z.CARGA_UTILIZ_PROGRAMADA, 0)) CARGA_PENDENTE,
				(COALESCE(Z.QUANTIDADE_TALAO_PROGRAMADA, 0) - COALESCE(Z.QTD_TALAO_UTILIZ_PROGRAMADA, 0)) QUANTIDADE_TALAO_PENDENTE,
				(COALESCE(Z.QUANTIDADE_CARGA_PROGRAMADA, 0) - COALESCE(Z.QTD_CARGA_UTILIZ_PROGRAMADA, 0)) QUANTIDADE_CARGA_PENDENTE,
				Z.QUANTIDADE_PARES_PENDENTE,

				/*PERCENTUAIS*/
				--PERC_CARGA_PROGRAMADA = (CARGA_PROGRAMADA * 100) / CAPACIDADE_DISPONIVEL
				(Z.CARGA_PROGRAMADA * 100) / NULLIF(Z.CAPACIDADE_DISPONIVEL, 0) PERC_CARGA_PROGRAMADA,
				--PERC_CARGA_UTILIZADA  = (CARGA_UTILIZADA * 100) / CARGA_PROGRAMADA
				(Z.CARGA_UTILIZADA * 100) / NULLIF(Z.CARGA_PROGRAMADA, 0) PERC_CARGA_UTILIZADA,
				--PERC_APROVEITAMENTO   = (CARGA_UTILIZADA * 100) / CAPACIDADE_DISPONIVEL
				(Z.CARGA_UTILIZADA * 100) / NULLIF(Z.CAPACIDADE_DISPONIVEL, 0) PERC_APROVEITAMENTO,

				--EFICIENCIA = ((QTD.PROD. / MIN.PROD.) / (QTD.PROJ. / MIN.PROJ.)) * 100
				COALESCE(
					((CAST(Z.QUANTIDADE_CARGA_UTILIZADA / NULLIF(Z.CARGA_UTILIZADA, 0) AS DOUBLE PRECISION))
					  /
					(CAST(Z.QUANTIDADE_CARGA_PROG_UTILIZ / NULLIF(Z.CARGA_PROG_UTILIZ, 0) AS DOUBLE PRECISION)))
					* 100
				, 0) EFICIENCIA

				FROM(

					SELECT
						X.REMESSA_DATA,
						X.UP_ID,
						X.UP_DESCRICAO,
						X.CAPACIDADE_DISPONIVEL,
						IIF(X.UM_ALTERNATIVA <> '', X.UM_ALTERNATIVA, X.UM) UM,

						/* PROGRAMADO */
						SUM(X.CARGA_PROGRAMADA) CARGA_PROGRAMADA,
						SUM(X.QUANTIDADE_TALAO_PROGRAMADA) QUANTIDADE_TALAO_PROGRAMADA,
						SUM(X.QUANTIDADE_CARGA_PROGRAMADA) QUANTIDADE_CARGA_PROGRAMADA,
						SUM(X.QTD_TALAO_UTILIZ_PROGRAMADA) QTD_TALAO_UTILIZ_PROGRAMADA,
						SUM(X.QTD_CARGA_UTILIZ_PROGRAMADA) QTD_CARGA_UTILIZ_PROGRAMADA,
						SUM(X.CARGA_UTILIZ_PROGRAMADA) CARGA_UTILIZ_PROGRAMADA,
						SUM(CAST(SUBSTRING(X.QUANTIDADE_PARES FROM 64 FOR 21) AS NUMERIC(10,5))) QUANTIDADE_PARES_PROGRAMADA,

						/* PRODUZIDO */
						SUM(CAST(SUBSTRING(X.PRODUZIDO FROM 1  FOR 12) AS NUMERIC(15,4))) CARGA_UTILIZADA,
						SUM(CAST(SUBSTRING(X.PRODUZIDO FROM 14 FOR 5) AS INTEGER)) QUANTIDADE_TALAO_UTILIZADA,
						SUM(CAST(SUBSTRING(X.PRODUZIDO FROM 20 FOR 12) AS NUMERIC(10,5))) QUANTIDADE_CARGA_UTILIZADA,
						SUM(CAST(SUBSTRING(X.PRODUZIDO FROM 33 FOR 12) AS NUMERIC(15,4))) CARGA_PROG_UTILIZ,
						SUM(CAST(SUBSTRING(X.PRODUZIDO FROM 46 FOR 12) AS NUMERIC(10,5))) QUANTIDADE_CARGA_PROG_UTILIZ,
						SUM(CAST(SUBSTRING(X.QUANTIDADE_PARES FROM 22 FOR 21) AS NUMERIC(10,5))) QUANTIDADE_PARES_UTILIZADA,

						/* PENDENTE */
						SUM(CAST(SUBSTRING(X.QUANTIDADE_PARES FROM 43 FOR 21) AS NUMERIC(10,5))) QUANTIDADE_PARES_PENDENTE

						FROM (

							SELECT
								Y.REMESSA_DATA,
								Y.UP_ID,
								Y.UP_DESCRICAO,
								Y.UM,
								Y.UM_ALTERNATIVA,

								(SELECT FIRST 1 COALESCE(CA.MINUTOS,0)
									FROM TBCALENDARIO_UP CA
									WHERE CA.GP_ID = Y.GP_ID
									AND CA.UP_ID = Y.UP_ID
									AND CA.DATA = Y.REMESSA_DATA) CAPACIDADE_DISPONIVEL,

								/* PROGRAMADO */
								SUM(Y.CARGA_PROGRAMADA) CARGA_PROGRAMADA,
								SUM(Y.QUANTIDADE_TALAO_PROGRAMADA) QUANTIDADE_TALAO_PROGRAMADA,
								SUM(Y.QUANTIDADE_CARGA_PROGRAMADA) QUANTIDADE_CARGA_PROGRAMADA,
								SUM(Y.QTD_TALAO_UTILIZ_PROGRAMADA) QTD_TALAO_UTILIZ_PROGRAMADA,
								SUM(Y.QTD_CARGA_UTILIZ_PROGRAMADA) QTD_CARGA_UTILIZ_PROGRAMADA,
								SUM(Y.CARGA_UTILIZ_PROGRAMADA) CARGA_UTILIZ_PROGRAMADA,

								/* PRODUZIDO */
								--CARGA_UTILIZADA|QUANTIDADE_TALAO_UTILIZADA|QUANTIDADE_CARGA_UTILIZADA|CARGA_PROG_UTILIZ|QUANTIDADE_CARGA_PROG_UTILIZ
								COALESCE(
									(SELECT LPAD(COALESCE(SUM(P1.TEMPO_REALIZADO),'0000000.0000'),12,'0')||'|'||
											LPAD(COUNT(T6.ID),5,'0')||'|'||
											LPAD(COALESCE(SUM(
												(SELECT SUM(IIF(D1.QUANTIDADE_ALTERN_PRODUCAO > 0, D1.QUANTIDADE_ALTERN_PRODUCAO, D1.QUANTIDADE_PRODUCAO))
													FROM VWREMESSA_TALAO_DETALHE D1
													WHERE D1.REMESSA_ID = T6.REMESSA_ID AND D1.REMESSA_TALAO_ID = T6.REMESSA_TALAO_ID)
											),'0000000.0000'),12,'0')||'|'||
											LPAD(COALESCE(SUM(P1.TEMPO),'0000000.0000'),12,'0')||'|'||
											LPAD(COALESCE(SUM(IIF(T6.QUANTIDADE_ALTERNATIVA > 0, T6.QUANTIDADE_ALTERNATIVA, CAST(T6.QUANTIDADE AS NUMERIC(15,4)))),'0000000.0000'),12,'0')

										FROM VWREMESSA_TALAO T6, TBPROGRAMACAO P1
										WHERE P1.TABELA_ID     = T6.ID
										  AND P1.TIPO          = 'A'
										  AND P1.GP_ID         = T6.GP_ID
										  AND P1.UP_ID         = T6.UP_ID
										  AND (P1.ESTACAO       = T6.ESTACAO or T6.ESTACAO = 0)
										  AND T6.GP_ID         = Y.GP_ID
										  AND T6.UP_ID         = Y.UP_ID
										  AND T6.ESTACAO       = Y.ESTACAO
										  AND T6.DATA_PRODUCAO = Y.REMESSA_DATA
										  /*@TURNO*/)
								,'00000|0000000.0000|0000000.0000|0000000.0000') PRODUZIDO,

								/* PARES */
								(SELECT
									 --PARES PRODUZIDOS POR DATA DE REMESSA
									(LPAD(SUM(L.PARES_PROD_DATA_REM), 21)||
									 --PARES PRODUZIDOS POR DATA DE PRODUCAO
									 LPAD(SUM(L.PARES_PROD_DATA_PROD), 21)||
									 --PARES PENDENTES POR DATA DE REMESSA
									 LPAD(SUM(L.PARES_PENDENTE_DATA_REM), 21)||
									 --PARES PROGRAMADOS POR DATA DE REMESSA
									 LPAD(SUM(L.PARES_PROGRAM_DATA_REM), 21))

									FROM
										(SELECT
											--PARES PRODUZIDOS POR DATA DE REMESSA
											IIF(K.DATA_REMESSA = Y.REMESSA_DATA AND K.STATUS = '2',
												COALESCE(
													IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
														((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
												, 0)
											, 0) PARES_PROD_DATA_REM,

											--PARES PRODUZIDOS POR DATA DE PRODUCAO
											IIF(K.DATA_PRODUCAO = Y.REMESSA_DATA /*@TURNO*/ AND K.STATUS = '2',
												COALESCE(
													IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
														((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
												, 0)
											, 0) PARES_PROD_DATA_PROD,

											--PARES PENDENTES POR DATA DE REMESSA
											IIF(K.DATA_REMESSA = Y.REMESSA_DATA AND K.STATUS = '1',
												COALESCE(
													IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
														((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
												, 0)
											, 0) PARES_PENDENTE_DATA_REM,

											--PARES PROGRAMADOS POR DATA DE REMESSA
											IIF(K.DATA_REMESSA = Y.REMESSA_DATA,
												COALESCE(
													IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
														((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
												, 0)
											, 0) PARES_PROGRAM_DATA_REM

											FROM (

												SELECT
													C1.QUANTIDADE_ORIGINAL QUANTIDADE_ORIGEM,
													IIF(COALESCE(C1.QUANTIDADE_NECES,0) > 0,C1.QUANTIDADE_NECES, C1.QUANTIDADE) QUANTIDADE_CONSUMO,
													V1.QUANTIDADE QUANTIDADE_VINCULO,
													R2.DATA DATA_REMESSA,
													T4.DATA_PRODUCAO,
													T4.TURNO,
													T4.STATUS

													FROM VWREMESSA_TALAO T4
													INNER JOIN TBREMESSA_CONSUMO_VINCULO V1 ON  V1.REMESSA_ID = T4.REMESSA_ID AND V1.REMESSA_TALAO_ID = T4.REMESSA_TALAO_ID
													INNER JOIN VWREMESSA_CONSUMO C1 ON C1.ID = V1.CONSUMO_ID
													INNER JOIN VWREMESSA R2 ON R2.REMESSA_ID = T4.REMESSA_ID

													WHERE T4.GP_ID   = Y.GP_ID
													  AND T4.UP_ID   = Y.UP_ID
													  AND T4.ESTACAO = Y.ESTACAO

											) K
										) L

								) QUANTIDADE_PARES

							FROM (
								SELECT
									R.DATA REMESSA_DATA,
									T.GP_ID,
									T.UP_ID,
									(SELECT FIRST 1 U.DESCRICAO FROM TBUP U WHERE U.ID = T.UP_ID) UP_DESCRICAO,
									T.ESTACAO,
									F.UNIDADEMEDIDA_SIGLA UM,
									F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,

									/* PROGRAMADO */
									IIF(T.ID>0,1,0) QUANTIDADE_TALAO_PROGRAMADA,
									IIF(T.STATUS='2',1,0) QTD_TALAO_UTILIZ_PROGRAMADA,
									IIF(T.QUANTIDADE_ALTERNATIVA > 0, T.QUANTIDADE_ALTERNATIVA, T.QUANTIDADE) QUANTIDADE_CARGA_PROGRAMADA,
									IIF(T.STATUS = '2',IIF(T.QUANTIDADE_ALTERNATIVA > 0, T.QUANTIDADE_ALTERNATIVA, T.QUANTIDADE),0) QTD_CARGA_UTILIZ_PROGRAMADA,
									P2.TEMPO CARGA_PROGRAMADA,
									IIF(T.STATUS='2',P2.TEMPO,0) CARGA_UTILIZ_PROGRAMADA

								 FROM VWREMESSA_TALAO T, VWREMESSA R, TBFAMILIA F, TBPROGRAMACAO P2

								WHERE 
									1=1
								AND R.REMESSA_ID = T.REMESSA_ID
								AND F.CODIGO = R.FAMILIA_ID

								AND P2.TABELA_ID    = T.ID
								AND P2.GP_ID        = T.GP_ID
								AND P2.UP_ID        = T.UP_ID
								AND (P2.ESTACAO      = T.ESTACAO OR T.ESTACAO = 0)

								/*@ESTABELECIMENTO_ID*/
								/*@GP_ID*/
								/*@UP_ID*/
								/*@ESTACAO*/
								/*@PERIODO*/
							) Y


							GROUP BY
								Y.REMESSA_DATA,
								Y.GP_ID,
								Y.UP_ID,
								Y.UP_DESCRICAO,
								Y.ESTACAO,
								Y.UM,
								Y.UM_ALTERNATIVA
						) X

					GROUP BY
						X.REMESSA_DATA,
						X.UP_ID,
						X.UP_DESCRICAO,
						X.CAPACIDADE_DISPONIVEL,
						UM
				) Z
		";
		
		$args = [
			'@ESTABELECIMENTO_ID'	=> $estabelecimento_id,
			'@GP_ID'				=> $gp_id,
			'@UP_ID'				=> $up_id,
			'@ESTACAO'				=> $estacao,
			'@PERIODO'				=> $periodo,
			'@TURNO'				=> $turno
		];
        
        return $con->query($sql,$args);
	}
	
	public static function totalizadorProgramado($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao();
		
		$estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param) ? "AND R.ESTABELECIMENTO_ID IN (" . arrayToList($param->ESTABELECIMENTO_ID, 999999999) . ")" : '';
		$gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID              IN (" . arrayToList($param->GP_ID             , 999999999) . ")" : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID              IN (" . arrayToList($param->UP_ID             , 999999999) . ")" : '';
		
		if ( array_key_exists('UP_TODOS', $param) && $param->UP_TODOS == '1' ) {
			$up_id = '';
		}
		
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND T.ESTACAO            IN (" . arrayToList($param->ESTACAO           , 999999999) . ")" : '';
		
		if ( array_key_exists('ESTACAO_TODOS', $param) && $param->ESTACAO_TODOS == '1' ) {
			$estacao = '';
		}
		
		$periodo            = array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param)
								? "AND R.DATA BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'" 
								: '';
		
		$sql = "
			SELECT
				X.REMESSA_DATA,
				X.UP_ID,
				X.UP_DESCRICAO,
				X.CAPACIDADE_DISPONIVEL,
				IIF(X.UM_ALTERNATIVA <> '', X.UM_ALTERNATIVA, X.UM) UM,

				/*CARGA PROGRAMADA*/
				X.CARGA_PROGRAMADA,
				X.QUANTIDADE_TALAO_PROGRAMADA,
				X.QUANTIDADE_CARGA_PROGRAMADA,
				X.QTD_TALAO_UTILIZ_PROGRAMADA,
				X.QTD_CARGA_UTILIZ_PROGRAMADA,
				
				/*CARGA PENDENTE*/
				(COALESCE(X.CARGA_PROGRAMADA, 0) - COALESCE(X.CARGA_UTILIZ_PROGRAMADA, 0)) CARGA_PENDENTE,
				(COALESCE(X.QUANTIDADE_TALAO_PROGRAMADA, 0) - COALESCE(X.QTD_TALAO_UTILIZ_PROGRAMADA, 0)) QUANTIDADE_TALAO_PENDENTE,
				(COALESCE(X.QUANTIDADE_CARGA_PROGRAMADA, 0) - COALESCE(X.QTD_CARGA_UTILIZ_PROGRAMADA, 0)) QUANTIDADE_CARGA_PENDENTE
				
				FROM(
					SELECT
						R.DATA REMESSA_DATA,
						COUNT(T.ID) QUANTIDADE_TALOES,
						T.UP_ID,
						(SELECT FIRST 1 U.DESCRICAO FROM TBUP U WHERE U.ID = T.UP_ID) UP_DESCRICAO,
						F.UNIDADEMEDIDA_SIGLA UM,
						F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,

						/**
						 * DADOS POR DATA DA REMESSA (SUBSELECTS)
						 */
						(SELECT FIRST 1 CA.MINUTOS
							FROM TBCALENDARIO_UP CA
							WHERE CA.GP_ID = T.GP_ID
							AND CA.UP_ID = T.UP_ID
							AND CA.DATA = R.DATA
						) CAPACIDADE_DISPONIVEL,

						/*CARGA PROGRAMADA*/

						(SELECT COUNT(T7.ID)
							FROM VWREMESSA_TALAO T7, VWREMESSA R7
							WHERE
								T7.GP_ID        = T.GP_ID
							AND T7.UP_ID        = T.UP_ID
							AND T7.ESTACAO      = T.ESTACAO
							AND R7.REMESSA_ID   = T7.REMESSA_ID
							AND R7.DATA         = R.DATA
						) QUANTIDADE_TALAO_PROGRAMADA,

						(SELECT COUNT(T7.ID)
							FROM VWREMESSA_TALAO T7, VWREMESSA R7
							WHERE
								T7.GP_ID        = T.GP_ID
							AND T7.UP_ID        = T.UP_ID
							AND T7.ESTACAO      = T.ESTACAO
							AND R7.REMESSA_ID   = T7.REMESSA_ID
							AND T7.STATUS       = '2'
							AND R7.DATA         = R.DATA
						) QTD_TALAO_UTILIZ_PROGRAMADA,

						(SELECT SUM(P2.TEMPO)
							FROM TBPROGRAMACAO P2, VWREMESSA_TALAO T6, VWREMESSA R6
							WHERE
								P2.TABELA_ID    = T6.ID
							AND P2.GP_ID        = T.GP_ID
							AND P2.UP_ID        = T.UP_ID
							AND P2.ESTACAO      = T.ESTACAO
							AND R6.REMESSA_ID   = T6.REMESSA_ID
							AND R6.DATA         = R.DATA
						) CARGA_PROGRAMADA,

						(SELECT SUM(P2.TEMPO)
							FROM TBPROGRAMACAO P2, VWREMESSA_TALAO T6, VWREMESSA R6
							WHERE
								P2.TABELA_ID    = T6.ID
							AND P2.GP_ID        = T.GP_ID
							AND P2.UP_ID        = T.UP_ID
							AND P2.ESTACAO      = T.ESTACAO
							AND R6.REMESSA_ID   = T6.REMESSA_ID
							AND T6.STATUS       = '2'
							AND R6.DATA         = R.DATA
						) CARGA_UTILIZ_PROGRAMADA,

						(SELECT SUM(IIF(T5.QUANTIDADE_ALTERNATIVA > 0, T5.QUANTIDADE_ALTERNATIVA, T5.QUANTIDADE))
							FROM VWREMESSA_TALAO T5, VWREMESSA R5
							WHERE
								T5.GP_ID        = T.GP_ID
							AND T5.UP_ID        = T.UP_ID
							AND T5.ESTACAO      = T.ESTACAO
							AND R5.REMESSA_ID   = T5.REMESSA_ID
							AND R5.DATA         = R.DATA
						) QUANTIDADE_CARGA_PROGRAMADA,

						(SELECT SUM(IIF(T5.QUANTIDADE_ALTERNATIVA > 0, T5.QUANTIDADE_ALTERNATIVA, T5.QUANTIDADE))
							FROM VWREMESSA_TALAO T5, VWREMESSA R5
							WHERE
								T5.GP_ID        = T.GP_ID
							AND T5.UP_ID        = T.UP_ID
							AND T5.ESTACAO      = T.ESTACAO
							AND R5.REMESSA_ID   = T5.REMESSA_ID
							AND T5.STATUS       = '2'
							AND R5.DATA         = R.DATA
						) QTD_CARGA_UTILIZ_PROGRAMADA

					FROM
						VWREMESSA_TALAO T

						INNER JOIN VWREMESSA R
							ON R.REMESSA_ID = T.REMESSA_ID

						INNER JOIN TBFAMILIA F
							ON F.CODIGO = R.FAMILIA_ID

					WHERE
						1=1
						/*@ESTABELECIMENTO_ID*/
						/*@GP_ID*/
						/*@UP_ID*/
						/*@ESTACAO*/
						/*@PERIODO*/

					GROUP BY
						R.DATA, F.UNIDADEMEDIDA_SIGLA, F.UNIDADEMEDIDA_ALTERNATIVO, T.GP_ID, T.UP_ID, T.ESTACAO
				)X
		";
		
		$args = [
			'@ESTABELECIMENTO_ID'	=> $estabelecimento_id,
			'@GP_ID'				=> $gp_id,
			'@UP_ID'				=> $up_id,
			'@ESTACAO'				=> $estacao,
			'@PERIODO'				=> $periodo
		];
        
        return $con->query($sql,$args);
	}

	public static function totalizadorProduzido($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao();
		
		$estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param) ? "AND T.ESTABELECIMENTO_ID IN (" . arrayToList($param->ESTABELECIMENTO_ID, 999999999) . ")" : '';
		$gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID              IN (" . arrayToList($param->GP_ID             , 999999999) . ")" : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID              IN (" . arrayToList($param->UP_ID             , 999999999) . ")" : '';
		
		if ( array_key_exists('UP_TODOS', $param) && $param->UP_TODOS == '1' ) {
			$up_id = '';
		}
		
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND T.ESTACAO            IN (" . arrayToList($param->ESTACAO           , 999999999) . ")" : '';
		
		if ( array_key_exists('ESTACAO_TODOS', $param) && $param->ESTACAO_TODOS == '1' ) {
			$estacao = '';
		}
		
		$periodo = '';
		
		//se o 'período' e 'turno' forem passados
		if ( array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param) ) {

			if ( array_key_exists('TURNO', $param) && array_key_exists('TURNO_HORA_INI', $param) && array_key_exists('TURNO_HORA_FIM', $param) ) {
				
				if ( $param->TURNO == '01' ) {
					$periodo = "AND T.HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '$param->DATA_FIM $param->TURNO_HORA_FIM'";
				}
				//se o turno for noite, será adicionado 1 dia na 'data fim' devido à 'data de produção'
				else if ( $param->TURNO == '02' ) {
					$data_criada = date_create($param->DATA_FIM);
					date_add($data_criada, date_interval_create_from_date_string('1 days'));
					$periodo = "AND T.HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '". date_format($data_criada, 'Y-m-d') ." $param->TURNO_HORA_FIM'";
				}
			}
			else {
				$periodo = "AND T.DATA_PRODUCAO BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
			}
		}
		
		$sql = " 
			SELECT DISTINCT			
				/*CARGA UTILIZADA*/
				X.DATA_PRODUCAO,
				SUM(X.CARGA_UTILIZADA) CARGA_UTILIZADA,
                SUM(X.QUANTIDADE_TALAO_UTILIZADA) QUANTIDADE_TALAO_UTILIZADA,
                SUM(X.QUANTIDADE_CARGA_UTILIZADA) QUANTIDADE_CARGA_UTILIZADA,
                SUM(X.CARGA_PROG_UTILIZ) CARGA_PROG_UTILIZ,
                SUM(X.QUANTIDADE_CARGA_PROG_UTILIZ) QUANTIDADE_CARGA_PROG_UTILIZ
                
                FROM (
                    SELECT
                        /**
                         * DADOS POR DATA DE PRODUCAO (SUBSELECTS)
                         */

                        /*CARGA UTILIZADA*/
						
						T.DATA_PRODUCAO,

                        (SELECT SUM(P1.TEMPO_REALIZADO)
                            FROM TBPROGRAMACAO P1, VWREMESSA_TALAO T1
                            WHERE
                                P1.TABELA_ID     = T1.ID
                            AND P1.GP_ID         = T.GP_ID
                            AND P1.UP_ID         = T.UP_ID
                            AND P1.ESTACAO       = T.ESTACAO
                            AND T1.HORA_PRODUCAO = T.HORA_PRODUCAO
                        ) CARGA_UTILIZADA,

                        (SELECT SUM(IIF(D1.QUANTIDADE_ALTERN_PRODUCAO > 0, D1.QUANTIDADE_ALTERN_PRODUCAO, D1.QUANTIDADE_PRODUCAO))
                            FROM VWREMESSA_TALAO_DETALHE D1, VWREMESSA_TALAO T2
                            WHERE
                                D1.REMESSA_ID       = T2.REMESSA_ID
                            AND D1.REMESSA_TALAO_ID = T2.REMESSA_TALAO_ID
                            AND T2.GP_ID            = T.GP_ID
                            AND T2.UP_ID            = T.UP_ID
                            AND T2.ESTACAO          = T.ESTACAO
                            AND T2.HORA_PRODUCAO    = T.HORA_PRODUCAO
                        ) QUANTIDADE_CARGA_UTILIZADA,

                        (SELECT SUM(PROG.TEMPO)
                            FROM TBPROGRAMACAO PROG, VWREMESSA_TALAO T6, VWREMESSA R6
                            WHERE
                                PROG.TABELA_ID    = T6.ID
                            AND PROG.GP_ID        = T.GP_ID
                            AND PROG.UP_ID        = T.UP_ID
                            AND PROG.ESTACAO      = T.ESTACAO
                            AND R6.REMESSA_ID     = T6.REMESSA_ID
                            AND T6.HORA_PRODUCAO  = T.HORA_PRODUCAO
                        ) CARGA_PROG_UTILIZ, --PROGRAMADA DA CARGA UTILIZADA

                        (SELECT SUM(IIF(T5.QUANTIDADE_ALTERNATIVA > 0, T5.QUANTIDADE_ALTERNATIVA, T5.QUANTIDADE))
                            FROM VWREMESSA_TALAO T5, VWREMESSA R5
                            WHERE
                                T5.GP_ID         = T.GP_ID
                            AND T5.UP_ID         = T.UP_ID
                            AND T5.ESTACAO       = T.ESTACAO
                            AND R5.REMESSA_ID    = T5.REMESSA_ID
                            AND T5.HORA_PRODUCAO = T.HORA_PRODUCAO
                        ) QUANTIDADE_CARGA_PROG_UTILIZ, --PROGRAMADA DA QTD CARGA UTILIZADA

                        (SELECT COUNT(T6.ID)
                            FROM VWREMESSA_TALAO T6
                            WHERE
                                T6.GP_ID         = T.GP_ID
                            AND T6.UP_ID         = T.UP_ID
                            AND T6.ESTACAO       = T.ESTACAO
                            AND T6.HORA_PRODUCAO = T.HORA_PRODUCAO
                        ) QUANTIDADE_TALAO_UTILIZADA
						
						FROM
							VWREMESSA_TALAO T

						WHERE
							1=1
							/*@ESTABELECIMENTO_ID*/
							/*@GP_ID*/
							/*@UP_ID*/
							/*@ESTACAO*/
							/*@PERIODO*/
				) X
				
				GROUP BY 
					X.DATA_PRODUCAO
		";
		
		$args = [
			'@ESTABELECIMENTO_ID'	=> $estabelecimento_id,
			'@GP_ID'				=> $gp_id,
			'@UP_ID'				=> $up_id,
			'@ESTACAO'				=> $estacao,
			'@PERIODO'				=> $periodo
		];
        
        return $con->query($sql,$args);
		
	}

	public static function totalizadorParPorDataRemessa($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao();
		
		$estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param) ? "AND T.ESTABELECIMENTO_ID IN (" . arrayToList($param->ESTABELECIMENTO_ID, 999999999) . ")" : '';
		$gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID              IN (" . arrayToList($param->GP_ID             , 999999999) . ")" : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID              IN (" . arrayToList($param->UP_ID             , 999999999) . ")" : '';
		
		if ( array_key_exists('UP_TODOS', $param) && $param->UP_TODOS == '1' ) {
			$up_id = '';
		}
		
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND T.ESTACAO            IN (" . arrayToList($param->ESTACAO           , 999999999) . ")" : '';
		
		if ( array_key_exists('ESTACAO_TODOS', $param) && $param->ESTACAO_TODOS == '1' ) {
			$estacao = '';
		}
		
		$periodo = '';
		
		//se o 'período' e 'turno' forem passados
		if ( array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param) ) {
			$periodo = "AND R.DATA BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
		}
		
		$sql = " 
			SELECT
				--PARES PRODUZIDOS POR DATA DE REMESSA
				SUM(L.PARES_PROD_DATA_REM) QUANTIDADE_PARES_UTILIZADA,

				--PARES PENDENTES POR DATA DE REMESSA
				SUM(L.PARES_PENDENTE_DATA_REM) QUANTIDADE_PARES_PENDENTE,

				--PARES PROGRAMADOS POR DATA DE REMESSA
				SUM(L.PARES_PROGRAM_DATA_REM) QUANTIDADE_PARES_PROGRAMADA

				FROM (
				
					SELECT
						--PARES PRODUZIDOS POR DATA DE REMESSA
						IIF(K.STATUS = '2',
							COALESCE(
								IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
									((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
							, 0)
						, 0) PARES_PROD_DATA_REM,

						--PARES PENDENTES POR DATA DE REMESSA
						IIF(K.STATUS = '1',
							COALESCE(
								IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
									((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
							, 0)
						, 0) PARES_PENDENTE_DATA_REM,

						--PARES PROGRAMADOS POR DATA DE REMESSA
						COALESCE(
							IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
								((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
						, 0) PARES_PROGRAM_DATA_REM

						FROM (

							SELECT
								C.QUANTIDADE_ORIGINAL QUANTIDADE_ORIGEM,
								IIF(COALESCE(C.QUANTIDADE_NECES,0) > 0,C.QUANTIDADE_NECES, C.QUANTIDADE) QUANTIDADE_CONSUMO,
								V.QUANTIDADE QUANTIDADE_VINCULO,
								T.STATUS

								FROM
									VWREMESSA_TALAO T

								INNER JOIN TBREMESSA_CONSUMO_VINCULO V
									ON  V.REMESSA_ID        = T.REMESSA_ID
									AND V.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID

								INNER JOIN VWREMESSA_CONSUMO C
									ON C.ID = V.CONSUMO_ID

								INNER JOIN VWREMESSA R
									ON R.REMESSA_ID = T.REMESSA_ID

								WHERE
									1=1
								/*@ESTABELECIMENTO_ID*/
								/*@GP_ID*/
								/*@UP_ID*/
								/*@ESTACAO*/
								/*@PERIODO*/

						) K
				) L
		";
		
		$args = [
			'@ESTABELECIMENTO_ID'	=> $estabelecimento_id,
			'@GP_ID'				=> $gp_id,
			'@UP_ID'				=> $up_id,
			'@ESTACAO'				=> $estacao,
			'@PERIODO'				=> $periodo
		];
        
        return $con->query($sql,$args);
	}
	
	public static function totalizadorParPorDataProducao($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao();
		
		$estabelecimento_id = array_key_exists('ESTABELECIMENTO_ID', $param) ? "AND T.ESTABELECIMENTO_ID IN (" . arrayToList($param->ESTABELECIMENTO_ID, 999999999) . ")" : '';
		$gp_id              = array_key_exists('GP_ID'             , $param) ? "AND T.GP_ID              IN (" . arrayToList($param->GP_ID             , 999999999) . ")" : '';
        $up_id              = array_key_exists('UP_ID'             , $param) ? "AND T.UP_ID              IN (" . arrayToList($param->UP_ID             , 999999999) . ")" : '';
		
		if ( array_key_exists('UP_TODOS', $param) && $param->UP_TODOS == '1' ) {
			$up_id = '';
		}
		
        $estacao            = array_key_exists('ESTACAO'           , $param) ? "AND T.ESTACAO            IN (" . arrayToList($param->ESTACAO           , 999999999) . ")" : '';
		
		if ( array_key_exists('ESTACAO_TODOS', $param) && $param->ESTACAO_TODOS == '1' ) {
			$estacao = '';
		}
		
		$periodo = '';
		
		//se o 'período' e 'turno' forem passados
		if ( array_key_exists('DATA_INI', $param) && array_key_exists('DATA_FIM', $param) ) {

			if ( array_key_exists('TURNO', $param) && array_key_exists('TURNO_HORA_INI', $param) && array_key_exists('TURNO_HORA_FIM', $param) ) {
				
				if ( $param->TURNO == '01' ) {
					$periodo = "AND T.HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '$param->DATA_FIM $param->TURNO_HORA_FIM'";
				}
				//se o turno for noite, será adicionado 1 dia na 'data fim' devido à 'data de produção'
				else if ( $param->TURNO == '02' ) {
					$data_criada = date_create($param->DATA_FIM);
					date_add($data_criada, date_interval_create_from_date_string('1 days'));
					$periodo = "AND T.HORA_PRODUCAO BETWEEN '$param->DATA_INI $param->TURNO_HORA_INI' AND '". date_format($data_criada, 'Y-m-d') ." $param->TURNO_HORA_FIM'";
				}
			}
			else {
				$periodo = "AND T.DATA_PRODUCAO BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
			}
		}
		
		$sql = " 
			SELECT
				--PARES PRODUZIDOS POR DATA DE PRODUCAO
				SUM(L.PARES_PROD_DATA_PROD) QUANTIDADE_PARES_UTILIZADA

				FROM (
				
					SELECT
						--PARES PRODUZIDOS POR DATA DE PRODUCAO
						IIF(K.STATUS = '2',
							COALESCE(
								IIF(K.QUANTIDADE_CONSUMO = K.QUANTIDADE_VINCULO,K.QUANTIDADE_ORIGEM,
									((K.QUANTIDADE_ORIGEM * K.QUANTIDADE_VINCULO)/K.QUANTIDADE_CONSUMO))
							, 0)
						, 0) PARES_PROD_DATA_PROD

						FROM (

							SELECT
								C.QUANTIDADE_ORIGINAL QUANTIDADE_ORIGEM,
								IIF(COALESCE(C.QUANTIDADE_NECES,0) > 0,C.QUANTIDADE_NECES, C.QUANTIDADE) QUANTIDADE_CONSUMO,
								V.QUANTIDADE QUANTIDADE_VINCULO,
								T.STATUS

								FROM
									VWREMESSA_TALAO T

								INNER JOIN TBREMESSA_CONSUMO_VINCULO V
									ON  V.REMESSA_ID        = T.REMESSA_ID
									AND V.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID

								INNER JOIN VWREMESSA_CONSUMO C
									ON C.ID = V.CONSUMO_ID

								INNER JOIN VWREMESSA R
									ON R.REMESSA_ID = T.REMESSA_ID

								WHERE
									1=1
								/*@ESTABELECIMENTO_ID*/
								/*@GP_ID*/
								/*@UP_ID*/
								/*@ESTACAO*/
								/*@PERIODO*/

						) K
				) L
		";
		
		$args = [
			'@ESTABELECIMENTO_ID'	=> $estabelecimento_id,
			'@GP_ID'				=> $gp_id,
			'@UP_ID'				=> $up_id,
			'@ESTACAO'				=> $estacao,
			'@PERIODO'				=> $periodo
		];
        
        return $con->query($sql,$args);
	}
	
}

class _22020DaoInsert
{
    /**
     * Registra o histórico da programação
     * @param type $param
     * @param _Conexao $con
     */
    public static function programacaoHistorico($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            INSERT INTO TBPROGRAMACAO_REGISTRO (
                PROGRAMACAO_ID,
                STATUS,
                OPERADOR_ID
            ) VALUES (
               :PROGRAMACAO_ID,       
               :STATUS,          
               :OPERADOR_ID        
            );
        ";
        $args = [
            ':PROGRAMACAO_ID' => $param->PROGRAMACAO_ID,
            ':STATUS'         => $param->PROGRAMACAO_HISTORICO_STATUS,
            ':OPERADOR_ID'    => $param->OPERADOR_ID,
        ];
		
        $con->execute($sql, $args);
    }

    /**
     * Registra vinculo da projeção com item de estoque
     * @param type $param
     * @param _Conexao $con
     */
    public static function vinculo($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        /**
         * 'D' : BUSCA O REGISTRO NA VWREMESSA_TALAO_DETALHE
         * 'R' : BUSCA O REGISTRO NA TBREVISAO
         */
        if ( trim($param->TIPO) == 'D' && isset($param->QUANTIDADE_ALOCAR) ) {$tipo = "D.ID, D.PRODUTO_ID, D.TAMANHO, " . $param->QUANTIDADE_ALOCAR . ", QUANTIDADE_ALTERN_SALDO FROM VWREMESSA_TALAO_DETALHE D WHERE D.ID = :ITEM_ESTOQUE_ID";} else 
        if ( trim($param->TIPO) == 'D' ) {$tipo = "D.ID, D.PRODUTO_ID, D.TAMANHO, D.QUANTIDADE_SALDO, QUANTIDADE_ALTERN_SALDO FROM VWREMESSA_TALAO_DETALHE D WHERE D.ID = :ITEM_ESTOQUE_ID";} else 
        if ( trim($param->TIPO) == 'R' ) {
            $tipo = "
                    R.ID,
                    R.PRODUTO_ID,
                    R.TAMANHO,
                    (R.SALDO - (SELECT COALESCE(SUM(V.QUANTIDADE), 0)
                    FROM TBREMESSA_TALAO_VINCULO V
                    WHERE V.STATUS <> '1'
                      AND V.TIPO = 'R'
                      AND V.TABELA_ID = R.ID)),
                    (R.METRAGEM_SALDO - (SELECT COALESCE(SUM(V.QUANTIDADE_ALTERNATIVA), 0)
                    FROM TBREMESSA_TALAO_VINCULO V
                    WHERE V.STATUS <> '1'
                      AND V.TIPO = 'R'
                      AND V.TABELA_ID = R.ID))
                FROM TBREVISAO R
                WHERE R.ID = :ITEM_ESTOQUE_ID
            ";
        }
        
        $sql =
        "
            INSERT INTO TBREMESSA_TALAO_VINCULO (
                TALAO_ID,
                CONSUMO_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                TAMANHO,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA
            )
            SELECT
                :TALAO_ID,
                :CONSUMO_ID,
                :TIPO,
                /*@TIPO*/
        ";
        $args = [
            ':TALAO_ID'        => $param->TALAO_ID,
            ':CONSUMO_ID'      => $param->CONSUMO_ID,
            ':TIPO'            => $param->TIPO,
            ':ITEM_ESTOQUE_ID' => $param->ITEM_ESTOQUE_ID,
            '@TIPO'            => $tipo,
        ];
        
        $con->execute($sql, $args);
    }
	
	/**
     * Registra vinculo da projeção com item de estoque (matéria-prima).
     * @param array $param
     * @param _Conexao $con
     */
    public static function vinculoMateriaPrima($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $sql =
        "
            INSERT INTO TBREMESSA_TALAO_VINCULO (
                TALAO_ID,
                CONSUMO_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                TAMANHO,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA
            )
            VALUES (
                :TALAO_ID,
                :CONSUMO_ID,
                :TIPO,
                :TABELA_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :QUANTIDADE,
                :QUANTIDADE_ALTERNATIVA
			)
        ";
		
        $args = [
            ':TALAO_ID'					=> $param->TALAO_ID,
            ':CONSUMO_ID'				=> $param->CONSUMO_ID,
            ':TIPO'						=> $param->TIPO,
            ':TABELA_ID'				=> $param->TABELA_ID,
			':PRODUTO_ID'				=> $param->PRODUTO_ID,
			':TAMANHO'					=> $param->TAMANHO,
			':QUANTIDADE'				=> $param->QUANTIDADE,
			':QUANTIDADE_ALTERNATIVA'	=> $param->QUANTIDADE_ALTERNATIVA
        ];
		
        $con->execute($sql, $args);
    }

	public static function registrarAproveitamento($param = [], _Conexao $con = null) {
		
		$con = $con ? $con : new _Conexao;
		
		$sql = "
			INSERT INTO TBREMESSA_TALAO_VINCULO (
                TALAO_ID,
                REMESSA_TALAO_DETALHE_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                TAMANHO,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA
            )
            SELECT
                :TALAO_ID,
                :REMESSA_TALAO_DETALHE_ID,
                :TIPO,
                R.ID,
				R.PRODUTO_ID,
				R.TAMANHO,
				:QUANTIDADE,
				0
			FROM TBREVISAO R
			WHERE R.ID = :TABELA_ID
			  AND R.SALDO > 0
		";
		
//		(R.SALDO - (SELECT COALESCE(SUM(V.QUANTIDADE), 0)
//								FROM TBREMESSA_TALAO_VINCULO V
//								WHERE V.STATUS <> '1'
//								  AND V.TIPO = 'R'
//								  AND V.TABELA_ID = R.ID))
		
		$args = [
			':TALAO_ID'					=> $param->TALAO_ID,
			':REMESSA_TALAO_DETALHE_ID'	=> $param->REMESSA_TALAO_DETALHE_ID,
			':TIPO'						=> $param->TIPO,
			':QUANTIDADE'				=> $param->QUANTIDADE,
			':TABELA_ID'				=> $param->ID
		];
		
		$con->execute($sql, $args);
		
	}
}

class _22020DaoUpdate
{

    /**
     * Bloqueia a Estação (à coloca em uso)
     * @param type $param
     * @param _Conexao $con
     */
    public static function estacaoBloqueio($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE TBUP_ESTACAO SET 
                TALAO_ID = :TALAO_ID,
                INCREMENTO = 1
            WHERE (UP_ID = :UP_ID) AND (ID = :ID);
        ";
        
        $args = [
            ':TALAO_ID' => $param->ESTACAO_TALAO_ID,
            ':UP_ID'    => $param->UP_ID,
            ':ID'       => $param->ESTACAO,
        ];

        $con->execute($sql, $args);
    }

    /**
     * Bloqueia a Estação (à coloca em uso)
     * @param type $param
     * @param _Conexao $con
     */
    public static function programacao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $estacao			= array_key_exists('ESTACAO'			, $param) ? ", ESTACAO = "			. arrayToList($param->ESTACAO           , 0) : '';
        $status				= array_key_exists('PROGRAMACAO_STATUS'	, $param) ? ", STATUS = "			. arrayToList($param->PROGRAMACAO_STATUS, 0) : '';
		$tempo_realizado	= array_key_exists('TEMPO_REALIZADO'	, $param) ? ", TEMPO_REALIZADO = "	. arrayToList($param->TEMPO_REALIZADO   , 0) : '';
        $id					= array_key_exists('PROGRAMACAO_ID'     , $param) ? "AND ID        = "		. arrayToList($param->PROGRAMACAO_ID    , 0) : '';
        $talao_id			= array_key_exists('TALAO_ID'           , $param) ? "AND TABELA_ID = "		. arrayToList($param->TALAO_ID          , 0) : '';
        
        $sql =
        "
            UPDATE TBPROGRAMACAO SET
                ID = ID
                /*@ESTACAO*/
                /*@STATUS*/
				/*@TEMPO_REALIZADO*/
            WHERE
                ID = ID
                /*@ID*/
                /*@TALAO_ID*/
        ";
        
        $args = [
            '@ESTACAO'			=> $estacao,
            '@STATUS'			=> $status,
            '@TEMPO_REALIZADO'  => $tempo_realizado,
            '@ID'				=> $id,
            '@TALAO_ID'			=> $talao_id,
        ];

        $con->execute($sql, $args);
    }
    
    /**
     * Bloqueia a Estação (à coloca em uso)
     * @param type $param
     * @param _Conexao $con
     */
    public static function reprogramacaoProduzido($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $sql = "
            EXECUTE PROCEDURE SPU_PROGRAMACAO_PRODUZIDO1(
                :ESTABELECIMENTO_ID,
                'A',
                :TALAO_ID
            );
        ";

        $args = [
            ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            ':TALAO_ID'           => $param->TALAO_ID,
        ];

        $con->execute($sql, $args);
    }

    /**
     * Atualiza o status do talão detalhado
     * @param type $param
     * @param _Conexao $con
     */
    public static function remessaTalao($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE VWREMESSA_TALAO SET 
                STATUS = :STATUS
            WHERE
                REMESSA_ID       = :REMESSA_ID 
            AND REMESSA_TALAO_ID = :REMESSA_TALAO_ID
        ";
        
        $args = [
            ':STATUS'           => $param->REMESSA_TALAO_STATUS,
            ':REMESSA_ID'       => $param->REMESSA_ID,
            ':REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID,
        ];

        $con->execute($sql, $args);
    }

    /**
     * Atualiza o status do talão detalhado
     * @param type $param
     * @param _Conexao $con
     */
    public static function remessaTalaoDetalhe($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql =
        "
            UPDATE VWREMESSA_TALAO_DETALHE SET 
                STATUS = :STATUS
            WHERE
                REMESSA_ID       = :REMESSA_ID
            AND REMESSA_TALAO_ID = :REMESSA_TALAO_ID
        ";
        
        $args = [
            ':STATUS'           => $param->REMESSA_TALAO_DETALHE_STATUS,
            ':REMESSA_ID'       => $param->REMESSA_ID,
            ':REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID,
        ];
        
        $con->execute($sql, $args);
    }
	
    /**
     * Altera a quantidade alocada da matéria-prima.
     * @param type $param
     * @param _Conexao $con
     */
    public static function alterarQtdAlocada($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
		
		$quantidade = ($param->RETORNO === 'QUANTIDADE_ALOCADA') 
						? "V.QUANTIDADE = $param->QUANTIDADE" 
						: "V.QUANTIDADE_ALTERNATIVA = $param->QUANTIDADE";
		
        $sql =
        "
            UPDATE 
				TBREMESSA_TALAO_VINCULO V
				
			SET
				/*@QUANTIDADE*/

			WHERE
				V.CONSUMO_ID = :CONS
        ";
        
        $args = [
            '@QUANTIDADE'	=> $quantidade,
            ':CONS'			=> $param->CONSUMO_ID
        ];
		
        $con->execute($sql, $args);
    }
	
	/**
     * Altera a quantidade de produção ou a quantidade alternativa de produção do detalhe do talão.
     * @param array $param
     * @param _Conexao $con
     */
    public static function alterarQtdTalaoDetalhe($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
		
        $sobra = 0;
        
		if($param->RETORNO === 'QUANTIDADE') {
		
			//somar quantidades
			if ( array_key_exists('SOMAR_QUANTIDADE', $param) ) {
				$quantidade = "D.QUANTIDADE_PRODUCAO_TMP	= COALESCE(D.QUANTIDADE_PRODUCAO_TMP, 0) + COALESCE($param->QUANTIDADE, 0),
								D.QUANTIDADE_SOBRA_TMP		= COALESCE(D.QUANTIDADE_SOBRA_TMP, 0) + COALESCE($param->SOBRA, 0)";
			}
			else {
				$quantidade = "D.QUANTIDADE_PRODUCAO_TMP	= $param->QUANTIDADE,
								D.QUANTIDADE_SOBRA_TMP		= $param->SOBRA";
			}
			
		}
		elseif($param->RETORNO === 'QUANTIDADE_ALTERNATIVA') {
			
			//somar quantidades
			if ( array_key_exists('SOMAR_QUANTIDADE', $param) ) {
				$quantidade = "D.QUANTIDADE_ALTERN_PRODUCAO_TMP = COALESCE(D.QUANTIDADE_ALTERN_PRODUCAO_TMP, 0) + COALESCE($param->QUANTIDADE, 0),
							   D.QUANTIDADE_SOBRA_TMP			= COALESCE(D.QUANTIDADE_SOBRA_TMP, 0) + 0";
			}
			else {
				$quantidade = "D.QUANTIDADE_ALTERN_PRODUCAO_TMP = $param->QUANTIDADE,
							   D.QUANTIDADE_SOBRA_TMP			= 0";
			}
			
		}
		elseif ($param->RETORNO === 'AMBAS') {
			
			//somar quantidades
			if ( array_key_exists('SOMAR_QUANTIDADE', $param) ) {
				$quantidade = "D.QUANTIDADE_PRODUCAO_TMP		= COALESCE(D.QUANTIDADE_PRODUCAO_TMP, 0) + COALESCE($param->QUANTIDADE, 0),
							   D.QUANTIDADE_ALTERN_PRODUCAO_TMP = COALESCE(D.QUANTIDADE_ALTERN_PRODUCAO_TMP, 0) + COALESCE($param->QUANTIDADE_ALTERNATIVA, 0),
							   D.QUANTIDADE_SOBRA_TMP			= COALESCE(D.QUANTIDADE_SOBRA_TMP, 0) + 0";
			}
			else {
				$quantidade = "D.QUANTIDADE_PRODUCAO_TMP		= $param->QUANTIDADE,
							   D.QUANTIDADE_ALTERN_PRODUCAO_TMP = $param->QUANTIDADE_ALTERNATIVA,
							   D.QUANTIDADE_SOBRA_TMP			= 0";
			}
		}
		
        $sql =
        "
            UPDATE
				VWREMESSA_TALAO_DETALHE D
				
			SET
				/*@QUANTIDADE*/

			WHERE
				D.ID = :ID
        ";
            
        $args = [
            '@QUANTIDADE'	=> $quantidade,
            ':ID'			=> $param->TALAO_DETALHE_ID
        ];
        
        $con->execute($sql, $args);
		
        $sql = "select * from SPC_CONSUMO_ALOCADO_V2(:REMESSA,:TALAO)";
        
        $args = [
            ':REMESSA'			=> $param->REMESSA_ID,
            ':TALAO'			=> $param->REMESSA_TALAO_ID
        ];
		
		$ret = $con->query($sql, $args);
        
        if(count($ret) > 0){
            return $ret;
        }else{
           //log_erro('Não há itens alocados no consumo');
            return $ret;
        }
         
    }

	/**
     * Altera todas as quantidades de produção ou as quantidades alternativas de produção do detalhe do talão.
     * @param array $param
     * @param _Conexao $con
     */
    public static function alterarTodasQtdTalaoDetalhe($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
        
        $sql =
        "
            UPDATE
                VWREMESSA_TALAO_DETALHE D

            SET
                D.QUANTIDADE_PRODUCAO_TMP        = IIF(CAST(:TIPO_1 AS CHAR(1)) = '1',D.QUANTIDADE       ,0),
                D.QUANTIDADE_ALTERN_PRODUCAO_TMP = IIF(CAST(:TIPO_2 AS CHAR(1)) = '1',D.QUANTIDADE_ALTERN,0)

            WHERE
                D.REMESSA_ID = :REMESSA_ID
            AND D.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
        ";
        
        $args = [
            ':REMESSA_ID'		=> $param->REMESSA_ID,
            ':REMESSA_TALAO_ID'	=> $param->REMESSA_TALAO_ID,
            ':TIPO_1'           => $param->TIPO,
            ':TIPO_2'           => $param->TIPO
        ];
        
        $con->execute($sql, $args);
		
        if ( $param->TIPO == '1' ) {

            $sql = "select * from SPC_CONSUMO_ALOCADO_V2(:REMESSA,:TALAO)";

            $args = [
                ':REMESSA'			=> $param->REMESSA_ID,
                ':TALAO'			=> $param->REMESSA_TALAO_ID
            ];
        
            $ret = $con->query($sql, $args);

        } else {
            $ret = [];
        }
        return $ret;
        
    }
}

class _22020DaoDelete
{
    public static function projecaoVinculoExcluir($param = [], _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;
		
        $sql =
        "
            DELETE FROM TBREMESSA_TALAO_VINCULO V WHERE V.ID = :ID
        ";
        
        $args = [
            ':ID' => $param->ID
        ];
		
        $con->execute($sql, $args);
    }
}
