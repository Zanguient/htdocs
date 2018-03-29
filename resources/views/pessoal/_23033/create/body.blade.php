<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-formacao') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.formacao.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.formacao.ID"
				ng-value="$ctrl.Create.formacao.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case js-input-descricao" 
					rows="2" 
					cols="40" 
					maxlength="100" 
					required 
					ng-model="$ctrl.Create.formacao.DESCRICAO"></textarea>

				<span class="contador"><span>@{{ 100 - $ctrl.Create.formacao.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-ponto') }}:</label>

			<input 
				type="number" 
				class="form-control input-menor" 
				min="0"
				required 
				ng-model="$ctrl.Create.formacao.PONTO">

		</div>

	</div>

</fieldset>