<?php

namespace App\Models\DAO\Produto;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _27010 - Cadastro de Familias de Produto
 */
class _27010DAO {

    /**
     * Função generica
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function selectFamilia($param) {
        
        $con = new _Conexao;

        $first          = array_key_exists('FIRST'         , $param) ? "FIRST              " . $param->FIRST         : '';
        $skip           = array_key_exists('SKIP'          , $param) ? "SKIP               " . $param->SKIP          : '';
        $filtro         = array_key_exists('FILTRO'        , $param) ? "AND FILTRO LIKE  '%" . $param->FILTRO . "%'" : '';
        $status         = array_key_exists('STATUS'        , $param) ? "AND STATUS IN (" . arrayToList($param->STATUS, "'#'","'") . ")" : '';
        $tipoproduto_id = array_key_exists('TIPOPRODUTO_ID', $param) ? "AND TIPOPRODUTO_ID IN (" . arrayToList($param->TIPOPRODUTO_ID, "'#'","'") . ")" : '';

        $sql =
        "
            SELECT /*@FIRST*/ /*@SKIP*/
                FILTRO,
                ID,
                DESCRICAO,
                EXIGE_GRADE,
                LOCALIZACAO_ID,  
                LOCALIZACAO_DESCRICAO,
                UM_SIGLA,     
                UM_DESCRICAO,
                UM_ALTERNATIVA_SIGLA,
                NCM_CODIGO,
                HABILITA_COMPRA,
                HABILITA_VENDA,
                EXIGE_MODELO,
                CONTROLE_PATRIMONIO,
                INVENTARIO,
                CONTROLE_REVISAO,
                TIPOPRODUTO_ID,
                MASCARA_PRODUTO,
                TIPOPRODUTO_DESCRICAO,
                CONTROLE_COR,
                STATUS,
                USUARIO_ID,
                STATUS_DESCRICAO,
                ETIQUETA_ID,
                UNIDADEMEDIDA_FCI,
                QTD_MIN_FRT_CIF,
                FAMILIA_ID_MP

            FROM
                (SELECT
                    LPAD(A.CODIGO,3,'0') ID,
                    A.DESCRICAO,
                    A.EXIGE_GRADE,
                    A.LOCALIZACAO_CODIGO LOCALIZACAO_ID,   
                    (SELECT FIRST 1 L.DESCRICAO FROM TBLOCALIZACAO L WHERE A.LOCALIZACAO_CODIGO = L.CODIGO) LOCALIZACAO_DESCRICAO,
                    A.UNIDADEMEDIDA_SIGLA UM_SIGLA,   
                    (SELECT FIRST 1 U.DESCRICAO FROM TBUNIDADEMEDIDA U WHERE A.UNIDADEMEDIDA_SIGLA = U.SIGLA) UM_DESCRICAO,
                    COALESCE(A.UNIDADEMEDIDA_ALTERNATIVO,'') UM_ALTERNATIVA_SIGLA,
                    A.NCM_CODIGO,
                    A.HABILITA_COMPRA,
                    A.HABILITA_VENDA,
                    A.EXIGE_MODELO,
                    A.CONTROLE_PATRIMONIO,
                    A.INVENTARIO,
                    A.CONTROLE_REVISAO,
                    A.TIPOPRODUTO_CODIGO TIPOPRODUTO_ID,
                    (SELECT FIRST 1 B.DESCRICAO FROM TBTIPOPRODUTO B WHERE A.TIPOPRODUTO_CODIGO = B.CODIGO) TIPOPRODUTO_DESCRICAO,
                    A.MASCARA_PRODUTO,
                    A.CONTROLE_COR,
                    A.STATUS,          
                    (CASE WHEN A.STATUS = '0' THEN 'INATIVO' ELSE 'ATIVO' END) STATUS_DESCRICAO, 
                    A.USUARIO_CODIGO USUARIO_ID,
                    A.ETIQUETA_ID,
                    A.UNIDADEMEDIDA_FCI,
                    A.QTD_MIN_FRT_CIF,
                    (SELECT FIRST 1 FMA.FAMILIA_MODELO_ID FROM TBFAMILIA_MODELO_ALOCACAO FMA WHERE FMA.FAMILIA_ID = A.CODIGO AND FMA.ALOCACAO = '1')FAMILIA_ID_MP,

                    (LPAD(A.CODIGO,3,'0')   || ' ' ||
                     A.CODIGO               || ' ' ||
                     A.DESCRICAO)FILTRO
    
                FROM TBFAMILIA A
    
                ORDER BY A.DESCRICAO)X

            WHERE
                1=1
                /*@FILTRO*/
                /*@STATUS*/
                /*@TIPOPRODUTO_ID*/

        ";

        $args = [
            '@FIRST'          => $first,
            '@SKIP'           => $skip,
            '@FILTRO'         => $filtro,
            '@STATUS'         => $status,
            '@TIPOPRODUTO_ID' => $tipoproduto_id,
        ];

        return $con->query($sql,$args);
    }
    
    public static function selectFamiliaModeloAlocacao($param, _Conexao $con = null) {
        
        $sql = "
            SELECT DISTINCT
                LPAD(FM.CODIGO,4,'0') FAMILIA_ID,
                FM.DESCRICAO FAMILIA_DESCRICAO,

                TRIM(FMA.ALOCACAO) ALOCACAO,

                TRIM(CASE FMA.ALOCACAO
                WHEN 1 THEN 'SIM'
                WHEN 0 THEN 'NÃO' END) ALOCACAO_DESCRICAO

            FROM
                TBFAMILIA_MODELO_ALOCACAO FMA,
                TBFAMILIA FM,
                TBFAMILIA F
            WHERE
                FMA.FAMILIA_MODELO_ID = FM.CODIGO
            AND FMA.FAMILIA_ID        = F.CODIGO
            AND FMA.CONSUMO           = '1'
            AND FM.STATUS             = '1'
            AND F.CODIGO              = :FAMILIA_ID      
        ";
        
        $args = [
            'FAMILIA_ID' => $param->FAMILIA_ID
        ];
        
        return $con->query($sql, $args);
    }

}