<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-fator') }}</legend>

	<div class="row">

		<div class="form-group" ng-if="$ctrl.Create.fator.ID > 0">

			<label>{{ Lang::get($menu.'.label-id') }}:</label>

			<input 
				type="text" 
				class="form-control input-menor" 
				disabled 
				ng-model="$ctrl.Create.fator.ID"
				ng-value="$ctrl.Create.fator.ID | lpad:[5,'0']">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-titulo') }}:</label>

			<input 
				type="text" 
				class="form-control input-maior normal-case js-input-titulo" 
				required 
				ng-model="$ctrl.Create.fator.TITULO">

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-tipo') }}:</label>

			<select
				class="form-control normal-case"
				ng-model="$ctrl.Create.fator.TIPO_ID"
				required>

				<option 
					ng-repeat="tipo in $ctrl.Create.listaFatorTipo" 
					ng-bind="tipo.TITULO"
					ng-value="tipo.ID"></option>
			</select>

		</div>

		<div class="form-group">

			<label>
				{{ Lang::get($menu.'.label-ordem-perc') }}:
				<span 
					class="glyphicon glyphicon-info-sign"
					title="{{ Lang::get($menu.'.span-ordem-perc-title') }}"></span>
			</label>

			<select
				class="form-control normal-case"
				ng-model="$ctrl.Create.fator.ORDEM_PERC_NIVEL">

				<option 
					ng-bind="'Crescente'"
					ng-value="'0'"></option>
				<option 
					ng-bind="'Decrescente'"
					ng-value="'1'"></option>
			</select>

		</div>

		<div class="form-group">

			<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

			<div class="textarea-grupo">

				<textarea 
					class="form-control normal-case" 
					rows="3" 
					cols="70" 
					maxlength="500" 
					ng-model="$ctrl.Create.fator.DESCRICAO"></textarea>

				<span class="contador"><span>@{{ 500 - $ctrl.Create.fator.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

			</div>

		</div>

	</div>

</fieldset>