@include('helper.include.view.consulta',
		[
		  'label_descricao'   => 'Grupos de Produção',
		  'obj_consulta'      => 'helper/include/gp',
		  'obj_ret'           => ['MASC','DESC'],
		  'campos_imputs'     => [['_id_gp','CODE'],['_ccusto_gp','CCUSTO'],['_bsc_grupo_gp','BSC_GRUPO'],['_efic_gp','EFIC_MINIMA'],['_desc','DESC']],
		  'campos_sql'        => ['CODE','DESC'],
		  'filtro_sql'        => ['so_ativos','so_familia3','ordenar_por_desc','sql_para_indicador'],
		  'campos_tabela'     => [['MASC','80'],['DESC','200']],
		  'campos_titulo'     => ['ID','DESCRIÇÃO'],
		  'class1'            => 'input-medio',
		  'class2'            => 'consulta_gp_grup'
		]
	)