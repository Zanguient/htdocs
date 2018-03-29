@include('helper.include.view.consulta',
	[
	  'label_descricao'   => 'Grupos de Produção',
	  'obj_consulta'      => 'helper/include/grupogp',
	  'obj_ret'           => ['ID','DESCRICAO'],
	  'campos_imputs'     => [['_id_gp','ID'],['_ccusto_gp','CCUSTO'],['_bsc_grupo_gp','BSC_GRUPO'],['_efic_gp','EFIC_MINIMA'],['_desc','DESCRICAO']],
	  'campos_sql'        => ['ID','DESCRICAO'],
	  'filtro_sql'        => ['so_ativos','so_familia3','ordenar_por_desc','sql_para_indicador'],
	  'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
	  'campos_titulo'     => ['ID','DESCRICAO'],
	  'class1'            => 'input-medio',
	  'class2'            => 'consulta_gp_grup2'
	]
)