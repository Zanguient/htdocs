<form ng-submit="vm.Acoes.filtrar()">

<div id="programacao-filtro" class="table-filter collapse in" aria-expanded="true">
		
		@if($selecionado ==1)
			{{-- Estabelecimento --}}
			@include('admin._11020.include.listar', [
				'required'			=> 'required',
				'autofocus'			=> 'autofocus',
				'opcao_selec'		=> 'true',
				'model'				=> 'vm.FILTRO.ESTABELECIMENTO',
				'estab_cadastrado'	=> $ESTAB
			])
		@else
			{{-- Estabelecimento --}}
			@include('admin._11020.include.listar', [
				'required'			=> 'required',
				'autofocus'			=> 'autofocus',
				'opcao_selec'		=> 'true',
				'model'				=> 'vm.FILTRO.ESTABELECIMENTO'
			])
		@endif

		{{-- GP --}}
		@include('helper.include.view.consulta',
			[
			  'label_descricao'   => 'GP:',
			  'obj_consulta'      => 'Ppcp/include/_22030-gp',
			  'obj_ret'           => ['ID','DESCRICAO'],
			  'campos_sql'        => ['ID','DESCRICAO','PERFIL','DIAS','VER_PECA_DISPONIVEL','VER_PARES'],
			  'campos_imputs'     => [['_gp_id','ID',$GP,'vm.FILTRO.GP_ID'],['_gp_descricao','DESCRICAO',$GPDESC,'vm.FILTRO.GP_DESCRICAO']],
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
				['_ver-pares-gp', 'VER_PARES'],
			  ],
			  'selecionado'	=> $selecionado,
			  'valor'	    => ($selecionado == 1) ? ($GP.' - '.$GPDESC) : ''
			]
		)

		{{-- UP --}}
		@include('helper.include.view.consulta',
			[
			  'label_descricao'   => 'UP:',
			  'obj_consulta'      => 'Ppcp/include/_22030-up',
			  'obj_ret'           => ['UP_ID','UP_DESCRICAO'],
			  'campos_sql'        => ['UP_ID','UP_DESCRICAO'],
			  'campos_imputs'     => [['_up_id','UP_ID',$UP,'vm.FILTRO.UP_ID'],['_up_descricao','UP_DESCRICAO',$UPDESC,'vm.FILTRO.UP_DESCRICAO']],
			  'filtro_sql'        => [
										['GP','','vm.FILTRO.GP_ID'],
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
			  'required'		  => 'required',
			  'no_script'         => true,
			  'selecionado'		  => $selecionado,
			  'valor'	    	  => ($selecionado ==1) ? ($UP.' - '.$UPDESC) : ''
			]
		)

		{{-- Estação --}}
		@include('helper.include.view.consulta',
			[
			  'label_descricao'   => 'Agrupamento de Estações:',
			  'obj_consulta'      => 'Ppcp/include/_22030-conformacao',
			  'obj_ret'           => ['ID','DESCRICAO'],
			  'campos_sql'        => ['ID','DESCRICAO','IDS'],
			  'campos_imputs'     => [['_estacao_id','IDS',$ESTACAO,'vm.FILTRO.ESTACAO_ID'],['_estacao_descricao','DESCRICAO',$ESTACAODESC,'vm.FILTRO.ESTACAO_DESCRICAO']],
			  'filtro_sql'        => [
										['UP','0','vm.FILTRO.UP_ID'],
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
			  'selecionado'		  => $selecionado,
			  'valor'	    	  => ($selecionado ==1) ? ($UP.' - '.$ESTACAODESC) : ''
			]
		)
		
		<div style="display: none;">
		{{-- UP origem --}}
		@include('helper.include.view.consulta',
			[
			  'label_descricao'	=> 'UP origem:',
			  'obj_consulta'	=> 'Ppcp/include/_22030-up',
			  'obj_ret'			=> ['UP_ID','UP_DESCRICAO'],
			  'campos_sql'		=> ['UP_ID','UP_DESCRICAO'],
			  'campos_imputs'	=> [['_up_origem_id','UP_ID','','vm.FILTRO.UPO_ID'],['_up_origem_descricao','UP_DESCRICAO','','vm.FILTRO.UPO_DESCRICAO']],
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
		</div>
		
		<div class="form-group filtro-periodo">
			<label>Período Produção:</label>
			<input type="date" ng-disabled="vm.FILTRO.FLAG_DATA" ng-model="vm.FILTRO.DATA_INICIAL" class="form-control data-ini" value="{{ date('Y-m-d') }}" />
			<label class="periodo-a"> {{ Lang::get('master.periodo-a') }} </label>
			<input type="date" ng-disabled="vm.FILTRO.FLAG_DATA" ng-model="vm.FILTRO.DATA_FINAL" class="form-control data-fim" value="{{ date('Y-m-d') }}" />
			<input type="checkbox" ng-model="vm.FILTRO.FLAG_DATA" id="periodo-todos" class="form-control periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}" checked />
			<label style="margin-left: 4px;" for="periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}"> Hoje</label>
		</div>

		<div class="form-group filtro-periodo">
			<label>Período Meta:</label>
			<input type="date" ng-disabled="vm.FILTRO.FLAG_DATA2" ng-model="vm.FILTRO.DATA_INICIAL2" class="form-control data-ini" value="{{ date('Y-m-d') }}" />
			<label class="periodo-a"> {{ Lang::get('master.periodo-a') }} </label>
			<input type="date" ng-disabled="vm.FILTRO.FLAG_DATA2" ng-model="vm.FILTRO.DATA_FINAL2" class="form-control data-fim" value="{{ date('Y-m-d') }}" />
			<input type="checkbox" ng-model="vm.FILTRO.FLAG_DATA2" id="periodo-todos" class="form-control periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}" checked />
			<label style="margin-left: 4px;" for="periodo-todos" title="{{ Lang::get($menu.'.periodo-todos-title') }}"> Hoje</label>
		</div>
		
		<button type="submit" class="btn btn-xs btn-primary btn-filtrar" data-hotkey="alt+f" id="btn-table-filter">
			<span class="glyphicon glyphicon-filter"></span>
			{{ Lang::get('master.filtrar') }}
		</button>
		
		<input type="hidden" class="_perfil-gp" />
		<input type="hidden" class="_dias-gp" />
		<input type="hidden" class="_ver-peca-disponivel-gp" />
		<input type="hidden" class="_ver-pares-gp" />

		<input type="hidden" class="_auto_filtro" value="{{$selecionado}}" />

		<input type="hidden" class="_auto_gp_id" 				value="{{$GP}}" />
		<input type="hidden" class="_auto_up_id" 				value="{{$UP}}" />
		<input type="hidden" class="_auto_estacao_id" 			value="{{$ESTACAO}}" />
		<input type="hidden" class="_auto_gp_descricao" 		value="{{$GPDESC}}" />
		<input type="hidden" class="_auto_up_descricao" 		value="{{$UPDESC}}" />
		<input type="hidden" class="_auto_estacao_descricao" 	value="{{$ESTACAODESC}}" />

	</div>
</form>	

