<?php

namespace App\Models\DTO\Logistica;

use App\Models\DAO\Logistica\_14020DAO;

/**
 * Objeto _14020 - Registro de Producao - Div. Bojo Colante
 */
class _14020
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
         
    public function selectFrete($param) {
        
        $sql = "
            SELECT
                F.ID,
                F.ORIGEM,
                F.ORIGEM_ID,
                F.ESTABELECIMENTO_ID,
                F.ESTABELECIMENTO_RAZAOSOCIAL,
                F.ESTABELECIMENTO_UF,
                F.TRANSPORTADORA_ID,
                F.TRANSPORTADORA_RAZAOSOCIAL,
                F.TRANSPORTADORA_CLASSIFICACAO,
                F.FRETE_TRANSPORTADORA_ID,
                F.CLIENTE_ID,
                F.CLIENTE_RAZAOSOCIAL,
                F.CIDADE_ID,
                F.CIDADE_DESCRICAO,
                F.CIDADE_PRAZO_ENTREGA,
                F.UF,
                F.VALOR_TOTAL,
                F.QUANTIDADE_TOTAL QUANTIDADE,
                F.QUANTIDADE_VOLUME,
                F.CUBAGEM CUBAGEM_TOTAL,
                (SELECT SUM(D.PESO_EMBALAGEM) FROM TBFRETE_DETALHE D WHERE D.FRETE_ID = F.ID) PESO_EMBALAGEM,
                F.PESO_LIQUIDO PESO_LIQUIDO_TOTAL,
                F.PESO_BRUTO,
                F.PESO_CUBADO,
                F.VALOR_FINAL,
                F.DATAHORA,
                F.USUARIO_ID,
                F.CTE_TRANSPORTADORA_ID,
                F.CTE_NF,
                F.CTE_SERIE,

                (SELECT FIRST 1 T.VALOR_TOTAL_NF
                  FROM TBNFE N,
                       TBNFE_TOTAIS T
                 WHERE TRUE
                   AND N.EMPRESA_CODIGO = F.CTE_TRANSPORTADORA_ID
                   AND N.NUMERO_NOTAFISCAL = F.CTE_NF
                   AND N.SERIE = FN_LPAD(F.CTE_SERIE,3,0)
                   AND T.NFE_CONTROLE = N.CONTROLE
                   ) VALOR_DOC,

                   F.FRETE_CIDADE_AGRUPAMENTO_ID CIDADE_AGRUPAMENTO_ID,
                   (SELECT FIRST 1 A.DESCRICAO
                      FROM TBFRETE_CIDADE_AGRUPAMENTO A,
                           TBFRETE_CIDADE C
                     WHERE A.FRETE_TRANSPORTADORA_ID = F.FRETE_TRANSPORTADORA_ID
                       AND C.CIDADE_ID = F.CIDADE_ID
                       AND C.FRETE_CIDADE_AGRUPAMENTO_ID = A.ID) CIDADE_AGRUPAMENTO_DESCRICAO,
                       
                (SELECT FIRST 1 FN_DATE_TO_STRING(P.DATA_1) || ' A ' || FN_DATE_TO_STRING(P.DATA_2) 
                   FROM TBFRETE_TRANSPORTADORA_PERIODO P 
                  WHERE P.ID = F.PERIODO_ID) PERIODO

            FROM
                TBFRETE F
            WHERE
                F.ID = :ID            
        ";
        
        $args = [
            'ID' => $param->ID
            
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function selectFreteDetalhe($param = null) {
        
        $sql = "
            SELECT
                D.*

            FROM
                TBFRETE_DETALHE D
            WHERE
                D.FRETE_ID = :FRETE_ID
        ";
        
        $args = [
            'FRETE_ID' => $param->FRETE_ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function selectFreteDetalhePeso($param = null) {
        
        $sql = "
            SELECT
                A.*

            FROM
                TBFRETE_DETALHE_PESO A
            WHERE
                A.FRETE_DETALHE_ID = :FRETE_DETALHE_ID
        ";
        
        $args = [
            'FRETE_DETALHE_ID' => $param->FRETE_DETALHE_ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function selectFreteDetalheCubagem($param = null) {
        
        $sql = "
            SELECT
                A.*

            FROM
                TBFRETE_DETALHE_CUBAGEM A
            WHERE
                A.FRETE_DETALHE_ID = :FRETE_DETALHE_ID
        ";

        $args = [
            'FRETE_DETALHE_ID' => $param->FRETE_DETALHE_ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function spcFreteCalcular($param) {
        
        $sql = "
            SELECT
                *
            FROM
                SPC_FRETE(:ORIGEM,:ORIGEM_ID,:TRANSPORTADORA_ID,:ESTABELECIMENTO_ID)
        ";
        
        $args = [
            'ORIGEM'             => setDefValue($param->ORIGEM, null),
            'ORIGEM_ID'          => setDefValue($param->ORIGEM_ID, null),
            'TRANSPORTADORA_ID'  => setDefValue($param->TRANSPORTADORA_ID, null),
            'ESTABELECIMENTO_ID' => setDefValue($param->ESTABELECIMENTO_ID, null)
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function selectTransportadora($param) {
        
        $sql = "
            SELECT
                *

            FROM
                TBFRETE_TRANSPORTADORA
        ";
        
        $args = [
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function selectTransportadoraCidade($param) {
        
        $sql = "
            SELECT DISTINCT
                T.*

            FROM
                TBFRETE_CIDADE C,
                TBFRETE_CIDADE_AGRUPAMENTO A,
                TBFRETE_TRANSPORTADORA T

            WHERE
                C.CIDADE_ID = :CIDADE_ID
            AND A.ID = C.FRETE_CIDADE_AGRUPAMENTO_ID
            AND T.ID = A.FRETE_TRANSPORTADORA_ID

            ORDER BY RAZAOSOCIAL
        ";
        
        $args = [
            'CIDADE_ID' => $param->CIDADE_ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function selectComposicao($param) {
        
        $sql = "
            SELECT C.*
              FROM TBFRETE_COMPOSICAO_VALOR C
             WHERE C.FRETE_ID = :FRETE_ID
                   /*@HABILITA_CARGA*/
                   /*@HABILITA_COMPOSICAO*/
             ORDER BY SEQUENCIA
        ";
        
        $carga      = array_key_exists('HABILITA_CARGA'     , $param) ? "AND C.SEQUENCIA < 100" : '';        
        $composicao = array_key_exists('HABILITA_COMPOSICAO', $param) ? "AND C.SEQUENCIA > 100" : '';        
        
        $args = [
            'FRETE_ID'                => $param->FRETE_ID,
            '@HABILITA_CARGA'         => $carga,
            '@HABILITA_COMPOSICAO'    => $composicao,
        ];    
        
        return $this->con->query($sql,$args);
    } 
         
    public function selectCtrc($param) {
        
        $sql = "
            SELECT
                F.ID                           FRETE_ID,
                F.ORIGEM                       FRETE_ORIGEM,
                F.ORIGEM_ID                    FRETE_ORIGEM_ID,
                F.ESTABELECIMENTO_ID           FRETE_ESTABELECIMENTO_ID,
                F.ESTABELECIMENTO_RAZAOSOCIAL  FRETE_ESTABELEC_RAZAOSOCIAL,
                F.ESTABELECIMENTO_UF           FRETE_ESTABELECIMENTO_UF,
                F.TRANSPORTADORA_ID            FRETE_TRANSPORTADORA_ID,
                F.TRANSPORTADORA_RAZAOSOCIAL   FRETE_TRANSP_RAZAOSOCIAL,
                F.FRETE_TRANSPORTADORA_ID      FRETE_TRANSPORTADORA_ID,
                F.CLIENTE_ID                   FRETE_CLIENTE_ID,
                F.CLIENTE_RAZAOSOCIAL          FRETE_CLIENTE_RAZAOSOCIAL,
                F.CIDADE_ID                    FRETE_CIDADE_ID,
                F.CIDADE_DESCRICAO             FRETE_CIDADE_DESCRICAO,
                F.UF                           FRETE_UF,
                F.FRETE_CIDADE_AGRUPAMENTO_ID  FRETE_CIDADE_AGRUPAMENTO_ID,
                F.VALOR_TOTAL                  FRETE_VALOR_TOTAL,
                F.QUANTIDADE_TOTAL             FRETE_QUANTIDADE_TOTAL,
                F.QUANTIDADE_VOLUME            FRETE_QUANTIDADE_VOLUME,
                F.CUBAGEM                      FRETE_CUBAGEM,
                F.PESO_LIQUIDO                 FRETE_PESO_LIQUIDO,
                F.PESO_BRUTO                   FRETE_PESO_BRUTO,
                F.PESO_CUBADO                  FRETE_PESO_CUBADO,
                F.VALOR_FINAL                  FRETE_VALOR_FINAL,
                F.DATAHORA                     FRETE_DATAHORA,
                F.USUARIO_ID                   FRETE_USUARIO_ID,
                F.CTE_TRANSPORTADORA_ID        FRETE_CTE_TRANSPORTADORA_ID,
                F.CTE_NF                       FRETE_CTE_NF,
                F.CTE_SERIE                    FRETE_CTE_SERIE,
                
                (X.VALOR_TOTAL - F.VALOR_FINAL)*-1  FRETE_DIFERENCA,
                ((X.VALOR_TOTAL - F.VALOR_FINAL) / NULLIF(X.VALOR_TOTAL,0))*-1 FRETE_DIFERENCA_PERCENTUAL,
                
                X.*            
            FROM (
                SELECT FIRST :FIRST SKIP :SKIP
                    DISTINCT
                    TRIM('CTE') FRETE_ORIGEM,
                    NE.EMPRESA_CODIGO||'|'||NE.NUMERO_NOTAFISCAL||'|'||NE.SERIE FRETE_ORIGEM_ID,
                    FTF.FRETE_TRANSPORTADORA_ID,
                    NE.EMPRESA_CODIGO TRANSPORTADORA_ID,
                    NE.EMPRESA_CNPJ TRANSPORTADORA_CNPJ,
                    FN_MASK(NE.EMPRESA_CNPJ) TRANSPORTADORA_CNPJ_MASK,
                    NE.EMPRESA_RAZAOSOCIAL TRANSPORTADORA_RAZAOSOCIAL,
                    NE.CONTROLE ID,
                    NE.NUMERO_NOTAFISCAL NFE,
                    NE.SERIE NFE_SERIE,
                    NE.NUMERO_NOTAFISCAL || '-' || FN_LPAD(NE.SERIE,3,0) DOCUMENTO,
                    NE.DATA_EMISSAO,
                    NE.DATA_ENTRADA,

                    NS.EMPRESA_CODIGO CLIENTE_ID,
                    NS.EMPRESA_RAZAOSOCIAL CLIENTE_RAZAOSOCIAL,
                    NS.EMPRESA_UF CLIENTE_UF,
                    NS.EMPRESA_CIDADE CLIENTE_CIDADE,

                    NET.VALOR_TOTAL_NF VALOR_TOTAL,
--                    (SELECT FIRST 1 I.OPERACAO_CODIGO FROM TBNFE_ITEM I WHERE I.NFE_CONTROLE = NE.CONTROLE) OPERACAO,

                    LIST(NS.CONTROLE,', ') CLIENTE_NFS_CONTROLE,
                    LIST(NS.NUMERO_NOTAFISCAL || '-' || FN_LPAD(NS.SERIE,3,0),', ') CLIENTE_DOCUMENTO

                FROM
                    TBNFE NE,
                    TBNFE_TOTAIS NET,
                    TBFRETE_TRANSPORTADORA_FILIAL FTF,
                    TBFISCAL_ESPECIE FE,
                    TBNF_REFERENCIADA NR,
                    TBNFS NS

                WHERE
                    NE.ESPECIE = FE.CODIGO_SPED
                AND NE.EMPRESA_CODIGO    = FTF.TRANSPORTADORA_ID
                AND FE.DESCRICAO_RESUMIDA = 'CTRC'
                AND NET.NFE_CONTROLE = NE.CONTROLE
                AND NR.ID = NE.CONTROLE
                AND NR.NFS_ID > 0
                AND NS.CONTROLE = NR.NFS_ID

                /*@DATA_ENTRADA*/
                /*@FRETE_TRANSPORTADORA_ID*/

                GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
                ) X
                LEFT JOIN TBFRETE F ON F.ORIGEM = X.FRETE_ORIGEM AND F.ORIGEM_ID = X.FRETE_ORIGEM_ID
            WHERE TRUE
--            AND X.OPERACAO IN (SELECT OSPLIT FROM SPLIT(FN_PARAMETRO('SISTEMA.OP_FRETE_BOJO'),','))
            /*@DOCUMENTO*/                    
        ";
        
        $transportadora_id = array_key_exists('FRETE_TRANSPORTADORA_ID', $param) ? "AND FTF.FRETE_TRANSPORTADORA_ID $param->FRETE_TRANSPORTADORA_ID " : '';        
        $documento         = array_key_exists('DOCUMENTO'              , $param) ? "AND X.DOCUMENTO                 $param->DOCUMENTO               " : '';        
        $data_entrada      = array_key_exists('DATA_ENTRADA'           , $param) ? "AND NE.DATA_ENTRADA             $param->DATA_ENTRADA            " : '';        
        
        $args = [
            'FIRST'                    => setDefValue($param->FIRST, 99999999999999),
            'SKIP'                     => setDefValue($param->SKIP , 0),
            '@FRETE_TRANSPORTADORA_ID' => $transportadora_id,
            '@DOCUMENTO'               => $documento,
            '@DATA_ENTRADA'            => $data_entrada,
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectCidade ( $param = null ) {
        
        $sql = "
            SELECT FIRST :FIRST SKIP :SKIP *
            FROM (
                SELECT
                    C.ID,
                    C.DESCRICAO,
                    C.UF,
                    C.UF || ' - ' || FN_LPAD(C.ID,4,0) || ' - ' || C.DESCRICAO FILTRO
    
                FROM
                    TBCIDADE C  
                )X
            WHERE TRUE
            /*@FILTRO*/
        ";
        
        $args = [
            'FIRST'   => setDefValue($param->FIRST, 100),
            'SKIP'    => setDefValue($param->SKIP , 0),
            '@FILTRO' => array_key_exists('FILTRO', $param) ? "AND FILTRO LIKE UPPER('%'||REPLACE(CAST('$param->FILTRO' AS VARCHAR(500)),' ','%')||'%')" : '',
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectCliente ( $param = null ) {
        
        $sql = "
            SELECT FIRST :FIRST SKIP :SKIP *
            FROM (
                SELECT
                    FN_LPAD(E.CODIGO,4,0) ID,
                    E.RAZAOSOCIAL,
                    E.NOMEFANTASIA,
                    E.UF,
                    E.CIDADE,
                    (SELECT FIRST 1 C.ID FROM TBCIDADE C WHERE C.DESCRICAO = E.CIDADE AND C.UF = E.UF) CIDADE_ID,
                    FN_LPAD(E.CODIGO,4,0) || ' - ' || E.RAZAOSOCIAL || ' - ' || E.NOMEFANTASIA || ' - ' || E.UF FILTRO

                FROM
                    TBCLIENTE C,
                    TBEMPRESA E
                WHERE
                    C.CODIGO = E.CODIGO
                )X       
                WHERE TRUE
            /*@FILTRO*/
        ";
        
        $args = [
            'FIRST'   => setDefValue($param->FIRST, 100),
            'SKIP'    => setDefValue($param->SKIP , 0),
            '@FILTRO' => array_key_exists('FILTRO', $param) ? "AND FILTRO LIKE UPPER('%'||REPLACE(CAST('$param->FILTRO' AS VARCHAR(500)),' ','%')||'%')" : '',
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
    public function insertFreteTmp($param = null) {
                
        $sql = "
            INSERT INTO TMPFRETE (
                MODELO_ID, 
                COR_ID, 
                TAMANHO, 
                QUANTIDADE, 
                VALOR_UNITARIO
            ) VALUES (
                :MODELO_ID, 
                :COR_ID, 
                :TAMANHO, 
                :QUANTIDADE, 
                :VALOR_UNITARIO
            );
        ";
        
        $args = [
            'MODELO_ID'      => setDefValue($param->MODELO_ID     , null),
            'COR_ID'         => setDefValue($param->COR_ID        , null),
            'TAMANHO'        => setDefValue($param->TAMANHO       , null),
            'QUANTIDADE'     => setDefValue($param->QUANTIDADE    , null),
            'VALOR_UNITARIO' => setDefValue($param->VALOR_UNITARIO, null),
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function updateInsertRegra($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBREGRA_CALCULO_LOGISTICA');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBREGRA_CALCULO_LOGISTICA (
                ID,
                FAMILIA_PRODUCAO,
                SEQUENCIA,
                FAMILIA_ID,
                GP_ID,
                PERFIL_UP,
                UP_PADRAO1,
                UP_PADRAO2,
                CALCULO_REBOBINAMENTO,
                CALCULO_CONFORMACAO,
                CLOGISTICA,
                FATOR,
                STATUS,
                REMESSAS_DEFEITO
            ) VALUES (
                :ID,
                :FAMILIA_PRODUCAO,
                :SEQUENCIA,
                :FAMILIA_ID,
                :GP_ID,
                :PERFIL_UP,
                :UP_PADRAO1,
                :UP_PADRAO2,
                :CALCULO_REBOBINAMENTO,
                :CALCULO_CONFORMACAO,
                :CLOGISTICA,
                :FATOR,
                :STATUS,
                :REMESSAS_DEFEITO
            ) MATCHING ( ID );
        ";
        
        $args = [
            'ID'                    => $param->ID,                  
            'FAMILIA_PRODUCAO'      => $param->FAMILIA_PRODUCAO,    
            'SEQUENCIA'             => $param->SEQUENCIA,           
            'FAMILIA_ID'            => $param->FAMILIA_ID,          
            'GP_ID'                 => $param->GP_ID,               
            'PERFIL_UP'             => $param->PERFIL_UP,           
            'UP_PADRAO1'            => $param->UP_PADRAO1,          
            'UP_PADRAO2'            => $param->UP_PADRAO2,          
            'CALCULO_REBOBINAMENTO' => $param->CALCULO_REBOBINAMENTO,
            'CALCULO_CONFORMACAO'   => $param->CALCULO_CONFORMACAO, 
            'CLOGISTICA'                => $param->CLOGISTICA,              
            'FATOR'                 => $param->FATOR,               
            'STATUS'                => $param->STATUS,              
            'REMESSAS_DEFEITO'      => $param->REMESSAS_DEFEITO,    
        ];    
        
        return $this->con->query($sql,$args);
    } 
        
    public function deleteRegra($param) {
        
        $sql = "
            DELETE FROM TBREGRA_CALCULO_LOGISTICA WHERE ID = :ID
        ";
        
        $args = [
            'ID' => $param->ID
        ];    
        
        return $this->con->query($sql,$args);
    } 
    
}