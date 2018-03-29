@include('helper.include.view.consulta',
	[
		'label_descricao'   => 'Indicador:',
		'obj_consulta'      => 'Opex/include/_25500-filtrar',
		'obj_ret'           => ['ID','DESCRICAO'],
        'filtro_sql'        => !empty($filtro_sql) ? $filtro_sql : '',
		'campos_imputs'     => !empty($campos_imputs) ? $campos_imputs : '',
		'campos_sql'        => ['ID','GRUPO','DESCRICAO'],
		'campos_tabela'     => [['MASK','90'],['GRUPO','120'],['DESCRICAO','300']],
		'campos_titulo'     => ['Id','Grupo','Descrição'],
		'selecionado'		=> !empty($selecionado) ? $selecionado : '',
		'valor'				=> !empty($valor) ? $valor : '',
		'autofocus'			=> !empty($autofocus) ? $autofocus : '',
		'required'			=> !empty($required) ? $required : '',
        'readonly'          => !empty($readonly) ? $readonly : '',
        'class1'             => !empty($class) ? $class : ''
	]
)