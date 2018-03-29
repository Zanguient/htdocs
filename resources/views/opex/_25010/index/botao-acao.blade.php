<ul class="list-inline acoes">

	<li>
		<button 
			type="button" 
			class="btn btn-primary" 
			data-hotkey="f6" 
			data-toggle="modal" 
			data-target="#modal-create"
			ng-click="
				vm.tipoTela = 'incluir';
				vm.somarNota();"
			ng-disabled="vm.permissao.INCLUIR == '0'"
		>
			<span class="glyphicon glyphicon-plus"></span> 
			{{ Lang::get('master.incluir') }}
		</button>
	</li>
	<li>
		<button 
			type="button" 
			class="btn btn-danger"
			data-hotkey="f12"
			ng-click="vm.excluirFormularioSelec(vm.listaFormularioSelec)"
			ng-disabled="vm.listaFormularioSelec.length == 0 || vm.permissao.EXCLUIR == '0'"
		>
			<span class="glyphicon glyphicon-trash"></span> 
			{{ Lang::get('master.excluir') }}
		</button>
	</li>
	<li>
		<button 
			type="button" 
			class="btn btn-default"
			data-hotkey="alt+e"
			data-toggle="modal" 
			data-target="#modal-painel"
			ng-click="vm.verPainel(vm.listaFormularioSelec); $emit('vsRepeatTrigger'); vm.fixVsRepeat()"
			ng-disabled="vm.listaFormularioSelec.length != 1"
		>
			<span class="glyphicon glyphicon-stats"></span> 
			{{ Lang::get($menu.'.button-painel') }}
		</button>
	</li>

</ul>

<div class="pesquisa-obj-container">

	<div class="input-group input-group-filtro-obj">

		<input 
			type="search" 
			class="form-control filtro-obj" 
			placeholder="{{ Lang::get('master.pesq-place') }}" 
			autocomplete="off"
			autofocus
			ng-model="vm.filtrarFormulario" 
			ng-init="vm.filtrarFormulario = ''" 
		/>

		<button type="button" class="input-group-addon btn-filtro btn-filtro-obj" tabindex="-1">
			<span class="fa fa-search"></span>
		</button>

	</div>

</div>