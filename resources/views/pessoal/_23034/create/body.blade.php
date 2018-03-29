<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-resumo') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.resumo.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.resumo.ID"
				ng-value="$ctrl.Create.resumo.ID | lpad:[5,'0']">

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
					ng-model="$ctrl.Create.resumo.DESCRICAO"></textarea>

				<span class="contador"><span>@{{ 100 - $ctrl.Create.resumo.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-peso') }}:</label>

			<input 
				type="number" 
				class="form-control input-menor" 
				min="0"
				required 
				ng-model="$ctrl.Create.resumo.PESO">

		</div>

	</div>

</fieldset>