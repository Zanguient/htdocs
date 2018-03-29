<?php

namespace App\Models\DTO\Produto;

use App\Models\DAO\Produto\_27050DAO;

/**
 * Objeto 27050 - Produto
 */
class _27050 {
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }  

    public function selectProduto($param) {

        $sql = "
            SELECT FIRST :FIRST SKIP :SKIP
                FN_LPAD(P.CODIGO,6,0) PRODUTO_ID,
                P.DESCRICAO PRODUTO_DESCRICAO,

                P.UNIDADEMEDIDA_SIGLA UM,
                TRIM(COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'')) UM_ALTERNATIVA,

                COALESCE(F.CODIGO   ,'')    FAMILIA_ID,
                COALESCE(F.DESCRICAO,'') FAMILIA_DESCRICAO,

                COALESCE(L.CODIGO   ,'') LINHA_ID,
                COALESCE(L.DESCRICAO,'') LINHA_DESCRICAO,

                COALESCE(M.CODIGO   ,'') MODELO_ID,
                COALESCE(M.DESCRICAO,'') MODELO_DESCRICAO,

                COALESCE(M.CODIGO   ,'') COR_ID,
                COALESCE(M.DESCRICAO,'') COR_DESCRICAO,

                P.GRADE_CODIGO GRADE_ID,
                P.LOCALIZACAO_CODIGO LOCALIZACAO_ID,

                TRIM(P.STATUS) STATUS,
                TRIM(CASE P.STATUS
                WHEN '0' THEN 'INATIVO'
                WHEN '1' THEN 'ATIVO' END) STATUS_DESCRICAO,

                TRIM(P.HABILITA_COMPRA) HABILITA_COMPRA,
                TRIM(CASE P.HABILITA_COMPRA
                WHEN '0' THEN 'NÃO'
                WHEN '1' THEN 'SIM' END) HABILITA_COMPRA_DESCRICAO,

                TRIM(P.HABILITA_VENDA) HABILITA_VENDA,
                TRIM(CASE P.HABILITA_VENDA
                WHEN '0' THEN 'NÃO'
                WHEN '1' THEN 'SIM' END) HABILITA_VENDA_DESCRICAO

            FROM
                TBPRODUTO P
                LEFT JOIN TBFAMILIA      F ON F.CODIGO = P.FAMILIA_CODIGO
                LEFT JOIN TBMODELO_LINHA L ON L.CODIGO = P.LINHA_CODIGO
                LEFT JOIN TBMODELO       M ON M.CODIGO = P.MODELO_CODIGO
                LEFT JOIN TBCOR          C ON C.CODIGO = P.COR_CODIGO

            WHERE TRUE
            AND (COALESCE(FN_LPAD(P.CODIGO,6,0),'')|| ' - ' ||COALESCE(P.DESCRICAO,'')) LIKE '%' || REPLACE(UPPER(CAST(:FILTRO AS VARCHAR(1000))),' ','%') || '%'
            /*@STATUS*/
            /*@MODELO_ID*/
            

            ORDER BY PRODUTO_DESCRICAO
        ";
        
        $arg = (object)[];
        
        if ( isset($param->STATUS) && $param->STATUS > -1 ) {
            $arg->STATUS = " = '$param->STATUS'";
        }        
        
        if ( isset($param->MODELO_ID) && $param->MODELO_ID > -1 ) {
            $arg->MODELO_ID = " = $param->MODELO_ID";
        }        
        
        if ( isset($param->MODELO_ID) && trim($param->MODELO_ID) != '' ) {
            $arg->MODELO_ID = $param->MODELO_ID;
        }

        $status    = array_key_exists('STATUS', $arg)    ? "AND P.STATUS        $arg->STATUS   " : '';
        $modelo_id = array_key_exists('MODELO_ID', $arg) ? "AND P.MODELO_CODIGO $arg->MODELO_ID" : '';
          
        
        $args = [
            'FIRST'       => setDefValue($param->FIRST , 100),
            'SKIP'        => setDefValue($param->SKIP  , 0  ),
            'FILTRO'      => setDefValue($param->FILTRO, '%'),
            '@STATUS'     => $status,
            '@MODELO_ID'  => $modelo_id
        ];

        return $this->con->query($sql,$args);
    }  
	
	/**
	 * Pesquisa produto de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtrar($filtro) {
		return _27050DAO::filtrar($filtro);
	}

	/**
	 * Consultar produto por modelo e cor.
	 * @param array $filtro
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarPorModeloECor($filtro, $con) {
		return _27050DAO::consultarPorModeloECor($filtro, $con);
	}
	
}
