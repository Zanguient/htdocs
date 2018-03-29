@include('helper.include.view.consulta',
	[
		'label_descricao'   => 'Área BSC:',
		'obj_consulta'      => 'Opex/include/_25900-Area',
		'obj_ret'           => ['MASC','DESCRICAO'],
		'campos_imputs'     => !empty($campos_imputs) ? $campos_imputs : '',
		'campos_sql'        => ['ID','GRUPO_ID','MASC','DESCRICAO'],
		'campos_tabela'     => [['MASC','50'],['DESCRICAO','300']],
		'campos_titulo'     => ['Id','Descrição'],
		'selecionado'		=> !empty($selecionado) ? $selecionado : '',
		'valor'				=> !empty($valor) ? $valor : '',
		'autofocus'			=> !empty($autofocus) ? $autofocus : '',
		'required'			=> !empty($required) ? $required : '',
        'validacao'         => !empty($validacao) ? $validacao : ''
	]
    
)