<div 
	class="alternativa-container" 
	ng-repeat="altern in perg.ALTERNATIVA track by $index"
>

	<div ng-if="perg.TIPO_RESPOSTA == '1' || perg.TIPO_RESPOSTA == '2'">

		<input 
			type="radio" 
			name="alternativa@{{ altern.FORMULARIO_PERGUNTA_ID }}" 
			required 
			ng-model="perg.RESPOSTA[0].ALTERNATIVA_ESCOLHIDA_ID" 
			ng-value="altern.ID"
			ng-checked="false"
			ng-change="perg.JUSTIFICATIVA_OBRIGATORIA = altern.JUSTIFICATIVA_OBRIGATORIA"
		/>
		<label>@{{ altern.DESCRICAO }}</label>

	</div>

</div>

<div 
	class="form-group form-group-textarea justificativa-tipo-2"
	ng-if="perg.TIPO_RESPOSTA == '2'"
>
	<label>{{ Lang::get($menu.'.justif-obrig') }}:</label>

	<div class="textarea-grupo">

		<textarea
			class="form-control normal-case" rows="3" cols="150" maxlength="1000"
			ng-required="perg.JUSTIFICATIVA_OBRIGATORIA == 1"
			ng-model="perg.RESPOSTA[0].DESCRICAO"
		></textarea>
		<span class="contador"><span>@{{ 1000 - perg.RESPOSTA[0].DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
	
	</div>

</div>

<div ng-if="perg.TIPO_RESPOSTA == '3'">

	<div class="form-group">

		<div class="textarea-grupo">

			<textarea 
				class="form-control normal-case" rows="3" cols="150" maxlength="1000" required
				ng-model="perg.RESPOSTA[0].DESCRICAO"
			></textarea>
			<span class="contador"><span>@{{ 1000 - perg.RESPOSTA[0].DESCRICAO.length }}</span> {{ Lang::get('master.caract-restante') }}</span>
		
		</div>

	</div>

</div>