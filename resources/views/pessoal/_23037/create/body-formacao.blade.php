<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-formacao') }}</legend>

	<div 
		class="row"
		ng-repeat="formacao in $ctrl.Create.avaliacao.FORMACAO | orderBy: ['ID']">

		<div class="form-group">

			<label class="lbl-radio">

				<input
					type="radio"
					class="form-control"
					ng-model="$ctrl.Create.avaliacao.FORMACAO_ESCOLHIDA_ID"
					ng-value="formacao.ID"
					ng-change="$ctrl.CreateFormacao.alterarFormacao(formacao)"
					ng-disabled="$ctrl.tipoTela != 'responder'"
					ng-required="!$ctrl.Create.avaliacao.FORMACAO_ESCOLHIDA_ID">

				<span ng-bind="formacao.DESCRICAO +' '"></span>
				<span class="formacao-ponto" ng-bind="formacao.PONTO | number:2"></span>
				<span class="formacao-ponto">{{ Lang::get($menu.'.label-ponto-min') }}.</span>

			</label>

		</div>

	</div>

</fieldset>