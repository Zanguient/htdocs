<?php

namespace App\Models\DAO\Financeiro;

use Exception;
use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\DTO\Financeiro\_20030;
use Illuminate\Support\Facades\Auth;

class _20030DAO
{	
		
	/**
	 * Pesquisa por Centro de Custo de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCusto($filtro) {
        
		$con = new _Conexao();
        
        return self::exibirCCusto($con, $filtro);
	}

	/**
	 * Pesquisa todos os Centro de Custos.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCustoTodos() {
        
		$con = new _Conexao();
        
        return self::exibirCCustoTodos($con);
	}
    
    /**
	 * Pesquisa por Centro de Custo de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCustoGp($filtro) {
        
		$con = new _Conexao();
        
        return self::exibirCCustoGp($con, $filtro);
	}
    
    /**
	 * Pesquisa por Centro de Custo de acordo com o que for digitado pelo usuário.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCusto2($filtro) {
        
		$con = new _Conexao();
        
        return self::exibirCCusto2($con, $filtro);
	}
    
    /**
	 * Pesquisa por Centro de Custo de acordo com as permissões para a requisição de consumo.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCustoConsumoRequisicao($filtro) {
        
		$con = new _Conexao();
        
        return self::exibirCCustoConsumoRequisicao($con, $filtro);
	}
        
    /**
	 * Pesquisa por Centro de Custo de acordo com o que for digitado pelo usuário apenas para indicadores.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function pesquisaCCustoIndicador($filtro) {
        
		$con = new _Conexao();
        
        return self::exibirCCustoIndicador($con, $filtro);
	}
    
	
	/**
	 * Paginação com scroll.
	 * Função chamada via Ajax.
	 *
	 * @param int $qtd_por_pagina
	 * @param int $pagina
	 * @return array
	 */
	public static function paginacaoScroll($qtd_por_pagina, $pagina) {
	
// 		return DB::select('
// 					select first '. $qtd_por_pagina .' skip '. $pagina .' r.ID, r.URGENCIA, r.DATA,
// 	 				 (select first 1 list(i.OC) OC from TBREQUISICAO_OC_ITEM i where r.ID = i.REQUISICAO_ID),
// 	 				 (select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID),
// 	 				 (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO)
// 					from TBREQUISICAO_OC r
// 	    			order by r.ID DESC
// 	    	   ');
	
	}
	
	/**
	 * Filtrar lista de requisições.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtraObj($filtro) {
	
// 		return DB::select('
// 					select first 20 r.ID, r.URGENCIA, r.DATA,
// 					 (select first 1 list(i.OC) OC from TBREQUISICAO_OC_ITEM i where r.ID = i.REQUISICAO_ID),
// 					 (select first 1 u.NOME USUARIO from TBUSUARIO u where u.CODIGO = r.USUARIO_ID),
// 				   	 (select first 1 c.DESCRICAO CCUSTO_DESCRICAO from TBCENTRO_DE_CUSTO c where c.CODIGO = r.CCUSTO)
// 					from TBREQUISICAO_OC r
// 					where (r.ID like \'%'.$filtro.'%\') 
// 					   or (r.CCUSTO like \'%'.$filtro.'%\') 
//     				order by r.ID DESC
// 	    	   ');
	
	}
    
    /**
    * • Retorna um sentro de custo (desc e id)
    */
    public static function getCCusto($id)
    {   
        $first  = 1;
        
        $con = new _Conexao();
        
		$sql = "
            SELECT FIRST :FIRST
                X.ID,
                X.DESCRICAO,
                X.MASK
            FROM
                (SELECT
                    C.CODIGO ID,
                
                    IIF(char_length(C.CODIGO)=2,C.CODIGO,
                    IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                    IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK,                
                
                    UPPER(C.DESCRICAO)DESCRICAO,
                    (F_REMOVE_ACENTOS(
                        C.CODIGO
                    || ' ' ||
                        IIF(char_length(C.CODIGO)=2,C.CODIGO,
                        IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3),
                        IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3)||' '||Substring(C.CODIGO From 6 For 3),'')))
                    || ' ' ||
                        C.DESCRICAO
                    ))FILTRO
                FROM
                    VWCENTRO_DE_CUSTO C
				LEFT JOIN
					TBUSUARIO_CCUSTO U
				ON
					C.CODIGO LIKE U.CCUSTO||'%'

                WHERE 
					C.ID > 1000
				AND U.USUARIO_ID = :USU_ID
				and c.status = 1
				
                ORDER BY
                    1,2)X
            WHERE
                X.ID = :FILTRO
		";

		$args = array(
			':FIRST'	=> $first,
			':FILTRO'	=> $id,
			':USU_ID'	=> Auth::user()->CODIGO
		);

		return $con->query($sql, $args);
    }

	public static function exibirCCusto(_Conexao $con, $filtro = false) {
		
		$first  = env('NUM_REGISTROS', '30');
		$filtro = '%' . Helpers::removeAcento($filtro, '%', 'upper',true) . '%';

		$sql = "
            SELECT FIRST :FIRST distinct
                X.ID,
                X.DESCRICAO,
                X.MASK,
                FILTRO
            FROM
                (SELECT
                    C.CODIGO ID,
                
                    IIF(char_length(C.CODIGO)=2,C.CODIGO,
                    IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                    IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK,                
                
                    UPPER(C.DESCRICAO)DESCRICAO,
                    (F_REMOVE_ACENTOS(
                        C.CODIGO
                    || ' ' ||
                        IIF(char_length(C.CODIGO)=2,C.CODIGO,
                        IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3),
                        IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3)||' '||Substring(C.CODIGO From 6 For 3),'')))
                    || ' ' ||
                        C.DESCRICAO
                    ))FILTRO
                FROM
                    VWCENTRO_DE_CUSTO C
				LEFT JOIN
					TBUSUARIO_CCUSTO U
				ON
					C.CODIGO = U.CCUSTO
					OR C.CODIGO LIKE U.CCUSTO||'%'

                WHERE 
					C.ID > 1000
				AND U.USUARIO_ID = :USU_ID
				
                ORDER BY
                    1,2)X
            WHERE
                X.FILTRO LIKE :FILTRO
		";

		$args = array(
			':FIRST'	=> $first,
			':FILTRO'	=> $filtro,
			':USU_ID'	=> Auth::user()->CODIGO
		);

		return $con->query($sql, $args);
	}

	public static function exibirCCustoTodos(_Conexao $con) {

		$sql = "
            SELECT
		        C.CODIGO ID,
		    	UPPER(C.DESCRICAO)DESCRICAO,

		        IIF(char_length(C.CODIGO)=2,C.CODIGO,
		        IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
		        IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK
		    
		    FROM
		        VWCENTRO_DE_CUSTO C

		    WHERE 
		        C.ID > 1000
		";

		return $con->query($sql);
	}
    
    public static function exibirCCustoGp(_Conexao $con, $filtro = false) {
		
		$filtro = '%' . Helpers::removeAcento($filtro, '%', 'upper',true) . '%';

		$sql = "
            SELECT distinct
                X.ID,
                X.DESCRICAO,
                X.MASK,
                FILTRO
            FROM
                (SELECT
                    C.CODIGO ID,
                
                    IIF(char_length(C.CODIGO)=2,C.CODIGO,
                    IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                    IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK,                
                
                    UPPER(C.DESCRICAO)DESCRICAO,
                    (F_REMOVE_ACENTOS(
                        C.CODIGO
                    || ' ' ||
                        IIF(char_length(C.CODIGO)=2,C.CODIGO,
                        IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3),
                        IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3)||' '||Substring(C.CODIGO From 6 For 3),'')))
                    || ' ' ||
                        C.DESCRICAO
                    ))FILTRO
                FROM
                    VWCENTRO_DE_CUSTO C
                WHERE
					status = 1
                    and C.codigo in (select d.ccusto from tbgp d where coalesce(d.ccusto,'') <> '')
                
                ORDER BY
                    1,2)X
            WHERE
                X.FILTRO LIKE :FILTRO
		";

		$args = array(
			':FILTRO'	=> $filtro
		);

		return $con->query($sql, $args);
	}
    
    public static function exibirCCusto2(_Conexao $con, $filtro = false){
		$first  = env('NUM_REGISTROS', '30');
		$filtro = '%' . Helpers::removeAcento($filtro, '%', 'upper',true) . '%';

		$sql = /** @lang text */
			"
            SELECT FIRST :FIRST
                X.ID,
                X.DESCRICAO,
                X.MASK,
                FILTRO
            FROM
                (SELECT
                    C.CODIGO ID,
                
                    IIF(char_length(C.CODIGO)=2,C.CODIGO,
                    IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                    IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK,                
                
                    UPPER(C.DESCRICAO)DESCRICAO,
                    (F_REMOVE_ACENTOS(
                        C.CODIGO
                    || ' ' ||
                        IIF(char_length(C.CODIGO)=2,C.CODIGO,
                        IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3),
                        IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3)||' '||Substring(C.CODIGO From 6 For 3),'')))
                    || ' ' ||
                        C.DESCRICAO
                    ))FILTRO
                FROM
                    VWCENTRO_DE_CUSTO C
                LEFT JOIN
					TBUSUARIO_CCUSTO U
				ON
					C.CODIGO LIKE U.CCUSTO||'%'

                WHERE 
					C.ID > 1000
				AND U.USUARIO_ID = :USU_ID
				
                ORDER BY
                    1,2)X
            WHERE
                X.FILTRO LIKE :FILTRO
		";

		$args = array(
			':FIRST'	=> $first,
			':FILTRO'	=> $filtro,
			':USU_ID'	=> Auth::user()->CODIGO
		);

		return $con->query($sql, $args);
	}
	
    public static function exibirCCustoConsumoRequisicao(_Conexao $con, $filtro = false){
		
		$filtro = '%' . Helpers::removeAcento($filtro, '%', 'upper',true) . '%';

		$sql = /** @lang text */
			"
            SELECT FIRST 30 DISTINCT
                X.ID,
                X.DESCRICAO,
                X.MASK,
                FILTRO
				
				FROM (
					SELECT
						C.CODIGO ID,

						IIF(char_length(C.CODIGO)=2,C.CODIGO,
						IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
						IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK,                

						IIF(CHAR_LENGTH(C.CODIGO)=2,C.DESCRICAO,'')||
				        IIF(CHAR_LENGTH(C.CODIGO)>=5,
				            (SELECT FIRST 1 (CC.DESCRICAO) FROM VWCENTRO_DE_CUSTO CC
				                WHERE CC.CODIGO = SUBSTRING(C.CODIGO FROM 1 FOR 5)),'') ||
				        IIF(CHAR_LENGTH(C.CODIGO)=8,' - '||
				            (SELECT FIRST 1 (CC.DESCRICAO) FROM VWCENTRO_DE_CUSTO CC
				                WHERE CC.CODIGO = C.CODIGO),'') DESCRICAO,

						(F_REMOVE_ACENTOS(
							C.CODIGO
							|| ' ' ||
							IIF(CHAR_LENGTH(C.CODIGO)=2,C.DESCRICAO,'')||
				            IIF(CHAR_LENGTH(C.CODIGO)>=5,
				                (SELECT FIRST 1 (CC.DESCRICAO) FROM VWCENTRO_DE_CUSTO CC
				                    WHERE CC.CODIGO = SUBSTRING(C.CODIGO FROM 1 FOR 5)),'') ||
				            IIF(CHAR_LENGTH(C.CODIGO)=8,' - '||
				                (SELECT FIRST 1 (CC.DESCRICAO) FROM VWCENTRO_DE_CUSTO CC
				                    WHERE CC.CODIGO = C.CODIGO),'')
						))FILTRO
						
						FROM
							VWCENTRO_DE_CUSTO C
							LEFT JOIN TBCONSUMO_REQ_PERMISSAO CR ON C.CODIGO LIKE CR.CCUSTO_REQUISICAO||'%'

						WHERE 
							C.ID > 1000
						AND C.STATUS = '1'
						AND CR.REQUISICAO = '1'
						AND CR.USUARIO_ID = :USU_ID

						ORDER BY
							1,2
				)X
				WHERE
					X.FILTRO LIKE :FILTRO
		";

		$args = array(
			':FILTRO'	=> $filtro,
			':USU_ID'	=> Auth::user()->CODIGO
		);

		return $con->query($sql, $args);
	}
    
    public static function exibirCCustoIndicador(_Conexao $con, $filtro = false){
		
		$first  = env('NUM_REGISTROS', '30');
		$filtro = '%' . Helpers::removeAcento($filtro, '%', 'upper',true) . '%';

		$sql = /** @lang text */
			"
            SELECT FIRST :FIRST
                X.ID,
                X.DESCRICAO,
                X.MASK,
                FILTRO,
                x.REG_INDICADOR
            FROM
                (SELECT
                    C.CODIGO ID,
                    C.REG_INDICADOR,
                    IIF(char_length(C.CODIGO)=2,C.CODIGO,
                    IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                    IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK,                
                
                    UPPER(C.DESCRICAO)DESCRICAO,
                    (F_REMOVE_ACENTOS(
                        C.CODIGO
                    || ' ' ||
                        IIF(char_length(C.CODIGO)=2,C.CODIGO,
                        IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3),
                        IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||' '||Substring(C.CODIGO From 3 For 3)||' '||Substring(C.CODIGO From 6 For 3),'')))
                    || ' ' ||
                        C.DESCRICAO
                    ))FILTRO
                FROM
                    VWCENTRO_DE_CUSTO C

                WHERE C.ID < 1000
                and REG_INDICADOR = 1
                
                ORDER BY
                    1,2)X
            WHERE
                X.FILTRO LIKE :FILTRO";

		$args = array(
			':FIRST'	=> $first,
			':FILTRO'	=> $filtro
		);

		return $con->query($sql, $args);
	}
	
}

?>