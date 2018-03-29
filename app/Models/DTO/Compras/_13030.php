<?php

namespace App\Models\DTO\Compras;

use App\Models\DAO\Compras\_13030DAO;

class _13030
{
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }  
    
    public function selectCotas($param) {
        
        $sql = "
            SELECT * FROM SPC_COTA_ORCAMENTARIA(:DATA_1,:DATA_2,:COTA_GGF,:COTA_VALIDA,:COTA_ZERADA,:FATURAMENTO,:USUARIO_ID,:COTA_AJUSTE_INVENTARIO)
        ";
        
        $args = [
            'DATA_1'      => setDefValue($param->DATA_1  , null),
            'DATA_2'      => setDefValue($param->DATA_2  , null),
            'COTA_GGF'    => setDefValue($param->COTA_GGF, null),
            'COTA_VALIDA' => setDefValue($param->COTA_VALIDA, null),
            'COTA_ZERADA' => setDefValue($param->COTA_ZERADA, null),
            'FATURAMENTO' => setDefValue($param->FATURAMENTO, null),
            'USUARIO_ID'  => \Auth::user()->CODIGO,
            'COTA_AJUSTE_INVENTARIO' => setDefValue($param->COTA_AJUSTE_INVENTARIO, null),
        ];
        
        
        return $this->con->query($sql,$args);
    }    
        
    public function selectCota($param) {
        
        $ret = (object)[];
        
        if ( $param->TIPO == 'GGF' ) {
            $ret->COTA_GGF = $this->selectCotaGGf($param);
        } else        
        if ( $param->TIPO == 'INV' ) {
            $ret->COTA_AJUSTE_INVENTARIO = $this->selectCotaAjusteInventario($param);
        } else {
            $ret->LANCAMENTOS = $this->selectCotaLancamentos($param);
            $ret->EXTRAS      = $this->selectCotaExtra($param);
            $ret->REDUCOES    = $this->selectCotaReducoes($param);
        }
        

        return $ret;
    }    
    
    public function selectCotaLancamentos($param) {
        
        $sql = "
            SELECT
                X.*,
                VALOR + DESCONTO_IMPOSTO VALOR_SUBTOTAL 
            FROM
                (SELECT
                    A.ID,
                    A.CCUSTO,
                    A.CONTACONTABIL CCONTABIL,
    
                    TRIM((IIF(A.TABELA = 'NFE',
    
                        (SELECT FIRST 1
                        ('NFE.'|| LPAD(E.NUMERO_NOTAFISCAL,9,'0')||' - '||
                        (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = E.EMPRESA_CODIGO) || ' - ' ||
                        SUBSTRING(E.PRODUTO_DESCRICAO FROM 1 FOR 25) || ' - ' ||
                        'QTD. ' || REPLACE(CAST(E.QUANTIDADE AS NUMERIC(15,4)), '.', ',')
                        )STRING
                        FROM
                        TBNFE_ITEM E
                        WHERE
                        E.CONTROLE = A.TABELA_ID),
    
                    IIF(A.TABELA = 'NFS',
    
                        (SELECT FIRST 1
                        ('NFS.'|| LPAD(E.NUMERO_NOTAFISCAL,9,'0')||' - '||
                        (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = E.EMPRESA_CODIGO) || ' - ' ||
                        SUBSTRING(E.PRODUTO_DESCRICAO FROM 1 FOR 25) || ' - ' ||
                        'QTD. ' || REPLACE(CAST(E.QUANTIDADE AS NUMERIC(15,4)), '.', ',')
                        )STRING
                        FROM
                        TBNFS_ITEM E
                        WHERE
                        E.CONTROLE = A.TABELA_ID),
    
                    IIF(A.TABELA = 'OC',
    
                        (SELECT FIRST 1
                        ('OC.'|| LPAD(E.OC,6,'0')||' - '||
                        (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = E.FORNECEDOR_CODIGO) || ' - ' ||
                        SUBSTRING((SELECT FIRST 1 DESCRICAO FROM TBPRODUTO WHERE CODIGO = E.PRODUTO_CODIGO) FROM 1 FOR 25) || ' - ' ||
                        'QTD. ' || REPLACE(CAST(E.QUANTIDADE AS NUMERIC(15,4)), '.', ',')
                        )STRING
                        FROM
                        TBOC_ITEM E
                        WHERE
                        E.CONTROLE = A.TABELA_ID),
    
                    ''))) || ' ' || IIF(CHAR_LENGTH(A.DESCRICAO) > 0,A.DESCRICAO, ''))) DESCRICAO,
    
                    A.VALOR,
                    (A.ICMS+A.PISCOFINS)*-1 DESCONTO_IMPOSTO,
                    TRIM(A.NATUREZA) NATUREZA,
                    A.DATA,
                    EXTRACT(MONTH FROM A.DATA)MES,
                    EXTRACT(YEAR FROM A.DATA)ANO,
                    0 as FILTRO
    
                FROM
                    TBCCUSTO_COTA_DETALHE A,
                    TBCCUSTO_COTA B
    
                WHERE
                    A.CCUSTO = B.CCUSTO
                AND A.CONTACONTABIL = B.CONTACONTABIL
                AND A.DATA BETWEEN CAST(B.ANO||'.'||B.MES||'.01' As Date)
                AND (DATEADD(1 MONTH TO CAST(B.ANO||'.'||B.MES||'.01' AS DATE))-1)
                AND B.STATUSEXCLUSAO = '0'
                AND A.STATUSEXCLUSAO = '0'
                AND A.CCUSTO = :CCUSTO
                AND A.CONTACONTABIL = :CCONTABIL
                AND A.DATA BETWEEN :DATA_1 AND :DATA_2
    
                ORDER BY
                    DESCRICAO)X
        ";
        
        $args = [
            'CCUSTO'    => setDefValue($param->CCUSTO   , null),
            'CCONTABIL' => setDefValue($param->CCONTABIL, null),
            'DATA_1'    => setDefValue($param->DATA_1   , null),
            'DATA_2'    => setDefValue($param->DATA_2   , null)
        ];
                
        return $this->con->query($sql,$args);
    }    
    
    
    public function selectCotaExtra($param) {
        
        $sql = "
            SELECT
                        A.ID,
                        B.CODIGO    USUARIO,
                        B.NOME		USUARIO_NOME,
                        A.VALOR,
                        A.DATAHORA_INSERT DATAHORA,
                        A.OBSERVACAO

            FROM
                        TBCCUSTO_COTA_EXTRA A,
                        TBUSUARIO B

            WHERE
                        B.CODIGO = A.USUARIO_ID
            AND A.CCUSTO_COTA_ID = :COTA_ID
            AND A.STATUSEXCLUSAO = '0'

                    ORDER BY
                        A.DATAHORA_INSERT
        ";
        
        $args = [
            'COTA_ID'   => setDefValue($param->ID, null)
        ];
                
        return $this->con->query($sql,$args);
    }    
    
    public function selectCotaReducoes($param) {
        
        $sql = "
            SELECT
                A.ID,
                B.CODIGO    USUARIO,
                B.NOME      USUARIO_NOME,
                A.VALOR,
                A.DATAHORA_INSERT DATAHORA,
                A.OBSERVACAO
    
            FROM
                TBCCUSTO_COTA_OUTROS A,
                TBUSUARIO B
    
            WHERE
                B.CODIGO = A.USUARIO_ID
            AND A.CCUSTO_COTA_ID = :COTA_ID
            AND A.STATUSEXCLUSAO = '0'
    
            ORDER BY
                A.DATAHORA_INSERT
        ";
        
        $args = [
            'COTA_ID'   => setDefValue($param->ID, null)
        ];
                
        return $this->con->query($sql,$args);
    }    
    
    public function selectCotaGGf($param) {
        
        $sql = "
            SELECT G.*
              FROM SPC_COTA_GGF(
                :CCUSTO,
                :DATA_1,
                :DATA_2) G
        ";
        
        $args = [
            'CCUSTO'   => setDefValue($param->CCUSTO  , null),
            'DATA_1'   => setDefValue($param->DATA_1  , null),
            'DATA_2'   => setDefValue($param->DATA_2  , null)
        ];
                
        return $this->con->query($sql,$args);
    }      
    
    public function selectCotaAjusteInventario($param) {
        
        $sql = "
            SELECT
                X.ANO,
                X.MES,
                X.CC_DETALHE CCUSTO,
                FN_CCUSTO_MASK(X.CC_DETALHE) CCUSTO_MASK,
                FN_CCUSTO_DESCRICAO(X.CC_DETALHE) CCUSTO_DESCRICAO,

                X.FAMILIA_ID || ' - ' || 
                    (SELECT FIRST 1 F.DESCRICAO
                       FROM TBFAMILIA F
                      WHERE F.CODIGO = X.FAMILIA_ID) DESCRICAO,

                X.FAMILIA_ID,
                (SELECT FIRST 1 F.DESCRICAO
                   FROM TBFAMILIA F
                  WHERE F.CODIGO = X.FAMILIA_ID) FAMILIA_DESCRICAO,
                CAST(SUM(CUSTO) AS NUMERIC(15,2)) VALOR,
                CAST(SUM(CUSTO) AS NUMERIC(15,2)) VALOR_UTILIZADO
            FROM (
            SELECT CC.CCUSTO_CONTABILIZACAO CC,
                   A.CENTRO_DE_CUSTO CC_DETALHE,
                   CC.DESCRICAO CC_DESCRICAO,
                   P.FAMILIA_CODIGO FAMILIA_ID,
                   EXTRACT (YEAR FROM A.DATA) ANO,
                   EXTRACT (MONTH FROM A.DATA) MES,
                   A.QUANTIDADE *
                  (SELECT FIRST 1 S.CUSTO_MEDIO
                     FROM TBESTOQUE_SALDO_DIARIO S
                    WHERE S.ESTABELECIMENTO_CODIGO = A.ESTABELECIMENTO_CODIGO
                      AND S.LOCALIZACAO_CODIGO = A.LOCALIZACAO_CODIGO
                      AND S.PRODUTO_CODIGO = A.PRODUTO_CODIGO
                      AND S.DATA = A.DATA) * (IIF(A.TIPO='E',-1.000,1.000)) CUSTO
              FROM TBESTOQUE_TRANSACAO_ITEM A, TBPRODUTO P, TBOPERACAO O, TBCENTRO_DE_CUSTO CC
             WHERE
                   A.PRODUTO_CODIGO = P.CODIGO
               AND A.OPERACAO_CODIGO = O.CODIGO
               AND O.ACERTO = '1'
               AND P.INVENTARIO = '1'
               AND A.CENTRO_DE_CUSTO = CC.CODIGO 
               AND CC.CCUSTO_CONTABILIZACAO = :CCUSTO
               AND A.DATA BETWEEN :DATA_1 AND :DATA_2
            ) X
            GROUP BY X.ANO, X.MES, X.CC, X.CC_DETALHE, X.CC_DESCRICAO, X.FAMILIA_ID
        ";
        
        $args = [
            'CCUSTO'   => setDefValue($param->CCUSTO  , null),
            'DATA_1'   => setDefValue($param->DATA_1  , null),
            'DATA_2'   => setDefValue($param->DATA_2  , null)
        ];
                
        return $this->con->query($sql,$args);
    }      
    

    public function selectCotaAjusteInventarioDetalhe($param = null)
    {        
        $sql = "
            SELECT * FROM SPC_COTA_AJUSTE_DETALHE(
                :CCUSTO,
                :FAMILIA_ID,
                :DATA_1,
                :DATA_2
            )
        ";
        
        $args = [
            ':CCUSTO'       => $param->CCUSTO,
            ':FAMILIA_ID'   => $param->FAMILIA_ID,
            ':DATA_1'       => $param->DATA_1,
            ':DATA_2'       => $param->DATA_2,
        ];
        
        return $this->con->query($sql,$args);
    }    
    
    public function selectCotaGGfDetalhe($param) {
        
        $sql = "
            SELECT * FROM SPC_COTA_GGF_DETALHE(
                :CCUSTO,
                :FAMILIA_ID,
                :DATA_1,
                :DATA_2
            )
        ";
        
        $args = [
            'CCUSTO'       => $param->CCUSTO,
            'FAMILIA_ID'   => $param->FAMILIA_ID,
            'DATA_1'       => $param->DATA_1,
            'DATA_2'       => $param->DATA_2,
        ];        
        
        return $this->con->query($sql,$args);
    }      
    
    public function insertCota($param) {
        
		$sql = "
            EXECUTE PROCEDURE SPI_COTA_ORCAMENTARIA(
                :CCUSTO,
                :CCONTABIL,
                :MES_1,
                :ANO_1,
                :MES_2,
                :ANO_2,
                :BLOQUEIA,
                :NOTIFICA,
                :DESTACA,
                :TOTALIZA,
                :VALOR
            );
    	";

		$args = [
			'CCUSTO'	=> $param->CCUSTO,
			'CCONTABIL'	=> $param->CCONTABIL,
			'MES_1'     => $param->MES_1,
			'ANO_1'     => $param->ANO_1,
			'MES_2'     => $param->MES_2,
			'ANO_2'     => $param->ANO_2,
			'BLOQUEIA'	=> $param->BLOQUEIA,
			'NOTIFICA'	=> $param->NOTIFICA,
			'DESTACA'	=> $param->DESTACA,
			'TOTALIZA'	=> $param->TOTALIZA,
			'VALOR'     => $param->VALOR,
		];
        
        return $this->con->query($sql,$args);
    }         
    
    public function insertCotaExtra($param) {
        
		$sql =
		"
            INSERT INTO TBCCUSTO_COTA_EXTRA (
                CCUSTO_COTA_ID,
                USUARIO_ID,
                VALOR,
                OBSERVACAO
            ) VALUES (
                :CCUSTO_COTA_ID,
                :USUARIO_ID,
                :VALOR,
                :OBSERVACAO
            )
		";
	
		$args = [
			'CCUSTO_COTA_ID'   => $param->ID,
			'USUARIO_ID'       => \Illuminate\Support\Facades\Auth::user()->CODIGO,
			'VALOR'            => $param->VALOR,
			'OBSERVACAO'       => $param->OBSERVACAO
		];
        
        return $this->con->query($sql,$args);
    }         
    
    public function insertCotaReducao($param) {
        
		$sql =
		"
            INSERT INTO TBCCUSTO_COTA_OUTROS (
                CCUSTO_COTA_ID,
                USUARIO_ID,
                VALOR,
                OBSERVACAO
            ) VALUES (
                :CCUSTO_COTA_ID,
                :USUARIO_ID,
                :VALOR,
                :OBSERVACAO
            )
		";
	
		$args = [
			'CCUSTO_COTA_ID'   => $param->ID,
			'USUARIO_ID'       => \Illuminate\Support\Facades\Auth::user()->CODIGO,
			'VALOR'            => $param->VALOR,
			'OBSERVACAO'       => $param->OBSERVACAO
		];
        
        return $this->con->query($sql,$args);
    }         
     
    public function updateCota($param) {
        
	    $sql = /** @lang DAO */
	    "
	        UPDATE TBCCUSTO_COTA
	        SET VALOR 		     = :VALOR,
	    		BLOQUEIO 	     = :BLOQUEIO,
	    		NOTIFICACAO      = :NOTIFICACAO,
	    		DESTAQUE	     = :DESTAQUE,
	    		TOTALIZA	     = :TOTALIZA,
	    		OBSERVACAO_GERAL = :OBSERVACAO_GERAL
	        WHERE ID = :ID
	    ";

		$args = [
            'ID'               => $param->ID,
            'VALOR'            => $param->VALOR,
            'BLOQUEIO'         => $param->BLOQUEIA,
            'NOTIFICACAO'      => $param->NOTIFICA,
            'DESTAQUE'         => $param->DESTAQUE,
            'TOTALIZA'         => $param->TOTALIZA,
			'OBSERVACAO_GERAL' => setDefValue($param->OBSERVACAO_GERAL, null)
		];
        
        return $this->con->query($sql,$args);
    }     
    
    public function deleteCota($param) {
        
		$sql = "
        	UPDATE TBCCUSTO_COTA 
               SET STATUSEXCLUSAO = '1' 
             WHERE ID = :ID
    	";

		$args = [
			':ID'	=> $param->ID
		];
        
        return $this->con->query($sql,$args);
    }         
    
    public function deleteCotaExtra($param) {
        
		$sql = "
            UPDATE TBCCUSTO_COTA_EXTRA 
               SET STATUSEXCLUSAO = '1' 
             WHERE ID = :ID
    	";

		$args = [
			':ID'	=> $param->ID
		];
        
        return $this->con->query($sql,$args);
    }         
    
    public function deleteCotaReducao($param) {
        
		$sql = "
            UPDATE TBCCUSTO_COTA_OUTROS 
               SET STATUSEXCLUSAO = '1' 
             WHERE ID = :ID
    	";

		$args = [
			':ID'	=> $param->ID
		];
        
        return $this->con->query($sql,$args);
    }         
    
	private $id;
	private $ccusto;
	private $conta;
	private $mes;
	private $ano;
	private $valor;
    private $bloqueio;
    private $notificacao;
    private $destaque;
    private $totaliza;
    private $extra_add;
    private $extra_del;
    private $outro_add;
    private $outro_del;

    public function getId() {
        return $this->id;
	}
	public function setId($id) {
        $this->id[] = $id;
	}

	public function getCcusto() {
        return $this->ccusto;
	}
	public function setCcusto($ccusto) {
        $this->ccusto[] = $ccusto;
	}

	public function getConta() {
        return $this->conta;
	}
	public function setConta($conta) {
        $this->conta[] = $conta;
	}		

	public function  getMes() {
        return $this->mes;
	}

	public function  setMes($mes) {
        $this->mes[] = $mes;
	}

	public function  getAno() {
        return $this->ano;
	}

	public function  setAno($ano) {
        $this->ano[] = $ano;
	}

	public function getValor() {
        return $this->valor;
	}
        
	public function setValor($valor) {
        $this->valor[] = $valor;
	}

	public function getObservacaoGeral() {
        return $this->observacao_geral;
	}
        
	public function setObservacaoGeral($observacao_geral) {
        $this->observacao_geral[] = $observacao_geral;
	}

	public function getBloqueio() {
		return $this->bloqueio;
	}
	
	public function setBloqueio($bloqueio) {
		$this->bloqueio[] = $bloqueio;
	} 

	public function getNotificacao() {
		return $this->notificacao;
	}
	
	public function setNotificacao($notificacao) {
		$this->notificacao[] = $notificacao;
	}
	
	public function getDestaque() {
		return $this->destaque;
	}
	
	public function setDestaque($destaque) {
		$this->destaque[] = $destaque;
	}
	
	public function getTotaliza() {
		return $this->totaliza;
	}
	
	public function setTotaliza($totaliza) {
		$this->totaliza[] = $totaliza;
	}
	
    public function getCotaExtraAdd() {
        return $this->extra_add;
	}
        
	public function setCotaExtraAdd($extra_add) {
        $this->extra_add = $extra_add;
	}	
	
    public function getCotaExtraDel() {
        return $this->extra_del;
	}
        
	public function setCotaExtraDel($extra_del) {
        $this->extra_del = $extra_del;
	}	

	public function getCotaOutroAdd() {
		return $this->outro_add;
	}
	
	public function setCotaOutroAdd($outro_add) {
		$this->outro_add = $outro_add;
	}	
	
    public function getCotaOutroDel() {
        return $this->outro_del;
	}
        
	public function setCotaOutroDel($outro_del) {
        $this->outro_del = $outro_del;
	}

	/**
	 * Gerar id do objeto.
	 *
	 * @return integer
	 */
	public static function gerarId() {
		return _13030DAO::gerarId();
	}

	/**
	 * Inserir dados do objeto na base de dados.
	 *
	 * @param _1000 $obj
	 */
	public static function gravar(_13030 $obj) {
		return _13030DAO::gravar($obj);
	}

	/**
	 * Consulta inicial
	 *
	 * @return array
	 */
	public static function listar($param) {
		return _13030DAO::listar($param);
	}

	/**
	 *
	 * Atualiza dados do objeto na base de dados.
	 * @param _13030 $obj
	 */
	public static function alterar(_13030 $obj) {
		return _13030DAO::alterar($obj);
	}

	/**
	 * Exclui dados do objeto na base de dados.
	 *
	 */
	public static function excluir($id) {
		return _13030DAO::excluir($id);
	}

    /**
	 * Exclui dados do objeto na base de dados.
	 *
	 */
	public static function excluirContaAjx($id) {
		return _13030DAO::excluirContaAjx($id);
	}

	/**
	 * Retorna dados do objeto na base de dados.
	 *
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id) {
		return _13030DAO::exibir($id);
	}

	/**
	 * Consulta Cotas
	 *
	 * @return array
	 */
	public static function consultaCota($id = null, $ccusto = null, $ccontabil = null, $periodoInicial, $periodoFinal = null) {
		return _13030DAO::consultaCota($id,$ccusto,$ccontabil,$periodoInicial,$periodoFinal);
	}
    
    public static function faturamentoQuery($param = []) {
        return _13030DAO::faturamentoQuery($param);
    }
    public static function faturamentoStore($param = []) {
        return _13030DAO::faturamentoStore($param);
    }
    
    public static function faturamentoUpdate($param = []) {
        return _13030DAO::faturamentoUpdate($param);
    }
    
    public static function faturamentoDestroy($param = []) {
        return _13030DAO::faturamentoDestroy($param);
    }
    
    public static function faturamentoGerarId() {
        return _13030DAO::faturamentoGerarId();
    }
    
    public static function replicarStore($param = []) {
        return _13030DAO::replicarStore($param);
    }    
    
    public static function selectGgfDetalhe($param = [], $con = null) {
        return _13030DAO::selectGgfDetalhe(obj_case($param),$con);
    }
    
    public static function selectAjusteInventarioDetalhe($param = [], $con = null) {
        return _13030DAO::selectAjusteInventarioDetalhe(obj_case($param),$con);
    }
    
    public static function selectGgf($param = [], $con = null) {
        return _13030DAO::selectGgf(obj_case($param),$con);
    }
    
    public static function selectAjusteInventario($param = [], $con = null) {
        return _13030DAO::selectAjusteInventario(obj_case($param),$con);
    }
}