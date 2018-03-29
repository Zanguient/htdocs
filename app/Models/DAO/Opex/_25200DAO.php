<?php

namespace App\Models\DAO\Opex;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;

class _25200DAO
{	
	
	
	/**
	 * filtra notas um uma alditoria.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtrar($filtro) {
        
		$con = new _Conexao();
        
        return self::filtrarIndicador($con, $filtro);
	}
    
    public static function filtrarIndicador(_Conexao $con, $filtro = false){
		$first  = env('NUM_REGISTROS', '30');
		$filtro = '%' . Helpers::removeAcento($filtro, '%', 'upper',true) . '%';

		$sql = /** @lang text */
			"
            SELECT FIRST :FIRST
                X.ID,
                X.MASK,
                X.GRUPO,
                X.DESCRICAO,
                X.STATUS,
                (X.GRUPO||' '||X.DESCRICAO) FILTRO,

                IIF( X.MAIOR_1 > MAIOR_2, IIF( X.MAIOR_1 > MAIOR_3, MAIOR_1 , MAIOR_3 ) , IIF( X.MAIOR_3 > MAIOR_3, MAIOR_3 , MAIOR_3 ) ) AS MAIOR,
                IIF( X.MENOR_1 < MENOR_2, IIF( X.MENOR_1 < MENOR_3, MENOR_1 , MENOR_3 ) , IIF( X.MENOR_3 < MENOR_3, MENOR_3 , MENOR_3 ) ) AS MENOR



            from (Select
                A.ID,
                LPAD(A.ID, 3, 0) AS MASK,
                A.GRUPO,
                A.DESCRICAO,
                A.STATUS,
                (A.GRUPO||' '||A.DESCRICAO) FILTRO,

                IIF( A.PERFIL1_A > A.PERFIL1_B, A.PERFIL1_A , A.PERFIL1_B ) AS MAIOR_1,
                IIF( A.PERFIL2_A > A.PERFIL2_B, A.PERFIL2_A , A.PERFIL2_B ) AS MAIOR_2,
                IIF( A.PERFIL3_A > A.PERFIL3_B, A.PERFIL3_A , A.PERFIL3_B ) AS MAIOR_3,

                IIF( A.PERFIL1_A < A.PERFIL1_B, A.PERFIL1_A , A.PERFIL1_B ) AS MENOR_1,
                IIF( A.PERFIL2_A < A.PERFIL2_B, A.PERFIL2_A , A.PERFIL2_B ) AS MENOR_2,
                IIF( A.PERFIL3_A < A.PERFIL3_B, A.PERFIL3_A , A.PERFIL3_B ) AS MENOR_3

            From
                    TbBsc_Indicadores A
                    WHERE A.DESCRICAO LIKE :FILTRO
                  
                    
            Order by Id
            ) X";

		$args = array(
			':FIRST'	=> $first,
			':FILTRO'	=> $filtro
		);
        
        //print_r($args);
        //print_r($sql);
        //EXIT;

		return $con->query($sql, $args);
	}
    
    /**
	 * Filtrar lista um indicador peplo controle N
	 *
	 * @param int $id
	 * @return array
	 */
	public static function indicadorcontrole($id) {
        $con = new _Conexao();
        
		$sql ="
            SELECT
                X.ID,
                X.MASK,
                X.GRUPO,
                X.DESCRICAO,
                IIF( X.MAIOR_1 > MAIOR_2, IIF( X.MAIOR_1 > MAIOR_3, MAIOR_1 , MAIOR_3 ) , IIF( X.MAIOR_3 > MAIOR_3, MAIOR_3 , MAIOR_3 ) ) AS MAIOR,
                IIF( X.MENOR_1 < MENOR_2, IIF( X.MENOR_1 < MENOR_3, MENOR_1 , MENOR_3 ) , IIF( X.MENOR_3 < MENOR_3, MENOR_3 , MENOR_3 ) ) AS MENOR
            
                from (
                
                    Select
                    A.ID,
                    LPAD(A.ID, 3, 0) AS MASK,
                    A.GRUPO,
                    A.DESCRICAO,
                    A.STATUS,

                    IIF( A.PERFIL1_A > A.PERFIL1_B, A.PERFIL1_A , A.PERFIL1_B ) AS MAIOR_1,
                    IIF( A.PERFIL2_A > A.PERFIL2_B, A.PERFIL2_A , A.PERFIL2_B ) AS MAIOR_2,
                    IIF( A.PERFIL3_A > A.PERFIL3_B, A.PERFIL3_A , A.PERFIL3_B ) AS MAIOR_3,

                    IIF( A.PERFIL1_A < A.PERFIL1_B, A.PERFIL1_A , A.PERFIL1_B ) AS MENOR_1,
                    IIF( A.PERFIL2_A < A.PERFIL2_B, A.PERFIL2_A , A.PERFIL2_B ) AS MENOR_2,
                    IIF( A.PERFIL3_A < A.PERFIL3_B, A.PERFIL3_A , A.PERFIL3_B ) AS MENOR_3

                    from TbBsc_Indicadores a, tbcontrole_n n
                    where n.id = :ID
                    and a.id = n.valor_ext

                ) X";

		$args = array(
			':ID'	=> $id
		);

        $ret = $con->query($sql, $args);
        
		return $ret;
        
	}
    
}