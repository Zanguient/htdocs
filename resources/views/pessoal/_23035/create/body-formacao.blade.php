<fieldset 
	class="fieldset-formacao" 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-formacao') }}</legend>

	<div class="button-container button-container-flutuante">

		<button 
			type="button" 
			class="btn btn-sm btn-info"
			data-hotkey="alt+d"
			ng-click="$ctrl.CreateFormacao.addFormacao()">

			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-formacao') }}
		</button>

	</div>

	<div class="item-dinamico-container">

		<div 
			class="item-dinamico"
			ng-repeat="formacao in $ctrl.Create.modelo.FORMACAO"
			ng-if="formacao.STATUSEXCLUSAO != '1'">

			<div class="row">

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<select
						class="form-control normal-case"
						ng-model="formacao.AVALIACAO_DES_FORMACAO_ID"
						ng-change="$ctrl.CreateFormacao.selecionarFormacao(formacao)" 
						required>

						<option
							ng-repeat="frm in $ctrl.CreateFormacao.listaFormacao | orderBy:['DESCRICAO']"
							ng-value="frm.ID"
							ng-bind="frm.DESCRICAO"></option>

					</select>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-ponto') }}:</label>

					<input
						type="number"
						class="form-control input-menor normal-case" 
						readonly
						ng-model="formacao.PONTO">

				</div>

				<div class="form-group">

					<button 
						type="button" 
						class="btn btn-danger"
						ng-click="$ctrl.CreateFormacao.excluirFormacao(formacao)">

						<span class="glyphicon glyphicon-trash"></span>
					</button>

				</div>

			</div>

		</div>

	</div>

</fieldset>