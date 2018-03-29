<fieldset
	class="fieldset-resumo" 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-resumo') }}</legend>

	<div class="button-container button-container-flutuante">

		<button 
			type="button" 
			class="btn btn-sm btn-info"
			data-hotkey="alt+r"
			ng-click="$ctrl.CreateResumo.addResumo()">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-resumo') }}
		</button>

	</div>

	<div class="item-dinamico-container">

		<div 
			class="item-dinamico"
			ng-repeat="resumo in $ctrl.Create.modelo.RESUMO"
			ng-if="resumo.STATUSEXCLUSAO != '1'">

			<div class="row">

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<select
						class="form-control normal-case"
						ng-model="resumo.AVALIACAO_DES_RESUMO_ID"
						ng-change="$ctrl.CreateResumo.selecionarResumo(resumo)" 
						required>

						<option
							ng-repeat="rsm in $ctrl.CreateResumo.listaResumo | orderBy:['DESCRICAO']"
							ng-value="rsm.ID"
							ng-bind="rsm.DESCRICAO"></option>

					</select>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-peso') }}:</label>

					<input
						type="number"
						class="form-control input-menor normal-case" 
						readonly
						ng-model="resumo.PESO">

				</div>

				<div class="form-group">

					<button 
						type="button" 
						class="btn btn-danger"
						ng-click="$ctrl.CreateResumo.excluirResumo(resumo)">

						<span class="glyphicon glyphicon-trash"></span>
					</button>

				</div>

			</div>

		</div>

	</div>

	<div class="form-group">

		<label>{{ Lang::get($menu.'.label-pontuacao-final') }}:</label>

		<input
			type="number"
			class="form-control input-menor normal-case" 
			readonly
			ng-model="$ctrl.CreateResumo.pesoFinal">

	</div>

</fieldset>