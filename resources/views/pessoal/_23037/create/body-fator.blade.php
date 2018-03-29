<fieldset 
	ng-disabled="$ctrl.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-fator') }}</legend>

	<div 
		class="fator-tipo"
		ng-repeat="tipo in $ctrl.Create.avaliacao.FATOR_TIPO">

		<h4 
			class="title" 
			ng-bind="tipo.TITULO"></h4>

		<div class="item-dinamico-container">

			<div 
				class="item-dinamico"
				ng-repeat="fator in $ctrl.Create.avaliacao.FATOR"
				ng-if="fator.TIPO_ID == tipo.ID">

				<div class="row">

					<div class="form-group">

						<label>{{ Lang::get($menu.'.label-fator') }}:</label>

						<input
							type="text"
							class="form-control normal-case input-maior-min" 
							readonly
							ng-model="fator.TITULO">

					</div>

					<div class="form-group">

						<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

						<div class="textarea-grupo">

							<textarea 
								class="form-control normal-case" 
								rows="3" 
								cols="50"
								readonly 
								ng-model="fator.DESCRICAO"></textarea>

						</div>

					</div>

					<div class="form-group">
						
						<label>{{ Lang::get($menu.'.label-ponto') }}:</label>

						<input
							type="number"
							class="form-control input-menor" 
							min="0"
							step="0.01"
							ng-model="fator.PONTO"
							ng-change="$ctrl.CreateFator.calcularPontuacao()"
							ng-disabled="$ctrl.tipoTela != 'responder'"
							ng-readonly="fator.jaCalculado"
							required>
					
					</div>

					<div class="form-group">

						<button 
							type="button"
							class="btn btn-info"
							ng-init="fator.exibeDescritivo = false"
							ng-click="$ctrl.CreateFator.exibirDescritivo(fator)">

							{{ Lang::get($menu.'.button-descritivos') }}

							<span 
								class="glyphicon"
								ng-class="{
									false: 'glyphicon-triangle-bottom', 
									true : 'glyphicon-triangle-top'
								}[fator.exibeDescritivo]"></span>

						</button>

					</div>

				</div>

				@include('pessoal._23037.create.body-fator-descritivo')

			</div>

		</div>

	</div>

	@include('pessoal._23037.create.body-fator-pontuacao')

</fieldset>