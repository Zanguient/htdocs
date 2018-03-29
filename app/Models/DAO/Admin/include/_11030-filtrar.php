<?php  
        $first = "";
		$filtro = isset($filtro) ? " AND P.DESCRICAO LIKE '%" .str_replace(' ', '%', $filtro). "%'" : '%';
        
        $cond   = '';
        $ordem  = '';
        $campos = '';
        
        if(isset($condicao)){
            $cont = 0;
            
            //filtro
            if (in_array("so_pa",          $condicao))  {$cond .= " AND P.TABELA = 'PA'"; }
            if (in_array("so_gp",          $condicao))  {$cond .= " AND P.TABELA = 'GP'"; }
            if (in_array("so_sku",         $condicao))  {$cond .= " AND P.TABELA = 'SKU'";}
            if (in_array("so_ped",         $condicao))  {$cond .= " AND P.TABELA = 'PED'";}
            if (in_array("so_cor",         $condicao))  {$cond .= " AND P.TABELA = 'COR'";}
            if (in_array("so_mod",         $condicao))  {$cond .= " AND P.TABELA = 'MOD'";}
            
            //ordem
            if (in_array("ord_por_desc",   $condicao))  {$ordem = " ORDER BY 2";}
            if (in_array("ord_por_cod",    $condicao))  {$ordem = " ORDER BY 1";}
            
            //campos
            if (in_array("sql_para_pa",     $condicao))  {$campos = " lpad(p.id,2,'0') as MASK, P.ID, P.DESCRICAO "; }
            if (in_array("sql_todos_campos",$condicao))  {$campos = " * ";              }

            $cond = preg_replace("/AND/", "WHERE", $cond.$filtro, 1);
        }
   
        
		$sql = 
			"SELECT
                
                /*FIRST*/
                
                /*CAMPOS*/
            
             FROM TBPERFIL P
                
                /*FILTRO*/
                
                /*ORDEM*/
                         
                ";
        
        $sql = str_replace('/*FIRST*/' , $first , $sql);
        $sql = str_replace('/*FILTRO*/', $cond	, $sql);
        $sql = str_replace('/*ORDEM*/' , $ordem , $sql);
        $sql = str_replace('/*CAMPOS*/', $campos, $sql);

		$ret = $con->query($sql);