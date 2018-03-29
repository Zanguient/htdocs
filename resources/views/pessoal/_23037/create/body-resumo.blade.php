<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-resumo') }}</legend>

	<div class="item-dinamico-container">

		<div 
			class="item-dinamico"
			ng-repeat="resumo in $ctrl.Create.avaliacao.RESUMO">

			<div class="row">

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<input
						type="text"
						class="form-control input-maior normal-case" 
						readonly
						ng-model="resumo.DESCRICAO">

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-pontuacao-geral') }}:</label>

					<input
						type="text"
						class="form-control input-menor" 
						readonly
						ng-model="resumo.PONTUACAO_GERAL"
						ng-value="resumo.PONTUACAO_GERAL | number:2">

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-peso') }}:</label>

					<input
						type="text"
						class="form-control input-menor" 
						readonly
						ng-model="resumo.PESO"
						ng-value="resumo.PESO | number:2">

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-resultado') }}:</label>

					<input
						type="text"
						class="form-control input-menor" 
						readonly
						ng-model="resumo.RESULTADO"
						ng-value="resumo.RESULTADO | number:2">

				</div>

			</div>

		</div>

	</div>

	@include('pessoal._23037.create.body-resumo-pontuacao')

</fieldset>