<fieldset class="fieldset-formacao">

	<legend>{{ Lang::get($menu.'.legend-formacao') }}</legend>

	<div 
		class="row"
		ng-repeat="formacao in $ctrl.Create.avaliacao.FORMACAO | orderBy: ['ID']">

		<div class="form-group">

			<span 
				class="span-icon-check"
				ng-if="$ctrl.Create.avaliacao.FORMACAO_ESCOLHIDA_ID == formacao.ID">&#9745;</span>

			<span 
				class="span-icon-check"
				ng-if="$ctrl.Create.avaliacao.FORMACAO_ESCOLHIDA_ID != formacao.ID">&#9744;</span>

			<span ng-bind="formacao.DESCRICAO +' '"></span>
			<span class="formacao-ponto" ng-bind="formacao.PONTO | number:2"></span>
			<span class="formacao-ponto">{{ Lang::get($menu.'.label-ponto-min') }}.</span>

		</div>

	</div>

</fieldset>