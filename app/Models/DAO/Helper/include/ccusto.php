<?php

use App\Helpers\Helpers;

        
        $first = "";
		//$first  = " FIRST 200"; //env('NUM_REGISTROS', '30');
		$filtro = isset($filtro) ? "WHERE X.DESCRICAO LIKE '%" .str_replace(' ', '%', $filtro). "%'" : '%';
        
        $cond = '';
        if(isset($condicao)){
            $cont = 0;
            
            if (in_array("so_ativos",           $condicao))  {$cond .= " AND C.STATUS > 0 ";                       }
            if (in_array("so_inativos",         $condicao))  {$cond .= " AND C.STATUS = 0 ";                       }
            if (in_array("id_maior_q1000",      $condicao))  {$cond .= " AND C.ID > 1000 ";                        }
            if (in_array("id_menor_q1000",      $condicao))  {$cond .= " AND C.ID < 1000 ";                        }
            if (in_array("so_Fabricas",         $condicao))  {$cond .= " AND C.DESCRICAO like '%FABRICA%' ";       }
            if (in_array("reg_indicador",       $condicao))  {$cond .= " AND REG_INDICADOR = 1 ";                  }
            

            $cond = preg_replace("/AND/", "WHERE", $cond, 1);
        }
   
		$sql = 
			"
            SELECT ".$first."
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
                    
                /*FILTRO*/
                
                ORDER BY
                    1,2)X
                
                /*FILTRO2*/
                         
                ";
        
        $sql = str_replace('/*FILTRO*/', $cond, $sql);
        $sql = str_replace('/*FILTRO2*/', $filtro, $sql);

		$ret = $con->query($sql);