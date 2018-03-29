<div class="tipo-resposta-container">
					
	<div class="form-group">

		<label>{{ Lang::get($menu.'.label-tipo-resposta') }}:</label>

		<select 
			class="form-control" 
			ng-model="perg.TIPO_RESPOSTA"
			ng-change="vm.selecionarTipoResposta(perg)">
			
			<option 
				ng-repeat="tipoResp in vm.tipoResposta track by $index" 
				ng-value="tipoResp.ID"
				ng-bind="tipoResp.DESCRICAO"></option>
		</select>

	</div>
		
	<div ng-if="perg.TIPO_RESPOSTA == '1' || perg.TIPO_RESPOSTA == '2'">

		@include('opex._25010.create.bloco-pergunta-alternativa')

	</div>

	<div ng-if="perg.TIPO_RESPOSTA == '3'">

		<div class="form-group">
			<textarea class="form-control normal-case" rows="3" cols="50" disabled></textarea>
		</div>

	</div>

</div>