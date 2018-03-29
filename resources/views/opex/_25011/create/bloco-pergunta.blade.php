<fieldset ng-disabled="vm.formulario.STATUS == '0' || vm.tipoTela == 'exibir' || vm.formulario.DESTINATARIO_STATUS_RESPOSTA == '1'">

	<div class="item-dinamico-container">

		<div class="item-dinamico item-dinamico-pergunta" ng-repeat="perg in vm.pergunta track by $index">

			<div class="row">

				<div class="form-group">

					<label class="lbl-pergunta">@{{ perg.ORDEM | lpad : [2, '0'] }}@{{ '. '+ perg.DESCRICAO }}</label>

					@include('opex._25011.create.bloco-alternativa')

				</div>

			</div>

		</div>

	</div>

</fieldset>