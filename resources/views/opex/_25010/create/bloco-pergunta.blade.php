<fieldset ng-disabled="vm.tipoTela == 'exibir'">

	<legend>{{ Lang::get($menu.'.legend-pergunta') }}</legend>

	<div 
		class="bloco-total-nota"
		ng-if="vm.formulario.TIPO == 3">

		<label>{{ Lang::get($menu.'.label-total-nota') }}:</label>
		<label 
			ng-class="{ok: (vm.totalNota | number) == '10,000' || (vm.totalNota | number) == '10'}"
			ng-bind="vm.totalNota | number"></label>
	</div>

	<div class="item-dinamico-container">

		<div class="item-dinamico item-dinamico-pergunta" ng-repeat="perg in vm.pergunta track by $index">

			<div class="row">

				<div class="form-group" ng-if="vm.formulario.TIPO == '3'">

					<label>{{ Lang::get($menu.'.label-indicador') }}:</label>

					<input 
						type="text" 
						ng-model="perg.INDICADOR" 
						class="form-control normal-case">
				
				</div>

				<div class="form-group" ng-if="vm.formulario.TIPO == '3'">

					<label>{{ Lang::get($menu.'.label-tag') }}:</label>

					<input 
						type="text" 
						ng-model="perg.TAG" 
						class="form-control normal-case">
				
				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-descricao') }}:</label>

					<div class="textarea-grupo">

						<textarea 
							class="form-control normal-case pergunta" rows="2" cols="70" maxlength="200" required 
							ng-model="perg.DESCRICAO"
						></textarea>
						<span class="contador"><span>@{{ 200 - perg.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
					
					</div>

				</div>

				<div class="form-group">

					<label>{{ Lang::get($menu.'.label-ordem') }}:</label>

					<input 
						type="number"
						class="form-control input-small"
						min="1"
						ng-model="perg.ORDEM"
						string-to-number>

				</div>

			</div>

			<div class="row">

				@include('opex._25010.create.bloco-pergunta-tipo-resposta')

			</div>

			<div class="row">
				
				<button type="button" class="btn btn-danger btn-pergunta-excluir" title="{{ Lang::get($menu.'.title-excluir-pergunta') }}"
					ng-click="vm.excluirPergunta($index)"
				>
					<span class="glyphicon glyphicon-trash"></span>
					{{ Lang::get('master.excluir') }}
				</button>

			</div>

		</div>

		<button type="button" class="btn btn-info" title="{{ Lang::get($menu.'.title-add-pergunta') }}"
			ng-click="vm.addPergunta()"
		>
			<span class="glyphicon glyphicon-plus"></span>
			{{ Lang::get($menu.'.button-add-pergunta') }}
		</button>

	</div>

</fieldset>