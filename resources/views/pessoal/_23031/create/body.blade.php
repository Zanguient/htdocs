<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-tipo') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.tipo.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.tipo.ID"
				ng-value="$ctrl.Create.tipo.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<input 
				type="text" 
				class="form-control input-maior normal-case js-input-titulo" 
				required 
				ng-model="$ctrl.Create.tipo.TITULO">

		</div>

	</div>

</fieldset>