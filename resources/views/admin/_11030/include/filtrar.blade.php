@include('helper.include.view.consulta',
	[
		'label_descricao'   => 'Perfil:',
		'obj_consulta'      => 'Admin/include/_11030-filtrar',
		'obj_ret'           => ['ID','DESCRICAO'],
        'filtro_sql'        => !empty($filtro_sql) ? $filtro_sql : '',
		'campos_imputs'     => !empty($campos_imputs) ? $campos_imputs : '',
		'campos_sql'        => ['ID','DESCRICAO'],
		'campos_tabela'     => [['MASK','90'],['DESCRICAO','250']],
		'campos_titulo'     => ['Id','Descrição'],
		'selecionado'		=> !empty($selecionado) ? $selecionado : '',
		'valor'				=> !empty($valor) ? $valor : '',
		'autofocus'			=> !empty($autofocus) ? $autofocus : '',
		'required'			=> !empty($required) ? $required : '',
        'readonly'          => !empty($readonly) ? $readonly : '',
	]
)