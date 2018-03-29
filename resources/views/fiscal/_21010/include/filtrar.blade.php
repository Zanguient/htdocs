@include('helper.include.view.consulta',
	[
		'label_descricao'   => 'Operação:',
		'obj_consulta'      => 'Fiscal/include/_21010-filtrar',
		'obj_ret'           => ['CODIGO','DESCRICAO'],
        'filtro_sql'        => !empty($filtro_sql) ? $filtro_sql : '',
		'campos_imputs'     => !empty($campos_imputs) ? $campos_imputs : '',
		'campos_sql'        => ['CODIGO','DESCRICAO'],
		'campos_tabela'     => [['CODIGO','70'],['DESCRICAO','300']],
		'campos_titulo'     => ['Cód.','Descrição'],
		'selecionado'		=> !empty($selecionado) ? $selecionado : '',
		'valor'				=> !empty($valor) ? $valor : '',
		'required'			=> !empty($required) ? $required : '',
		'no_script'			=> !empty($no_script) ? $no_script : '',
		'filtro_sql'		=> !empty($consulta_filtro) ? $consulta_filtro : '',
		'chave'				=> !empty($chave) ? $chave : '',
	]
)