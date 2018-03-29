@include('helper.include.view.consulta',
	[
		'label_descricao'   => 'Produto:',
		'obj_consulta'      => 'Produto/include/_27050-filtrar-consumo-requisicao',
		'obj_ret'           => ['ID','DESCRICAO'],
		'campos_imputs'     => !empty($campos_imputs) ? $campos_imputs : '',
		'recebe_valor'		=> !empty($recebe_valor) ? $recebe_valor : [['none','none']],
		'campos_sql'        => ['ID','DESCRICAO','SALDO','UNIDADEMEDIDA_SIGLA'],
		'campos_tabela'     => [['ID','70'],['DESCRICAO','300'],['SALDO', '100'],['UNIDADEMEDIDA_SIGLA', '50']],
		'campos_titulo'     => ['Id','Descrição','Saldo','UM'],
		'class1'			=> 'input-maior',
		'selecionado'		=> !empty($selecionado) ? $selecionado : '',
		'valor'				=> !empty($valor) ? $valor : '',
		'required'			=> !empty($required) ? $required : '',
		'no_script'			=> !empty($no_script) ? $no_script : '',
		'validacao'			=> !empty($validate) ? $validate : ''
	]
)