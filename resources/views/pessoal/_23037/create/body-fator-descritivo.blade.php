<div 
	class="row descritivo-container"
	id="descritivo-container-@{{ fator.ID }}"
	ng-class="{exibe: fator.exibeDescritivo}">

	<div 
		class="form-group"
		ng-repeat="nivel in $ctrl.Create.avaliacao.FATOR_NIVEL"
		ng-if="nivel.FATOR_ID == fator.FATOR_ID">
		
		<label ng-bind="nivel.TITULO +': '+ nivel.FAIXA_INICIAL +' à '+ nivel.FAIXA_FINAL"></label>

		<div class="textarea-grupo">

			<textarea 
				class="form-control normal-case" 
				rows="5" 
				cols="40" 
				maxlength="500" 
				readonly 
				ng-model="nivel.DESCRICAO"></textarea>

		</div>
	
	</div>

</div>