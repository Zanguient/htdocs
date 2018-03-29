<?php

namespace App\Models\DTO\Vendas;

use App\Models\DAO\Vendas\_12090DAO;

class _12090
{
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }  
    
    public function selectEmpresas($param) {
        
        $sql = "
            SELECT FIRST :FIRST SKIP :SKIP
                *
            FROM
                SPC_EMPRESA(
                    :STATUS,
                    :FILTRO,
                    :HABILITA_CLIENTE,
                    :HABILITA_FORNECEDOR,
                    :HABILITA_REPRESENTANTE,
                    :HABILITA_TRANSPORTADORA
                ) 
            WHERE TRUE
            /*@EMPRESA_ID*/
            /*@REPRESENTANTE_ID*/
        ";
        
        if( isset($param->EMPRESA_ID)  ) {
            $param->EMPRESA_ID = "AND EMPRESA_ID = $param->EMPRESA_ID";
        }
        
        if( isset($param->REPRESENTANTE_ID)  ) {
            $param->REPRESENTANTE_ID = "AND EMPRESA_REPRESENTANTE_ID = $param->REPRESENTANTE_ID";
        }
         
        $args = [
            'FIRST'                     => setDefValue($param->FIRST                    , 50),
            'SKIP'                      => setDefValue($param->SKIP                     , 0),
            'STATUS'                    => setDefValue($param->STATUS                   , null),
            'FILTRO'                    => setDefValue($param->FILTRO                   , null),
            'HABILITA_CLIENTE'          => setDefValue($param->HABILITA_CLIENTE         , null),
            'HABILITA_FORNECEDOR'       => setDefValue($param->HABILITA_FORNECEDOR      , null),
            'HABILITA_REPRESENTANTE'    => setDefValue($param->HABILITA_REPRESENTANTE   , null),
            'HABILITA_TRANSPORTADORA'   => setDefValue($param->HABILITA_TRANSPORTADORA  , null),
            '@EMPRESA_ID'               => setDefValue($param->EMPRESA_ID               , null),
            '@REPRESENTANTE_ID'         => setDefValue($param->REPRESENTANTE_ID         , null),
        ];
        
        return $this->con->query($sql,$args);
    }    
        
    
    public function selectEmpresa($param) {
        
        $sql = "
            SELECT
                FN_LPAD(E.CODIGO,6,0)           EMPRESA_ID,
                E.NOMEFANTASIA                  EMPRESA_NOMEFANTASIA,
                E.RAZAOSOCIAL                   EMPRESA_RAZAO_SOCIAL,
                E.UF                            EMPRESA_UF,     
                E.CIDADE                        EMPRESA_CIDADE,
                E.CNPJ                          EMPRESA_CNPJ,
                FN_MASK(E.CNPJ)                 EMPRESA_CNPJ_MASK,
                E.IE                            EMPRESA_IE,
                E.ENDERECO                      EMPRESA_ENDERECO,
                E.COMPLEMENTO                   EMPRESA_ENDERECO_COMPLEMENTO,
                E.NUMERO                        EMPRESA_ENDERECO_NUMERO,
                E.BAIRRO                        EMPRESA_ENDERECO_BAIRRO,
                E.CEP                           EMPRESA_ENDERECO_CEP,
                FN_MASK(E.CEP)                  EMPRESA_ENDERECO_CEP_MASK, 

                EC.UF                           COBRANCA_UF,
                EC.CIDADE                       COBRANCA_CIDADE,
                EC.ENDERECO                     COBRANCA_ENDERECO,
                EC.COMPLEMENTO                  COBRANCA_COMPLEMENTO,
                EC.NUMERO                       COBRANCA_NUMERO,
                EC.BAIRRO                       COBRANCA_BAIRRO,
                EC.CEP                          COBRANCA_CEP,
                FN_MASK(EC.CEP)                 COBRANCA_CEP_MASK,
                                                                
                EC.ENTREGA_UF                   ENTREGA_UF,
                EC.ENTREGA_CIDADE               ENTREGA_CIDADE,
                EC.ENTREGA_ENDERECO             ENTREGA_ENDERECO,
                EC.ENTREGA_COMPLEMENTO          ENTREGA_COMPLEMENTO,
                EC.ENTREGA_NUMERO               ENTREGA_NUMERO,
                EC.ENTREGA_BAIRRO               ENTREGA_BAIRRO,
                EC.ENTREGA_CEP                  ENTREGA_CEP,
                FN_MASK(EC.ENTREGA_CEP)         ENTREGA_CEP_MASK,

                TRIM(C.BLOQUEIA_NOTAFISCAL)     BLOQUEIA_NOTAFISCAL,
                TRIM(CASE C.BLOQUEIA_NOTAFISCAL 
                WHEN '0' THEN 'Liberado'
                WHEN '1' THEN 'Bloqueado'
                ELSE '' END)                    BLOQUEIA_NOTAFISCAL_DESCRICAO,

                TRIM(C.BLOQUEIA_PEDIDO)         BLOQUEIA_PEDIDO,
                TRIM(CASE C.BLOQUEIA_PEDIDO
                WHEN '0' THEN 'Liberado'
                WHEN '1' THEN 'Bloqueado'
                ELSE '' END)                     BLOQUEIA_PEDIDO_DESCRICAO,

                TRIM(C.CONFIRMACAO_AUTOMATICA)  CONFIRMACAO_AUTOMATICA,
                TRIM(C.ATENDE_GRADE_COMPLETA)   ATENDE_GRADE_COMPLETA,
                C.LIMITE_CREDITO                LIMITE_CREDITO,
                COALESCE(
                  C.SUBSTITUIDO_TRIBUTARIO,'0') SUBSTITUIDO_TRIBUTARIO,
                FN_CAST_BLOB_TO_TEXT(C.OBSERVACAO) OBSERVACAO_NF,

                C.REPRESENTANTE_CODIGO          REPRESENTANTE_ID,
                C.REPRESENTANTE_COMISSAO        REPRESENTANTE_COMISSAO,
                R.RAZAOSOCIAL                   REPRESENTANTE_RAZAOSOCIAL,
                
                C.VENDEDOR_CODIGO               VENDEDOR_ID,
                RV.NOME                         VENDEDOR_NOME,
                C.VENDEDOR_COMISSAO             VENDEDOR_COMISSAO,

                C.SUFRAMA                       EMPRESA_SUFRAMA,
                E.EMAIL                         EMPRESA_EMAIL,
                E.EMAIL_XML                     EMPRESA_EMAIL_XML,
                E.EMAIL_CPA                     EMPRESA_EMAIL_CPA,
                E.SITE                          EMPRESA_SITE,
                E.FONE                          EMPRESA_FONE,
                FN_MASK(E.FONE,'FONE')          EMPRESA_FONE_MASK,
                E.FAX                           EMPRESA_FAX,
                FN_MASK(E.FAX,'FONE')           EMPRESA_FAX_MASK,
                FN_MASK(E.FONE,'FONE')          EMPRESA_FONE_MASK,
                E.CELULAR                       EMPRESA_CELULAR,
                FN_MASK(E.CELULAR,'FONE')       EMPRESA_CELULAR_MASK,
                E.CONTATO                       EMPRESA_CONTATO,
                FN_CAST_BLOB_TO_TEXT(CO.OBSERVACAO)EMPRESA_OBSERVACAO,

                C.TRANSPORTADORA_CODIGO         C_TRANSPORTADORA_ID,
                CT.RAZAOSOCIAL                  C_TRANSPORTADORA_RAZAOSOCIAL,

                C.PAGAMENTO_CONDICAO            C_PAGAMENTO_CONDICAO,
                PC1.DESCRICAO                   C_PAGAMENTO_CONDICAO_DESCRICAO,
                C.PAGAMENTO_FORMA               C_PAGAMENTO_FORMA,
                PF1.DESCRICAO                   C_PAGAMENTO_FORMA_DESCRICAO,

                F.TRANSPORTADORA_CODIGO         F_TRANSPORTADORA_ID,
                FT.RAZAOSOCIAL                  F_TRANSPORTADORA_RAZAOSOCIAL,

                F.PAGAMENTO_CONDICAO            F_PAGAMENTO_CONDICAO,
                PC2.DESCRICAO                   F_PAGAMENTO_CONDICAO_DESCRICAO,
                F.PAGAMENTO_FORMA               F_PAGAMENTO_FORMA,
                PF2.DESCRICAO                   F_PAGAMENTO_FORMA_DESCRICAO,

                C.PRIORIDADE                    EMPRESA_PRIORIDADE,
                TRIM(E.HABILITA_CLIENTE)        EMPRESA_HABILITA_CLIENTE,
                TRIM(E.HABILITA_FORNECEDOR)     EMPRESA_HABILITA_FORNECEDOR,
                TRIM(E.HABILITA_REPRESENTANTE)  EMPRESA_HABILITA_REPRESENTANTE,
                TRIM(E.HABILITA_TRANSPORTADORA) EMPRESA_HABILITA_TRANSPORTADORA,
                R.SUPERVISOR_ID                 EMPRESA_SUPERVISOR_ID,
               (SELECT FIRST 1 S.DESCRICAO
                  FROM TBSUPERVISOR S
                 WHERE R.SUPERVISOR_ID = S.ID)  EMPRESA_SUPERVISOR_DESCRICAO,
                C.EMPRESA_VINCULO               CONTA_PRINCIPAL_ID,
               (SELECT FIRST 1 E.NOMEFANTASIA
                  FROM TBEMPRESA E
                 WHERE E.CODIGO = C.EMPRESA_VINCULO) CONTA_PRINCIPAL_NOMEFANTASIA,                 
               TRIM((CASE
                WHEN C.TAG = '1' THEN 'TAG'
                ELSE '' END))                   EMPRESA_TAG,
                TRIM(C.TAG)                     TAG,

                TRIM(C.FRETE)                   FRETE,
                TRIM(CASE C.FRETE
                WHEN 1 THEN 'CIF'
                WHEN 2 THEN 'FOB'
                ELSE '' END)                    FRETE_DESCRICAO,
                REPLACE(E.LATITUDE,',','.')     EMPRESA_LATITUDE,
                REPLACE(E.LONGITUDE,',','.')    EMPRESA_LONGITUDE,
                TRIM(E.STATUS)                  EMPRESA_STATUS,
                CASE E.STATUS
                WHEN '0' THEN 'Inativo'
                WHEN '1' THEN 'Ativo'
                ELSE '' END EMPRESA_STATUS_DESCRICAO
    
            FROM TBEMPRESA E
            LEFT JOIN TBCLIENTE C ON E.CODIGO = C.CODIGO
            LEFT JOIN TBREPRESENTANTE R ON (CASE WHEN E.HABILITA_REPRESENTANTE='1' THEN E.CODIGO ELSE C.REPRESENTANTE_CODIGO END) = R.CODIGO
            LEFT JOIN TBFORNECEDOR F ON E.CODIGO = F.CODIGO
            LEFT JOIN TBEMPRESA_COBRANCA EC ON EC.CODIGO = E.CODIGO
            LEFT JOIN TBPAGAMENTO_CONDICAO PC1 ON PC1.CODIGO = C.PAGAMENTO_CONDICAO
            LEFT JOIN TBPAGAMENTO_FORMA    PF1 ON PF1.CODIGO = C.PAGAMENTO_FORMA
            LEFT JOIN TBPAGAMENTO_CONDICAO PC2 ON PC2.CODIGO = F.PAGAMENTO_CONDICAO
            LEFT JOIN TBPAGAMENTO_FORMA    PF2 ON PF2.CODIGO = F.PAGAMENTO_FORMA
            LEFT JOIN TBREPRESENTANTE_VENDEDOR RV ON RV.CODIGO = C.VENDEDOR_CODIGO
            LEFT JOIN TBTRANSPORTADORA CT ON CT.CODIGO = C.TRANSPORTADORA_CODIGO
            LEFT JOIN TBTRANSPORTADORA FT ON FT.CODIGO = F.TRANSPORTADORA_CODIGO
            LEFT JOIN TBCLIENTE_OBSERVACAO CO ON CO.CLIENTE_CODIGO = C.CODIGO
            WHERE E.CODIGO = :EMPRESA_ID
        ";
        
        $args = [
            'EMPRESA_ID' => $param->EMPRESA_ID
        ];
        
        return $this->con->query($sql,$args);
    }    
        
    public function selectModeloPreco($param) {
        
        $sql = "
            SELECT
                *
            FROM (

                SELECT
                    TRIM('M') TIPO,
                    TRIM('PREÇO POR MODELO') TIPO_DESCRICAO,
                    A.MODELO_CODIGO ID,
                    T.MODELO_DESCRICAO DESCRICAO,
                    T.TAMANHO,
                    FN_TAMANHO_GRADE(T.GRADE_ID,T.TAMANHO)TAMANHO_DESCRICAO,
                    COALESCE(NULLIF(
                    IIF(T.TAMANHO = 01,A.PR_NF_01,
                    IIF(T.TAMANHO = 02,A.PR_NF_02,
                    IIF(T.TAMANHO = 03,A.PR_NF_03,
                    IIF(T.TAMANHO = 04,A.PR_NF_04,
                    IIF(T.TAMANHO = 05,A.PR_NF_05,
                    IIF(T.TAMANHO = 06,A.PR_NF_06,
                    IIF(T.TAMANHO = 07,A.PR_NF_07,
                    IIF(T.TAMANHO = 08,A.PR_NF_08,
                    IIF(T.TAMANHO = 09,A.PR_NF_09,
                    IIF(T.TAMANHO = 10,A.PR_NF_10,
                    IIF(T.TAMANHO = 11,A.PR_NF_11,
                    IIF(T.TAMANHO = 12,A.PR_NF_12,
                    IIF(T.TAMANHO = 13,A.PR_NF_13,
                    IIF(T.TAMANHO = 14,A.PR_NF_14,
                    IIF(T.TAMANHO = 15,A.PR_NF_15,
                    IIF(T.TAMANHO = 16,A.PR_NF_16,
                    IIF(T.TAMANHO = 17,A.PR_NF_17,
                    IIF(T.TAMANHO = 18,A.PR_NF_18,
                    IIF(T.TAMANHO = 19,A.PR_NF_19,
                    IIF(T.TAMANHO = 20,A.PR_NF_20,A.PRECO_VENDA)))))))))))))))))))),0),A.PRECO_VENDA) PRECO

                From TbCLIENTE_MODELO_PRECO A, SPC_MODELO_GRADE_TAMANHO(A.MODELO_CODIGO) T Where A.CLIENTE_CODIGO = :EMPRESA_ID_1
                UNION
                SELECT
                    TRIM('P') TIPO,
                    TRIM('PREÇO POR PRODUTO') TIPO_DESCRICAO,
                    B.PRODUTO_CODIGO PRODUTO_ID,
                    (SELECT FIRST 1 M.DESCRICAO FROM TBMODELO M WHERE P.MODELO_CODIGO = M.CODIGO)||' '||
                    (SELECT FIRST 1 C.DESCRICAO FROM TBCOR C WHERE P.COR_CODIGO = C.CODIGO) PRODUTO_DESCRICAO,
                    T.TAMANHO,
                    FN_TAMANHO_GRADE(T.GRADE_ID,T.TAMANHO)TAMANHO_DESCRICAO,
                    COALESCE(NULLIF(
                    IIF(T.TAMANHO = 01,B.PR_NF_01,
                    IIF(T.TAMANHO = 02,B.PR_NF_02,
                    IIF(T.TAMANHO = 03,B.PR_NF_03,
                    IIF(T.TAMANHO = 04,B.PR_NF_04,
                    IIF(T.TAMANHO = 05,B.PR_NF_05,
                    IIF(T.TAMANHO = 06,B.PR_NF_06,
                    IIF(T.TAMANHO = 07,B.PR_NF_07,
                    IIF(T.TAMANHO = 08,B.PR_NF_08,
                    IIF(T.TAMANHO = 09,B.PR_NF_09,
                    IIF(T.TAMANHO = 10,B.PR_NF_10,
                    IIF(T.TAMANHO = 11,B.PR_NF_11,
                    IIF(T.TAMANHO = 12,B.PR_NF_12,
                    IIF(T.TAMANHO = 13,B.PR_NF_13,
                    IIF(T.TAMANHO = 14,B.PR_NF_14,
                    IIF(T.TAMANHO = 15,B.PR_NF_15,
                    IIF(T.TAMANHO = 16,B.PR_NF_16,
                    IIF(T.TAMANHO = 17,B.PR_NF_17,
                    IIF(T.TAMANHO = 18,B.PR_NF_18,
                    IIF(T.TAMANHO = 19,B.PR_NF_19,
                    IIF(T.TAMANHO = 20,B.PR_NF_20,B.PRECO_VENDA)))))))))))))))))))),0),B.PRECO_VENDA) PRECO

                From
                    TbCLIENTE_PRODUTO_PRECO B,
                    TBPRODUTO P,
                    SPC_MODELO_GRADE_TAMANHO(P.MODELO_CODIGO) T
                Where
                    B.CLIENTE_CODIGO = :EMPRESA_ID_2
                AND P.CODIGO = B.PRODUTO_CODIGO
            ) X

            ORDER BY TIPO, DESCRICAO, TAMANHO_DESCRICAO            
        ";
        
        $args = [
            'EMPRESA_ID_1' => $param->EMPRESA_ID,
            'EMPRESA_ID_2' => $param->EMPRESA_ID
        ];

        return $this->con->query($sql,$args);
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
                        ('NFE.'|| LPAD(E.NUMERO_NOTAFISCAL,6,'0')||' - '||
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
                        ('NFS.'|| LPAD(E.NUMERO_NOTAFISCAL,6,'0')||' - '||
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
    
}