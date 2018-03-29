<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-nivel') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.nivel.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.nivel.ID"
				ng-value="$ctrl.Create.nivel.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<input 
				type="text" 
				class="form-control input-maior normal-case js-input-titulo" 
				required 
				ng-model="$ctrl.Create.nivel.TITULO">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-faixa') }}:</label>

			<input 
				type="number" 
				class="form-control input-menor"
				min="0" 
				required 
				ng-model="$ctrl.Create.nivel.FAIXA_INICIAL">

			<span>{{ Lang::get($menu.'.label-a') }}</span>

			<input 
				type="number" 
				class="form-control input-menor" 
				min="0"
				required 
				ng-model="$ctrl.Create.nivel.FAIXA_FINAL">

		</div>

	</div>

</fieldset>