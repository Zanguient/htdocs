<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-descritivos') }}</legend>

	<div class="button-container">

		<button 
			type="button" 
			class="btn btn-sm btn-info"
			data-hotkey="alt+a"
			ng-click="$ctrl.Create.addDescritivo()">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-descritivo') }}
		</button>

	</div>

	<div class="item-dinamico-container">

		<div 
			class="item-dinamico"
			ng-repeat="descritivo in $ctrl.Create.fator.DESCRITIVO"
			ng-if="descritivo.STATUSEXCLUSAO != '1'">

			<div class="row">

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-nivel') }}:</label>

					<select
						class="form-control normal-case"
						ng-model="descritivo.NIVEL_ID"
						required>

						<option 
							ng-repeat="nivel in $ctrl.Create.listaFatorNivel" 
							ng-bind="nivel.TITULO +': '+ nivel.FAIXA_INICIAL +' à '+ nivel.FAIXA_FINAL"
							ng-value="nivel.ID"></option>
					</select>

				</div>

				<div class="form-group">

					<label>
						{{ Lang::get($menu.'.label-faixa') }}:
						<span 
							class="glyphicon glyphicon-info-sign"
							title="{{ Lang::get($menu.'.span-faixa-title') }}"></span>
					</label>

					<input 
						type="number" 
						class="form-control input-menor"
						min="0"
						step="0.01"
						ng-model="descritivo.FAIXA_INICIAL">

					<span>{{ Lang::get($menu.'.label-a') }}</span>

					<input 
						type="number" 
						class="form-control input-menor" 
						min="0" 
						step="0.01" 
						ng-model="descritivo.FAIXA_FINAL">

				</div>

			</div>

			<div class="row">

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<div class="textarea-grupo">

						<textarea 
							class="form-control normal-case" 
							rows="4" 
							cols="70" 
							maxlength="500" 
							required 
							ng-model="descritivo.DESCRICAO"></textarea>

						<span class="contador"><span>@{{ 500 - descritivo.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>

					</div>

				</div>

			</div>

			<div class="row">

				<button 
					type="button" 
					class="btn btn-sm btn-danger"
					ng-click="$ctrl.Create.excluirDescritivo(descritivo)">

					<span class="glyphicon glyphicon-trash"></span>
					{{ Lang::get($menu.'.button-excluir-descritivo') }}
				</button>

			</div>

		</div>

	</div>

</fieldset>