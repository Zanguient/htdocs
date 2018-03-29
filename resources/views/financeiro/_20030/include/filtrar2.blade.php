@include('helper.include.view.consulta',
	[
		'label_descricao'   => 'Centro de custo:',
		'obj_consulta'      => 'Financeiro/include/_20030-filtrar2',
		'obj_ret'           => ['ID','DESCRICAO'],
		'campos_imputs'     => !empty($campos_imputs) ? $campos_imputs : '',
		'campos_sql'        => ['ID','DESCRICAO'],
		'campos_tabela'     => [['ID','90'],['DESCRICAO','400']],
		'campos_titulo'     => ['Id','Descrição'],
		'selecionado'		=> !empty($selecionado) ? $selecionado : '',
		'valor'				=> !empty($valor) ? $valor : '',
		'autofocus'			=> !empty($autofocus) ? $autofocus : '',
		'required'			=> !empty($required) ? $required : '',
        'class1'             => !empty($class) ? $class : ''
	]
)