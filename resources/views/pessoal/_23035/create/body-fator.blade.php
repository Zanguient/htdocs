<fieldset 
	class="fieldset-fator" 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-fator') }}</legend>

	<div class="button-container button-container-flutuante">

		<button 
			type="button" 
			class="btn btn-sm btn-info"
			data-hotkey="alt+a"
			ng-click="$ctrl.CreateFator.addFator()">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-fator') }}
		</button>

	</div>

	<div class="item-dinamico-container">

		<div 
			class="item-dinamico"
			ng-repeat="fator in $ctrl.Create.modelo.FATOR"
			ng-if="fator.STATUSEXCLUSAO != '1'">

			<div class="row">

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-fator') }}:</label>

					<select
						class="form-control normal-case"
						ng-model="fator.AVALIACAO_DES_FATOR_ID"
						ng-change="$ctrl.CreateFator.selecionarFator(fator)" 
						required>

						<option
							ng-repeat="ftr in $ctrl.CreateFator.listaFator | orderBy:['TITULO']"
							ng-value="ftr.ID"
							ng-bind="ftr.TITULO"></option>

					</select>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-tipo') }}:</label>

					<input
						type="text"
						class="form-control normal-case" 
						readonly
						ng-model="fator.TIPO_TITULO">

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<div class="textarea-grupo">

						<textarea 
							class="form-control normal-case" 
							rows="2" 
							cols="40" 
							readonly 
							ng-model="fator.DESCRICAO"></textarea>

					</div>

				</div>

				<div class="form-group">
					
					<button 
						type="button" 
						class="btn btn-danger"
						ng-click="$ctrl.CreateFator.excluirFator(fator)">

						<span class="glyphicon glyphicon-trash"></span>
					</button>
				
				</div>

			</div>

		</div>

	</div>

</fieldset>