<?php        
		
		if ($filtro <> ''){
		$filtro = isset($filtro) ? " AND a.DESCRICAO LIKE '%" .str_replace(' ', '%', $filtro). "%'" : '%';
		}
        
		$sql = 
			"
			SELECT
			a.id AS CODIGO,
            lpad(a.id,2,0) AS MASK,
			A.DESCRICAO
			,A.DESCRICAO AS GRUPO
			,CAST( (LIST(C.GP_ID,','))AS VARCHAR(32000) CHARACTER SET WIN1252) AS ID,
			EFIC_MINIMA,
			A.CCUSTO,
			BSC_GRUPO

			FROM TBAGRUPAMENTO_GP A , TBAGRUPAMENTO_GP_DETALHE C

			 WHERE C.AGRUPAMENTO_GP_ID = A.ID
			 ".$filtro."
			 GROUP BY a.id, A.DESCRICAO,EFIC_MINIMA,CCUSTO,BSC_GRUPO
			 order by A.DESCRICAO				 
                ";

		$ret = $con->query($sql);