<?php  
        $first = "";
		
        $filtro1 = isset($filtro) ? " OR P.DESCRICAO LIKE '%" .str_replace(' ', '%', $filtro). "%'" : '%';
        $filtro2 = isset($filtro) ? " OR P.GRUPO LIKE '%" .str_replace(' ', '%', $filtro). "%'" : '%';
        $filtro3 = isset($filtro) ? " OR P.ID LIKE '%" .str_replace(' ', '%', $filtro). "%'" : '%';
        
        $filtro  = " AND P.ID > 0 ".$filtro1.$filtro2.$filtro3;
        
        $cond   = '';
        $ordem  = '';
        $campos = '';
        
        if(isset($condicao)){
            $cont = 0;
            
            //filtro
            if (in_array("so_web",         $condicao))  {$cond .= " AND P.grupo = 'WEB'"; }
            if (in_array("so_geral",       $condicao))  {$cond .= " AND P.grupo = 'GERAL'"; }
            if (in_array("so_bojo",        $condicao))  {$cond .= " AND P.grupo = 'BOJO'"; }
            
            //ordem
            if (in_array("ord_por_desc",   $condicao))  {$ordem = " ORDER BY 4";}
            if (in_array("ord_por_cod",    $condicao))  {$ordem = " ORDER BY 1";}
            
            //campos
            if (in_array("sql_todos_campos",$condicao))  {$campos = " lpad(P.id,4,'0') as MASK,P.* ";}

            $cond = preg_replace("/AND/", "WHERE", $cond.$filtro, 1);
        }
   
		$sql = 
			"SELECT
                
                /*FIRST*/
                
                /*CAMPOS*/
            
             FROM tbbsc_indicadores P
                
                /*FILTRO*/
                
                /*ORDEM*/
                         
                ";
        
        $sql = str_replace('/*FIRST*/' , $first , $sql);
        $sql = str_replace('/*FILTRO*/', $cond	, $sql);
        $sql = str_replace('/*ORDEM*/' , $ordem , $sql);
        $sql = str_replace('/*CAMPOS*/', $campos, $sql);

		$ret = $con->query($sql);