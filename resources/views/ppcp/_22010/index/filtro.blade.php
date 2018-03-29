
@php $auto_load                = isset($_GET['AUTO_LOAD'])            ? 1 : 0
@php $talao_selected           = isset($_GET['TALAO_SELECTED'      ]) ? $_GET['TALAO_SELECTED'      ] : 0
@php $def_estabelecimento_id   = isset($_GET['ESTABELECIMENTO_ID'  ]) ? $_GET['ESTABELECIMENTO_ID'  ] : ''
@php $def_gp_id                = isset($_GET['GP_ID'               ]) ? $_GET['GP_ID'               ] : ''
@php $def_gp_descricao         = isset($_GET['GP_DESCRICAO'        ]) ? $_GET['GP_DESCRICAO'        ] : ''
@php $def_gp_pecas_disponiveis = isset($_GET['GP_PECAS_DISPONIVEIS']) ? $_GET['GP_PECAS_DISPONIVEIS'] : 0
@php $def_perfil               = isset($_GET['PERFIL_UP'           ]) ? $_GET['PERFIL_UP'           ] : ''
@php $def_perfil_descricao     = isset($_GET['PERFIL_UP_DESCRICAO' ]) ? $_GET['PERFIL_UP_DESCRICAO' ] : ''
@php $def_up_id                = isset($_GET['UP_ID'               ]) ? $_GET['UP_ID'               ] : ''
@php $def_up_descricao         = isset($_GET['UP_DESCRICAO'        ]) ? $_GET['UP_DESCRICAO'        ] : ''
@php $def_estacao              = isset($_GET['ESTACAO'             ]) ? $_GET['ESTACAO'             ] : ''
@php $def_estacao_descricao    = isset($_GET['ESTACAO_DESCRICAO'   ]) ? $_GET['ESTACAO_DESCRICAO'   ] : ''
@php $def_up_origem            = isset($_GET['UP_ORIGEM'           ]) ? $_GET['UP_ORIGEM'           ] : ''
@php $def_up_origem_descricao  = isset($_GET['UP_ORIGEM_DESCRICAO' ]) ? $_GET['UP_ORIGEM_DESCRICAO' ] : ''
@php $def_turno                = isset($_GET['TURNO'               ]) ? $_GET['TURNO'               ] : ''
@php $def_ver_pares            = isset($_GET['VER_PARES'           ]) ? $_GET['VER_PARES'           ] : ''
@php $def_gp_remessa_dias      = isset($_GET['GP_REMESSA_DIAS'     ]) ? $_GET['GP_REMESSA_DIAS'     ] : 0

<div id="programacao-filtro" class="table-filter collapse in" aria-expanded="true" ng-init="
    vm.Filtro.GUIA_ATIVA      = 'TALAO_PRODUZIR'; 
    vm.Filtro.AUTO_LOAD       = {{ $auto_load }}; 
    vm.Filtro.VER_PARES       = {{ $ver_pares }}; 
    vm.Filtro.TALAO_SELECTED  = {{ $talao_selected }};
    vm.Filtro.GP_REMESSA_DIAS = {{ $def_gp_remessa_dias }};
">
   
    
	{{-- Estabelecimento --}}
    @include('admin._11020.include.listar', [
        'required'			=> 'required',
        'autofocus'			=> 'autofocus',
        'opcao_selec'		=> 'true',
        'model'             => 'vm.Filtro.ESTABELECIMENTO_ID',
        'model_descricao'   => 'vm.Filtro.ESTABELECIMENTO_DESCRICAO',
        'estab_cadastrado'	=> $auto_load && $def_estabelecimento_id ? $def_estabelecimento_id : ''
    ])    

	{{-- GP --}}
	@include('helper.include.view.consulta',
		[
            'label_descricao'   => 'GP:',
            'obj_consulta'      => 'Ppcp/include/_22030-gp',
            'obj_ret'           => ['ID','DESCRICAO'],
            'campos_sql'        => ['ID','DESCRICAO','PERFIL','DIAS','VER_PECA_DISPONIVEL','VER_PARES'],
            'campos_imputs'     => [
                  ['_gp_id'       ,       'ID', $def_gp_id       , 'vm.Filtro.GP_ID'       ],
                  ['_gp_descricao','DESCRICAO', $def_gp_descricao, 'vm.Filtro.GP_DESCRICAO']
            ],
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
            ],
            'selecionado'	=> $auto_load && $def_gp_id,
            'valor'	        => $auto_load && $def_gp_id ? $def_gp_id . ' - ' . $def_gp_descricao : ''
		]
	)

	{{-- Perfil de UP --}}
	@include('helper.include.view.consulta',
		[
            'label_descricao'   => 'Perfil UP:',
            'obj_consulta'      => 'Admin/include/_11030-por-tabela',
            'obj_ret'           => ['ID','DESCRICAO'],
            'campos_sql'        => ['ID','DESCRICAO'],
            'campos_imputs'     => [
                  ['_perfil_up_id'       ,'ID'       ,$def_perfil,'vm.Filtro.PERFIL_UP'       ],
                  ['_perfil_up_descricao','DESCRICAO',$def_perfil_descricao,'vm.Filtro.PERFIL_UP_DESCRICAO']
             ],
            'filtro_sql'        => [
                                      ['GP',$def_gp_id],
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
            'no_script'         => true,
            'selecionado'	=> $auto_load && $def_perfil,
            'valor'	        => $auto_load && $def_perfil ? $def_perfil . ' - ' . $def_perfil_descricao : ''
		]
	)

	{{-- UP --}}
	@include('helper.include.view.consulta',
        [
            'label_descricao'   => 'UP:',
            'obj_consulta'      => 'Ppcp/include/_22030-up',
            'obj_ret'           => ['UP_ID','UP_DESCRICAO'],
            'campos_sql'        => ['UP_ID','UP_DESCRICAO'],
            'campos_imputs'     => [
                  ['_up_id'       ,'UP_ID'       ,$def_up_id,'vm.Filtro.UP_ID'       ],
                  ['_up_descricao','UP_DESCRICAO',$def_up_descricao,'vm.Filtro.UP_DESCRICAO']
              ],
            'filtro_sql'        => [
                                      ['PERFIL_UP',$def_perfil],
                                      ['GP',$def_gp_id],
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
            'no_script'         => true,
            'selecionado'	=> $auto_load && $def_up_id,
            'valor'	        => $auto_load && $def_up_id ? $def_up_id . ' - ' . $def_up_descricao : ''
		]
	)

	{{-- Estação --}}
	@include('helper.include.view.consulta',
		[
            'label_descricao'   => 'Estação:',
            'obj_consulta'      => 'Ppcp/include/_22030-estacao',
            'obj_ret'           => ['ID','DESCRICAO'],
            'campos_sql'        => ['ID','DESCRICAO'],
            'campos_imputs'     => [
                  ['_estacao_id'       ,'ID'       ,$def_estacao,'vm.Filtro.ESTACAO'          ],
                  ['_estacao_descricao','DESCRICAO',$def_estacao_descricao,'vm.Filtro.ESTACAO_DESCRICAO']
              ],
            'filtro_sql'        => [
                                      ['UP',$def_up_id],
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
            'no_script'         => true,
            'selecionado'	=> $auto_load && $def_estacao,
            'valor'	        => $auto_load && $def_estacao ? $def_estacao . ' - ' . $def_estacao_descricao : ''
		]
	)
	
	{{-- UP origem --}}
	@include('helper.include.view.consulta',
		[
            'label_descricao'	=> 'UP origem:',
            'obj_consulta'	=> 'Ppcp/include/_22030-up',
            'obj_ret'			=> ['UP_ID','UP_DESCRICAO'],
            'campos_sql'		=> ['UP_ID','UP_DESCRICAO'],
            'campos_imputs'	=> [
                  ['_up_origem_id'       ,'UP_ID'       ,$def_up_origem,'vm.Filtro.UP_ORIGEM'          ],
                  ['_up_origem_descricao','UP_DESCRICAO',$def_up_origem_descricao,'vm.Filtro.UP_ORIGEM_DESCRICAO']
              ],
            'filtro_sql'		=> [
                                  ['STATUS','1'],
                                  ['ORDER','UP_DESCRICAO,UP_ID']
                              ],
            'campos_tabela'	=> [['UP_ID','80'],['UP_DESCRICAO','200']],
            'campos_titulo'	=> ['ID','DESCRIÇÃO'],
            'class1'			=> 'input-medio',
            'class2'			=> 'consulta_up_origem_group',
            'no_script'		=> true,
            'selecionado'	=> $auto_load && $def_up_origem,
            'valor'	        => $auto_load && $def_up_origem ? $def_up_origem . ' - ' . $def_up_origem_descricao : ''
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
		'opcao_todos_selecionada'	=> 'true',
        'model'                     => 'vm.Filtro.TURNO',
        'model_descricao'           => 'vm.Filtro.TURNO_DESCRICAO',
        'turno_cadastrado'          => $auto_load && $def_turno ? $def_turno : ''
	])

    <button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
		<span class="glyphicon glyphicon-filter"></span>
		{{ Lang::get('master.filtrar') }}
	</button>
	
	<input type="hidden" class="_perfil-gp" />
	<input type="hidden" class="_dias-gp" ng-model="vm.Filtro.GP_REMESSA_DIAS" ng-update-hidden value="{{ $def_gp_remessa_dias }}" />
	<input type="hidden" class="_ver-peca-disponivel-gp" ng-model="vm.Filtro.GP_PECAS_DISPONIVEIS" ng-update-hidden value="{{ $def_gp_pecas_disponiveis }}" />
    <input type="hidden" class="_ver-pares-gp" ng-model="vm.Filtro.VER_PARES" ng-update-hidden value="{{ $def_ver_pares }}" />
	
</div>