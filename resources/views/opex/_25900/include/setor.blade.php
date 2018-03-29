@include('helper.include.view.consulta',
	[
		'label_descricao'   => 'Setor:',
		'obj_consulta'      => 'Opex/include/_25900-Setor',
		'obj_ret'           => ['MASC','DESCRICAO'],
		'campos_imputs'     => !empty($campos_imputs) ? $campos_imputs : '',
		'campos_sql'        => ['ID','MASC','DESCRICAO'],
		'campos_tabela'     => [['MASC','50'],['DESCRICAO','200']],
		'campos_titulo'     => ['Id','Descrição'],
		'selecionado'		=> !empty($selecionado) ? $selecionado : '',
		'valor'				=> !empty($valor) ? $valor : '',
		'autofocus'			=> !empty($autofocus) ? $autofocus : '',
		'required'			=> !empty($required) ? $required : ''
	]
    
)