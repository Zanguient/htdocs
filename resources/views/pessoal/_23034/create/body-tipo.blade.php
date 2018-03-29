<fieldset
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-tipo-fator-relac') }}</legend>

	<div class="button-container">
		
		<button 
			type="button"
			class="btn btn-sm btn-info"
			ng-click="$ctrl.Create.addFatorTipo()">
					
			<span class="glyphicon glyphicon-plus"></span>

			{{ Lang::get($menu.'.button-adicionar') }}

		</button>

	</div>

	<div 
		class="form-group"
		ng-repeat="tipo in $ctrl.Create.resumo.FATOR_TIPO"
		ng-if="tipo.STATUSEXCLUSAO != '1'">

		<select
			class="form-control normal-case"
			ng-model="tipo.FATOR_TIPO_ID">

			<option
				ng-repeat="tp in $ctrl.Index.listaFatorTipo"
				ng-value="tp.FATOR_TIPO_ID"
				ng-bind="tp.FATOR_TIPO_TITULO"></option>

		</select>

		<button 
			type="button"
			class="btn btn-danger"
			ng-click="$ctrl.Create.excluirFatorTipo(tipo)">
					
			<span class="glyphicon glyphicon-trash"></span>

		</button>

	</div>

</fieldset>