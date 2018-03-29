<?php

namespace App\Models\DAO\Produto;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto 27050 - Produto
 */
class _27050DAO {
	
	/**
     * Pesquisa produto de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     *
     * @param string $filtro
	 * @param array $condicao
     * @return array
     */
    public static function filtrar($filtro, $condicao = null) {
		
		$con		= new _Conexao();	
		
		//$prod_perm	= _11010::produtoPerm();		
        $palavra	= $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';

		if ( isset($condicao) && count($condicao) > 0 ){
			$estab = $condicao[0];
		}
        
        if ( isset($condicao) && count($condicao) > 1 ){
			$saldo = $condicao[1];
		}
        
        $valor = empty($saldo) ? '/*SALDO*/' : 'WHERE SALDO > 0'; 
		
		$sql = "
            SELECT * FROM(
			SELECT FIRST 100 
                LPAD(P.CODIGO, 5, '0') ID, 
                P.DESCRICAO, 
                P.UNIDADEMEDIDA_SIGLA,
                coalesce((SELECT sum(E.SALDO) FROM TBESTOQUE_SALDO E
                  WHERE E.Estabelecimento_Codigo = :ESTABELECIMENTO
                    AND E.PRODUTO_CODIGO = p.CODIGO),0.000) SALDO
               FROM TBPRODUTO P, TbUsuario_Familia UF
               WHERE P.familia_codigo = UF.familia_id
				 AND P.STATUS = '1'
                 AND UF.usuario_id = :USUARIO
                 AND P.CODIGO || P.DESCRICAO LIKE '$palavra'
             ORDER BY 2,1
             ) A ".$valor."
		";
		
		$args = array(
			':ESTABELECIMENTO'	=> empty($estab) ? : $estab,
			':USUARIO'			=> Auth::user()->CODIGO
		);
		
        return $con->query($sql, $args);
    }
	
	/**
     * Pesquisa produto de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     *
     * @param string $filtro
	 * @param array $condicao
     * @return array
     */
    public static function filtrar2($param) {
		
		$con		= new _Conexao();	
        
        $filtro     = isset($param['FILTRO']) && $param['FILTRO'] != '' ? "AND P.CODIGO || P.DESCRICAO LIKE '" . '%' . str_replace(' ', '%', strtoupper($param['FILTRO'])) . "%'" : '';
        $produto_id = isset($param['OPTIONS']['PRODUTO_ID']) ? 'AND P.CODIGO = ' . $param['OPTIONS']['PRODUTO_ID'] : '';

		
        
        $valor = '';//empty($saldo) ? '/*SALDO*/' : 'WHERE SALDO > 0'; 
		
		$sql = "
            SELECT * FROM(
			SELECT FIRST 100 
                LPAD(P.CODIGO, 5, '0') ID, 
                P.DESCRICAO, 
                P.UNIDADEMEDIDA_SIGLA,
                coalesce((SELECT FIRST 1 E.SALDO FROM TBESTOQUE_SALDO E
                  WHERE 
                        E.Localizacao_Codigo = P.Localizacao_Codigo
                    AND E.PRODUTO_CODIGO = P.CODIGO),0.000) SALDO
               FROM TBPRODUTO P
               WHERE TRUE
                $filtro 
                $produto_id
             ORDER BY 2,1
             ) A ".$valor."
		";
        return $con->query($sql);
    }

	/**
     * Pesquisa produto de acordo com as permissões da requisição de consumo.
     * Função chamada via Ajax.
     *
     * @param string $filtro
	 * @param array $condicao
     * @return array
     */
    public static function filtrarConsumoRequisicao($filtro, $condicao = null) {
		
		$con		= new _Conexao();	
		
        $palavra	= $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';

		if ( isset($condicao) && count($condicao) > 0 ){
			$estab = $condicao[0];
		}
		
		$sql = "
			SELECT FIRST 200 DISTINCT 
				LPAD(P.CODIGO, 5, '0') ID, 
				P.DESCRICAO, 
				P.UNIDADEMEDIDA_SIGLA,
				COALESCE((SELECT FIRST 1 E.SALDO FROM TBESTOQUE_SALDO E
				  WHERE E.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO
					AND E.LOCALIZACAO_CODIGO = IIF(P.LOCALIZACAO_CODIGO > 0, P.LOCALIZACAO_CODIGO, (SELECT FIRST 1 F.LOCALIZACAO_CODIGO FROM TBFAMILIA F WHERE F.CODIGO = P.FAMILIA_CODIGO))
					AND E.PRODUTO_CODIGO = P.CODIGO),0.000) SALDO,
				G.TOTAL_TAMANHOS

				FROM
					TBPRODUTO P
					LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON CR.FAMILIA = P.FAMILIA_CODIGO
					LEFT JOIN TBGRADE G ON G.CODIGO = P.GRADE_CODIGO

				WHERE 
					CR.USUARIO_ID = :USUARIO
				AND CR.REQUISICAO = '1'
				AND P.STATUS = '1'
				AND P.CODIGO || P.DESCRICAO LIKE :PRODUTO
				
				ORDER BY 2,1
		";
		
		$args = array(
			':ESTABELECIMENTO'	=> empty($estab) ? : $estab,
			':USUARIO'			=> Auth::user()->CODIGO,
			':PRODUTO'			=> $palavra
		);
		
        return $con->query($sql, $args);
    }

    /**
	 * Consultar produto por modelo e cor.
	 * @param array $filtro
	 * @param _Conexao $con
	 * @return array
	 */
    public static function consultarPorModeloECor($filtro, $con) {

    	$sql = "
    		Select
			    P.Codigo,
			    P.Descricao,
			    P.UnidadeMedida_Sigla UM,
			    P.Grade_Codigo,

			    (Select First 1 C.Descricao From TbCor C Where C.Codigo = P.Cor_Codigo
			    ) Cor_Descricao

			From TbProduto P

			Where
			    Status = '1'
			and P.Modelo_Codigo = :MODELO_ID
			and P.Cor_Codigo = :COR_ID
    	";

    	$args = [
    		':MODELO_ID' 	=> $filtro['MODELO_ID'],
    		':COR_ID' 		=> $filtro['COR_ID']
    	];

    	return $con->query($sql, $args);

    }
}
