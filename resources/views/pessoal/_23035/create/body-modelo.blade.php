<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-modelo') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.modelo.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.modelo.ID"
				ng-value="$ctrl.Create.modelo.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="4" 
					cols="35" 
					maxlength="100" 
					required 
					ng-model="$ctrl.Create.modelo.TITULO"></textarea>

				<span class="contador"><span>@{{ 100 - $ctrl.Create.modelo.TITULO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-instrucoes') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="4" 
					cols="70" 
					maxlength="600" 
					required 
					ng-model="$ctrl.Create.modelo.INSTRUCAO_INICIAL"></textarea>

				<span class="contador"><span>@{{ 600 - $ctrl.Create.modelo.INSTRUCAO_INICIAL.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

	</div>

</fieldset>