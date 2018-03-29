<fieldset ng-disabled="vm.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-formulario') }}</legend>
	
	<div class="row">

		<div 
			class="form-group" 
			ng-if="vm.tipoTela != 'incluir'">
			
			<label>{{ Lang::get($menu.'.label-id') }}:</label>
			<input type="text" ng-model="vm.formulario.ID" class="form-control input-menor" disabled />
		</div>
		
		<div class="form-group">
			<label>{{ Lang::get($menu.'.label-tipo') }}:</label>

			<select 
				id="formulario-tipo" 
				class="form-control" 
				ng-model="vm.formulario.TIPO" 
				ng-disabled="vm.urlTipoForm == 3"
				required>

				{{-- 
					Se o tipo 3 for passado na url, exibe todos os tipos (pesq. cliente);
					Senão, não exibe o tipo 3 (pesq. clima e satisf.).
				--}}
				<option 
					ng-repeat="tipoForm in vm.tipoFormulario track by $index"
					ng-if="((vm.urlTipoForm == 3) || (vm.urlTipoForm != 3 && tipoForm.ID != 3))"
					ng-value="tipoForm.ID"
					ng-bind="tipoForm.DESCRICAO"></option>
			</select>
		</div>

		<div 
			class="form-group"
			ng-if="vm.tipoTela != 'incluir'">

			<label>{{ Lang::get($menu.'.label-data-criacao') }}:</label>
			<input type="date" ng-model="vm.formulario.DATAHORA_INSERT" class="form-control">
		</div>

		<div 
			class="form-group periodo"
			ng-if="vm.urlTipoForm != 3">

			<label>{{ Lang::get($menu.'.label-periodo-resposta') }}:</label>
			<input type="date" ng-model="vm.formulario.PERIODO_INI" class="form-control data-ini" required />
			<label class="periodo-a">{{ Lang::get('master.periodo-a') }}</label>
			<input type="date" ng-model="vm.formulario.PERIODO_FIM" class="form-control data-fim" required />
		</div>

	</div>

	<div class="row">

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="50" 
					maxlength="100" 
					required 
					ng-model="vm.formulario.TITULO"></textarea>

				<span class="contador"><span>@{{ 100 - vm.formulario.TITULO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
			
			</div>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="2" 
					cols="70" 
					maxlength="300" 
					required 
					ng-model="vm.formulario.DESCRICAO"></textarea>

				<span class="contador"><span>@{{ 300 - vm.formulario.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
			
			</div>

		</div>

	</div>

</fieldset>