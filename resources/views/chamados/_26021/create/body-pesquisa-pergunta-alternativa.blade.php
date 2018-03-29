<label 
	class="lbl-radio label-alternativa" 
	ng-repeat="altern in $ctrl.Create.pesquisa.ALTERNATIVA track by $index"
	ng-if="(altern.FORMULARIO_PERGUNTA_ID == perg.ID) && (perg.TIPO_RESPOSTA == '1' || perg.TIPO_RESPOSTA == '2')">

	<input 
		type="radio" 
		name="alternativa@{{ altern.FORMULARIO_PERGUNTA_ID }}" 
		required 
		ng-model="perg.RESPOSTA.ALTERNATIVA_ESCOLHIDA_ID" 
		ng-value="altern.ID"				
		ng-change="$ctrl.Create.alterarResposta(perg, altern)"
		ng-disabled="$ctrl.Create.pesquisa.STATUS == '2'">
	
	@{{ altern.DESCRICAO +' ('+ (altern.NOTA | number) +')' }}
</label>

<div 
	class="form-group form-group-textarea justificativa-tipo-2"
	ng-if="perg.TIPO_RESPOSTA == '2'">

	<label class="label-alternativa">{{ Lang::get($menu.'.label-justif-obrig') }}:</label>

	<div class="textarea-grupo">

		<textarea
			class="form-control normal-case" 
			rows="3" 
			cols="100" 
			maxlength="1000"
			ng-required="perg.JUSTIFICATIVA_OBRIGATORIA == 1"
			ng-model="perg.RESPOSTA.DESCRICAO"
			ng-disabled="$ctrl.Create.pesquisa.STATUS == '2'"></textarea>

		<span class="contador"><span>@{{ 1000 - perg.RESPOSTA.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
	
	</div>

</div>

<div 
	class="form-group form-group-textarea"
	ng-if="perg.TIPO_RESPOSTA == '3'">

	<div class="textarea-grupo">

		<textarea 
			class="form-control normal-case" 
			rows="3" 
			cols="100" 
			maxlength="300" 
			required
			ng-model="perg.RESPOSTA.DESCRICAO"
			ng-disabled="$ctrl.Create.pesquisa.STATUS == '2'"></textarea>

		<span class="contador"><span>@{{ 300 - perg.RESPOSTA.DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
	
	</div>

</div>


<div 
	class="form-group form-group-textarea"
	ng-if="$ctrl.Create.pesquisa.STATUS == '2' && perg.RESPOSTA_NEGATIVA == 1">

	<label class="label-alternativa">{{ Lang::get($menu.'.label-solucao') }}:</label>

	<div class="textarea-grupo">

		<textarea
			class="form-control normal-case" 
			rows="3" 
			cols="100" 
			maxlength="300"
			ng-model="perg.RESPOSTA.SOLUCAO"></textarea>

		<span class="contador"><span>@{{ 300 - perg.RESPOSTA.SOLUCAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
	
	</div>

</div>