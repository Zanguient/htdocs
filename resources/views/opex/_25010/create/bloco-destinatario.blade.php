<fieldset 
	ng-disabled="vm.tipoTela == 'exibir'"
	ng-if="vm.formulario.TIPO != '3'"
>

	<legend>{{ Lang::get($menu.'.legend-destinatario') }}</legend>

	<div class="button-container">

		<div class="radio-group">

			<input type="radio" name="destinatario_tipo_usuario" id="destinatario_tipo_usuario" value="usuario"
				ng-model="vm.formulario.DESTINATARIO_TIPO" ng-change="vm.alterarDestinatarioTipo()" ng-checked="true" 
			/>
			<label for="destinatario_tipo_usuario">{{ Lang::get($menu.'.radio-usuario') }}</label>
			
			<input type="radio" name="destinatario_tipo_ccusto" id="destinatario_tipo_ccusto" value="ccusto"
				ng-model="vm.formulario.DESTINATARIO_TIPO" ng-change="vm.alterarDestinatarioTipo()"
			/>
			<label for="destinatario_tipo_ccusto">{{ Lang::get($menu.'.radio-ccusto') }}</label>

		</div>

		<button 
			type="button" 
			class="btn btn-sm btn-primary incluir-item" 
			ng-click="$emit('vsRepeatTrigger'); vm.fixVsRepeat()" 
			data-hotkey="f1" 
			data-toggle="modal" 
			data-target="@{{ (vm.formulario.DESTINATARIO_TIPO == 'usuario') ? '#modal-destinatario-usuario' : '#modal-destinatario-ccusto' }}"
		>
			<span class="glyphicon glyphicon-plus"></span> 
			{{ Lang::get('master.incluir') }}
		</button>

		<button 
			type="button" 
			class="btn btn-sm btn-danger excluir-item"
			ng-click="
				(vm.formulario.DESTINATARIO_TIPO == 'usuario') 
				? vm.excluirUsuarioEscolhido(vm.listaUsuarioSelecEscolhido)
				: vm.excluirCCustoEscolhido(vm.listaCCustoSelecEscolhido)
			"
			data-hotkey="f2"
		>
			<span class="glyphicon glyphicon-trash"></span> 
			{{ Lang::get('master.excluir') }}
		</button>

	</div>

	<div class="row">

		@include('opex._25010.create.bloco-destinatario-table')

	</div>

</fieldset>