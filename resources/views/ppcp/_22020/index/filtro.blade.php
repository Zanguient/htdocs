<div id="programacao-filtro" class="table-filter collapse in" aria-expanded="true">

	{{-- Estabelecimento --}}
	@include('admin._11020.include.listar', [
		'required'			=> 'required',
		'autofocus'			=> 'autofocus',
		'opcao_selec'		=> 'true'
	])

	{{-- GP --}}
	@include('helper.include.view.consulta',
		[
		  'label_descricao'   => 'GP:',
		  'obj_consulta'      => 'Ppcp/include/_22030-gp',
		  'obj_ret'           => ['ID','DESCRICAO'],
		  'campos_sql'        => ['ID','DESCRICAO','PERFIL','DIAS','VER_PECA_DISPONIVEL','VER_PARES'],
		  'campos_imputs'     => [['_gp_id','ID'],['_gp_descricao','DESCRICAO']],
		  'filtro_sql'        => [
									['STATUS','1'],
									['ORDER','DESCRICAO,ID']
								 ],
		  'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
		  'campos_titulo'     => ['ID','DESCRIÇÃO'],
		  'class1'            => 'input-medio',
		  'class2'            => 'consulta_gp_grup',
		  'required'		  => 'required',
		  'recebe_valor'	  => [
			['_perfil-gp', 'PERFIL'],
			['_dias-gp', 'DIAS'],
			['_ver-peca-disponivel-gp', 'VER_PECA_DISPONIVEL'],
			['_ver-pares-gp', 'VER_PARES']
		  ]
		]
	)

	{{-- Perfil de UP --}}
	@include('helper.include.view.consulta',
		[
		  'label_descricao'   => 'Perfil UP:',
		  'obj_consulta'      => 'Admin/include/_11030-por-tabela',
		  'obj_ret'           => ['ID','DESCRICAO'],
		  'campos_sql'        => ['ID','DESCRICAO'],
		  'campos_imputs'     => [['_perfil_up_id','ID'],['_perfil_up_descricao','DESCRICAO']],
		  'filtro_sql'        => [
		  							['GP','0'],
									['TABELA','UP'],
									['ORDER','DESCRICAO,ID']
								 ],
		  'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
		  'campos_titulo'     => ['ID','DESCRIÇÃO'],
		  'class1'            => 'input-medio',
		  'class2'            => 'consulta_perfil_up_group',
		  'opcao_todos'		  => 'true',
		  'recebe_todos'	  => ['up_todos'],
		  'class_get_todos'	  => '_perfil_up_todos',
		  'required'		  => 'required',
		  'no_script'         => true
		]
	)

	{{-- UP --}}
	@include('helper.include.view.consulta',
		[
		  'label_descricao'   => 'UP:',
		  'obj_consulta'      => 'Ppcp/include/_22030-up',
		  'obj_ret'           => ['UP_ID','UP_DESCRICAO'],
		  'campos_sql'        => ['UP_ID','UP_DESCRICAO'],
		  'campos_imputs'     => [['_up_id','UP_ID'],['_up_descricao','UP_DESCRICAO']],
		  'filtro_sql'        => [
									['PERFIL_UP','0'],
									['GP','0'],
									['STATUS','1'],
									['ORDER','UP_DESCRICAO,UP_ID']
								 ],
		  'campos_tabela'     => [['UP_ID','80'],['UP_DESCRICAO','200']],
		  'campos_titulo'     => ['ID','DESCRIÇÃO'],
		  'class1'            => 'input-medio',
		  'class2'            => 'consulta_up_group',
		  'opcao_todos'		  => 'true',
		  'recebe_todos'	  => ['estacao_up_todos'],
		  'class_get_todos'	  => '_up_todos',
		  'class_set_todos'	  => 'up_todos',
		  'required'		  => 'required',
		  'no_script'         => true
		]
	)

	{{-- Estação --}}
	@include('helper.include.view.consulta',
		[
		  'label_descricao'   => 'Estação:',
		  'obj_consulta'      => 'Ppcp/include/_22030-estacao',
		  'obj_ret'           => ['ID','DESCRICAO'],
		  'campos_sql'        => ['ID','DESCRICAO'],
		  'campos_imputs'     => [['_estacao_id','ID'],['_estacao_descricao','DESCRICAO']],
		  'filtro_sql'        => [
									['UP','0'],
									['STATUS','1'],
									['ORDER','DESCRICAO,ID']
								 ],
		  'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
		  'campos_titulo'     => ['ID','DESCRIÇÃO'],
		  'class1'            => 'input-medio',
		  'class2'            => 'consulta_estacao_group',
		  'required'		  => 'required',
		  'class_get_todos'	  => '_estacao_todos',
		  'class_set_todos'	  => 'estacao_up_todos',
		  'no_script'         => true
		]
	)
	
	{{-- UP origem --}}
	@include('helper.include.view.consulta',
		[
		  'label_descricao'	=> 'UP origem:',
		  'obj_consulta'	=> 'Ppcp/include/_22030-up',
		  'obj_ret'			=> ['UP_ID','UP_DESCRICAO'],
		  'campos_sql'		=> ['UP_ID','UP_DESCRICAO'],
		  'campos_imputs'	=> [['_up_origem_id','UP_ID'],['_up_origem_descricao','UP_DESCRICAO']],
		  'filtro_sql'		=> [
								['STATUS','1'],
								['ORDER','UP_DESCRICAO,UP_ID']
							],
		  'campos_tabela'	=> [['UP_ID','80'],['UP_DESCRICAO','200']],
		  'campos_titulo'	=> ['ID','DESCRIÇÃO'],
		  'class1'			=> 'input-medio',
		  'class2'			=> 'consulta_up_origem_group',
		  'no_script'		=> true
		]
	)

	<div class="form-group filtro-remessa">
		<label>{{ Lang::get($menu.'.remessa') }}:</label>
		<input type="text" id="remessa" />
	</div>
	
	<br />
	
	<div class="form-group filtro-periodo">
		<label>{{ Lang::get('master.periodo') }}:</label>
		<input type="date" class="form-control data-ini" value="{{ date('Y-m-d') }}" />
		<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
		<input type="date" class="form-control data-fim" value="{{ date('Y-m-d') }}" />
		<input type="checkbox" id="periodo-todos" class="form-control periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}" checked />
		<label for="periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}">{{ Lang::get('master.todos') }}</label>
	</div>
	
	{{-- Turno --}}
	@include('pessoal._23010.include.listar', [
		'required'					=> 'required',
		'opcao_selec'				=> 'true',
		'opcao_todos'				=> 'true',
		'opcao_todos_selecionada'	=> 'true'
	])

	<button type="button" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>
	
	<input type="hidden" class="_perfil-gp" />
	<input type="hidden" class="_dias-gp" />
	<input type="hidden" class="_ver-peca-disponivel-gp" />
	<input type="hidden" class="_ver-pares-gp" />
	
</div>