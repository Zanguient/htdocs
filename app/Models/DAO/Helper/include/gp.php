<?php  
        $first = "";
		//$first  = " FIRST 200"; //env('NUM_REGISTROS', '30');
		$filtro = isset($filtro) ? " AND E.DESCRICAO LIKE '%" .str_replace(' ', '%', $filtro). "%'" : '%';
        
        $cond   = '';
        $ordem  = '';
        $campos = '';
        
        if(isset($condicao)){
            $cont = 0;
            
            //filtro
            if (in_array("so_ativos",           $condicao))  {$cond .= " AND E.STATUS = 1 ";                       }
            if (in_array("so_inativos",         $condicao))  {$cond .= " AND E.STATUS = 0 ";                       }
            if (in_array("so_familia3",         $condicao))  {$cond .= " AND E.FAMILIA_CODIGO = 3";                }
            
            //ordem
            if (in_array("ordenar_por_desc",    $condicao))  {$ordem = " ORDER BY 3";                              }
            if (in_array("ordenar_por_cod",     $condicao))  {$ordem = " ORDER BY 1";                              }
            
            //campos
            if (in_array("sql_para_indicador",  $condicao))  {$campos = " E.CODIGO as CODE,lpad(E.codigo,4,0) AS MASC, E.DESCRICAO as DESC, E.TONALIDADE_REMESSA AS GRUPO, CCUSTO, BSC_GRUPO, EFIC_MINIMA";}
            if (in_array("sql_todos_campos",    $condicao))  {$campos = " * ";                                     }
            

            $cond = preg_replace("/AND/", "WHERE", $cond.$filtro, 1);
        }
   
        
		$sql = 
			"SELECT
                
                /*FIRST*/
                
                /*CAMPOS*/
            
             FROM TBESTEIRA E
                
                /*FILTRO*/
                
                /*ORDEM*/
                         
                ";
        
        $sql = str_replace('/*FIRST*/' , $first         , $sql);
        $sql = str_replace('/*FILTRO*/', $cond		    , $sql);
        $sql = str_replace('/*ORDEM*/' , $ordem         , $sql);
        $sql = str_replace('/*CAMPOS*/', $campos        , $sql);

		$ret = $con->query($sql);