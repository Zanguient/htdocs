<?php

namespace App\Models\DTO\Produto;

use App\Models\DAO\Produto\_27010DAO;

/**
 * Objeto _27010 - Cadastro de Familias de Produto
 */
class _27010
{
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }  
    
    public function selectFamilia($dados) {
        
        $param = obj_case($dados);
        
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
                    TRIM(A.EXIGE_GRADE) EXIGE_GRADE,
                    A.LOCALIZACAO_CODIGO LOCALIZACAO_ID,   
                    (SELECT FIRST 1 L.DESCRICAO FROM TBLOCALIZACAO L WHERE A.LOCALIZACAO_CODIGO = L.CODIGO) LOCALIZACAO_DESCRICAO,
                    A.UNIDADEMEDIDA_SIGLA UM_SIGLA,   
                    (SELECT FIRST 1 U.DESCRICAO FROM TBUNIDADEMEDIDA U WHERE A.UNIDADEMEDIDA_SIGLA = U.SIGLA) UM_DESCRICAO,
                    COALESCE(A.UNIDADEMEDIDA_ALTERNATIVO,'') UM_ALTERNATIVA_SIGLA,
                    A.NCM_CODIGO,
                    TRIM(A.HABILITA_COMPRA) HABILITA_COMPRA,
                    TRIM(A.HABILITA_VENDA) HABILITA_VENDA,
                    TRIM(A.EXIGE_MODELO) EXIGE_MODELO,
                    TRIM(A.CONTROLE_PATRIMONIO) CONTROLE_PATRIMONIO,
                    TRIM(A.INVENTARIO) INVENTARIO,
                    TRIM(A.CONTROLE_REVISAO) CONTROLE_REVISAO,
                    A.TIPOPRODUTO_CODIGO TIPOPRODUTO_ID,
                    (SELECT FIRST 1 B.DESCRICAO FROM TBTIPOPRODUTO B WHERE A.TIPOPRODUTO_CODIGO = B.CODIGO) TIPOPRODUTO_DESCRICAO,
                    A.MASCARA_PRODUTO,
                    TRIM(A.CONTROLE_COR) CONTROLE_COR,
                    TRIM(A.STATUS) STATUS,          
                    TRIM((CASE WHEN A.STATUS = '0' THEN 'INATIVO' ELSE 'ATIVO' END)) STATUS_DESCRICAO, 
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

        $first          = array_key_exists('FIRST'         , $param) ? "FIRST              " . $param->FIRST         : '';
        $skip           = array_key_exists('SKIP'          , $param) ? "SKIP               " . $param->SKIP          : '';
        $filtro         = array_key_exists('FILTRO'        , $param) ? "AND FILTRO LIKE  UPPER('%'||REPLACE(CAST('$param->FILTRO' AS VARCHAR(500)),' ','%')||'%')" : '';
        $status         = array_key_exists('STATUS'        , $param) ? "AND STATUS IN (" . arrayToList($param->STATUS, "'#'","'") . ")" : '';
        $tipoproduto_id = array_key_exists('TIPOPRODUTO_ID', $param) ? "AND TIPOPRODUTO_ID IN (" . arrayToList($param->TIPOPRODUTO_ID, "'#'","'") . ")" : '';

        $args = [
            '@FIRST'          => $first,
            '@SKIP'           => $skip,
            '@FILTRO'         => $filtro,
            '@STATUS'         => $status,
            '@TIPOPRODUTO_ID' => $tipoproduto_id,
        ];
         
        return $this->con->query($sql,$args);
    }    
    
	/**
	 * 
	 */
	public static function getChecList($dados) {
		return _27010DAO::getChecList($dados);
	}
	
	/**
	 * Listar
	 */
	public static function listar($dados) {
		return _27010DAO::listar($dados);
	}
    
//    public static function selectFamilia($dados) {
//        return _27010DAO::selectFamilia(obj_case($dados));
//    }
    
	/**
	 * Listar
	 */
	public static function selectFamiliaModeloAlocacao($dados, $con = null) {
		return _27010DAO::selectFamiliaModeloAlocacao(obj_case($dados),$con);
	}

}