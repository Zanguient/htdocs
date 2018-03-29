<fieldset ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-ccusto') }}</legend>

	<div class="button-container">

		<button 
			type="button" 
			class="btn btn-sm btn-primary incluir-item" 
			ng-click="$ctrl.CreateCCusto.exibirModal()" 
			data-hotkey="f1">
			
			<span class="glyphicon glyphicon-plus"></span> 
			{{ Lang::get('master.incluir') }}
		</button>

		<button 
			type="button" 
			class="btn btn-sm btn-danger excluir-item"
			ng-click="$ctrl.CreateCCusto.excluirCCustoEscolhido()"
			data-hotkey="f2">

			<span class="glyphicon glyphicon-trash"></span> 
			{{ Lang::get('master.excluir') }}
		</button>
		
	</div>

	<div class="row">

		@include('pessoal._23036.create.body-base-ccusto-table')

	</div>

</fieldset>